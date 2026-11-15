<?php
/**
 * password_hashing.php  -  replace DVWA's unsalted md5() with Argon2id + upgrade-on-login.
 *
 * Why: md5() is fast and unsalted, so a stolen `users` table can be cracked at billions of guesses
 * per second, and identical passwords show identical hashes (admin and smithy both use "password").
 * Argon2id is salted (automatic) and memory-hard, which makes every guess expensive.
 *
 * Parameters follow the OWASP Password Storage Cheat Sheet minimum for Argon2id
 * (19 MiB memory, 2 iterations, 1 thread). Tune them on your own machine.
 *
 * DATABASE CHANGE REQUIRED (DVWA creates `password varchar(32)`, too short for an Argon2id hash):
 *     ALTER TABLE users MODIFY password VARCHAR(255);
 */

const PH_OPTIONS = ['memory_cost' => 19456, 'time_cost' => 2, 'threads' => 1];

function ph_hash(string $plain): string {
    return password_hash($plain, PASSWORD_ARGON2ID, PH_OPTIONS);
}

/**
 * Check a login. Returns the user row on success, false on failure.
 * Old accounts still holding an MD5 hash are accepted once, then silently upgraded to Argon2id.
 */
function ph_verify_and_upgrade(PDO $db, string $user, string $plain) {
    $st = $db->prepare('SELECT * FROM users WHERE user = ? LIMIT 1');
    $st->execute([$user]);
    $row = $st->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        password_verify($plain, ph_hash('dummy'));   // spend similar time so unknown users are not revealed
        return false;
    }

    $stored = $row['password'];
    $ok = false;
    if (str_starts_with($stored, '$argon2id$')) {
        $ok = password_verify($plain, $stored);
        if ($ok && password_needs_rehash($stored, PASSWORD_ARGON2ID, PH_OPTIONS)) {
            ph_store($db, (int)$row['user_id'], $plain);
        }
    } elseif (strlen($stored) === 32 && ctype_xdigit($stored)) {   // legacy MD5 row
        $ok = hash_equals($stored, md5($plain));
        if ($ok) {
            ph_store($db, (int)$row['user_id'], $plain);           // migrate on first successful login
        }
    }
    return $ok ? $row : false;
}

function ph_store(PDO $db, int $userId, string $plain): void {
    $st = $db->prepare('UPDATE users SET password = ? WHERE user_id = ?');
    $st->execute([ph_hash($plain), $userId]);
}
