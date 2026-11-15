<?php
/**
 * Preliminary tests for password_hashing.php  -  run:  php test_password_hashing.php
 * Uses an in-memory SQLite table that copies DVWA's seeded MD5 users, so it does not touch your Docker DB.
 */
require __DIR__ . '/password_hashing.php';

$pass = 0; $fail = 0;
function check(string $name, bool $ok): void {
    global $pass, $fail;
    $ok ? $pass++ : $fail++;
    echo ($ok ? "PASS  " : "FAIL  ") . $name . "\n";
}

// DVWA's seeded accounts (from dvwa/includes/DBMS/MySQL.php)
$seed = [[1,'admin','password'],[2,'gordonb','abc123'],[3,'1337','charley'],[4,'pablo','letmein'],[5,'smithy','password']];
$db = new PDO('sqlite::memory:');
$db->exec('CREATE TABLE users (user_id INT, user TEXT, password TEXT)');
$ins = $db->prepare('INSERT INTO users VALUES (?,?,?)');
foreach ($seed as [$id, $u, $p]) { $ins->execute([$id, $u, md5($p)]); }

// --- BEFORE: what is wrong with the MD5 table
$hashes = $db->query('SELECT user, password FROM users')->fetchAll(PDO::FETCH_KEY_PAIR);
check("BEFORE: admin and smithy have identical hashes (no salt)", $hashes['admin'] === $hashes['smithy']);

$wordlist = ['123456','password','12345678','qwerty','abc123','letmein','monkey','charley','dragon','111111'];
$t = hrtime(true); $cracked = 0;
foreach ($hashes as $u => $h) { foreach ($wordlist as $w) { if (md5($w) === $h) { $cracked++; break; } } }
$ms = (hrtime(true) - $t) / 1e6;
printf("INFO  BEFORE: %d of %d MD5 hashes cracked with a 10-word list in %.3f ms\n", $cracked, count($hashes), $ms);

// --- Speed of one guess: the number that matters
$N = 20000; $t = hrtime(true);
for ($i = 0; $i < $N; $i++) { md5("guess$i"); }
$md5_us = (hrtime(true) - $t) / 1e3 / $N;
$t = hrtime(true); $h1 = ph_hash("guess"); $argon_us = (hrtime(true) - $t) / 1e3;
printf("INFO  one guess: md5 = %.2f microseconds | argon2id = %.0f microseconds (about %dx slower)\n",
       $md5_us, $argon_us, (int)round($argon_us / max($md5_us, 0.001)));

// --- AFTER: functional tests of the upgrade logic
check("legacy MD5 user can still log in (and gets upgraded)", ph_verify_and_upgrade($db, 'admin', 'password') !== false);
$new = $db->query("SELECT password FROM users WHERE user='admin'")->fetchColumn();
check("admin row is now Argon2id", str_starts_with($new, '$argon2id$'));
check("upgraded user can log in again", ph_verify_and_upgrade($db, 'admin', 'password') !== false);
check("wrong password rejected", ph_verify_and_upgrade($db, 'admin', 'wrong') === false);
check("unknown user rejected", ph_verify_and_upgrade($db, 'nobody', 'password') === false);
check("empty password rejected", ph_verify_and_upgrade($db, 'gordonb', '') === false);

ph_verify_and_upgrade($db, 'smithy', 'password');
$a = $db->query("SELECT password FROM users WHERE user='admin'")->fetchColumn();
$s = $db->query("SELECT password FROM users WHERE user='smithy'")->fetchColumn();
check("AFTER: admin and smithy (same password) now have different hashes (salted)", $a !== $s);
check("hash length fits in VARCHAR(255) (was VARCHAR(32)): " . strlen($a), strlen($a) <= 255 && strlen($a) > 32);

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
