<?php
// Argon2id password hashing with upgrade-on-login (OWASP minimum parameters).
const PH_OPTIONS = ['memory_cost' => 19456, 'time_cost' => 2, 'threads' => 1];

function ph_hash(string $plain): string {
    return password_hash($plain, PASSWORD_ARGON2ID, PH_OPTIONS);
}

// Returns true if the login is valid. Old MD5 rows are accepted once, then upgraded to Argon2id.
function ph_verify_and_upgrade($db, string $user, string $plain): bool {
    $st = mysqli_prepare($db, 'SELECT user_id, password FROM users WHERE user = ? LIMIT 1');
    mysqli_stmt_bind_param($st, 's', $user);
    mysqli_stmt_execute($st);
    mysqli_stmt_bind_result($st, $uid, $stored);
    $found = mysqli_stmt_fetch($st);
    mysqli_stmt_close($st);

    if (!$found) {
        password_verify($plain, ph_hash('dummy'));   // similar timing for unknown users
        return false;
    }

    $ok = false; $upgrade = false;
    if (str_starts_with($stored, '$argon2id$')) {
        $ok = password_verify($plain, $stored);
        $upgrade = $ok && password_needs_rehash($stored, PASSWORD_ARGON2ID, PH_OPTIONS);
    } elseif (strlen($stored) === 32 && ctype_xdigit($stored)) {   // legacy MD5
        $ok = hash_equals($stored, md5($plain));
        $upgrade = $ok;
    }
    if ($upgrade) {
        $new = ph_hash($plain);
        $up = mysqli_prepare($db, 'UPDATE users SET password = ? WHERE user_id = ?');
        mysqli_stmt_bind_param($up, 'si', $new, $uid);
        mysqli_stmt_execute($up);
        mysqli_stmt_close($up);
    }
    return $ok;
}
