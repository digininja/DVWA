<?php
/**
 * secure_crypto.php  -  AES-256-GCM helpers to replace the repeating-key XOR in DVWA.
 *
 * Output format (then base64):   version(1) | nonce(12) | ciphertext | tag(16)
 *
 * Design choices (explain these in the report):
 *  - AES-256-GCM  : authenticated encryption = confidentiality + integrity in one step (NIST SP 800-38D).
 *  - random nonce : a fresh 12-byte value from the OS CSPRNG for EVERY message. Reusing a nonce with the
 *                   same key breaks GCM, so the nonce is never chosen by the caller.
 *  - key handling : the key is NOT in the source code. It is read from a Docker secret file or an
 *                   environment variable and must be exactly 32 random bytes.
 *  - AAD          : optional "associated data" (e.g. the user id) is authenticated but not encrypted,
 *                   so a ciphertext cannot be moved to another context.
 *  - fail closed  : any problem returns false. No partial plaintext is ever returned.
 */

const SC_CIPHER    = 'aes-256-gcm';
const SC_VERSION   = "\x01";
const SC_NONCE_LEN = 12;
const SC_TAG_LEN   = 16;
const SC_KEY_LEN   = 32;

/** Load the 32-byte key. Returns the raw key, or false if it is missing or has the wrong length. */
function sc_load_key() {
    $file = getenv('CRYPTO_KEY_FILE');            // preferred: a Docker secret mounted as a file
    $b64  = ($file && is_readable($file)) ? trim(file_get_contents($file)) : getenv('CRYPTO_KEY_B64');
    if (!$b64) {
        return false;
    }
    $key = base64_decode($b64, true);
    return ($key !== false && strlen($key) === SC_KEY_LEN) ? $key : false;
}

/** Encrypt. Returns base64 string, or false on error. */
function sc_encrypt(string $plaintext, string $key, string $aad = '') {
    if (strlen($key) !== SC_KEY_LEN) {
        return false;
    }
    $nonce = random_bytes(SC_NONCE_LEN);          // fresh random nonce every time
    $tag   = '';
    $ct = openssl_encrypt($plaintext, SC_CIPHER, $key, OPENSSL_RAW_DATA, $nonce, $tag, $aad, SC_TAG_LEN);
    if ($ct === false) {
        return false;
    }
    return base64_encode(SC_VERSION . $nonce . $ct . $tag);
}

/** Decrypt. Returns plaintext, or false if the message is malformed or fails authentication. */
function sc_decrypt(string $b64, string $key, string $aad = '') {
    if (strlen($key) !== SC_KEY_LEN) {
        return false;
    }
    $raw = base64_decode($b64, true);
    if ($raw === false || strlen($raw) < 1 + SC_NONCE_LEN + SC_TAG_LEN || $raw[0] !== SC_VERSION) {
        return false;
    }
    $nonce = substr($raw, 1, SC_NONCE_LEN);
    $tag   = substr($raw, -SC_TAG_LEN);
    $ct    = substr($raw, 1 + SC_NONCE_LEN, -SC_TAG_LEN);
    // openssl_decrypt checks the tag BEFORE returning anything; on mismatch it returns false.
    return openssl_decrypt($ct, SC_CIPHER, $key, OPENSSL_RAW_DATA, $nonce, $tag, $aad);
}
