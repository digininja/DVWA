<?php

# var_dump ( openssl_get_cipher_methods());

function encrypt ($plaintext, $key, $iv) {
	$e = openssl_encrypt($plaintext, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
	if ($e === false) {
		throw new Exception ("Encryption failed");
	}
	return $e;
}
function decrypt ($ciphertext, $key, $iv) {
	$e = openssl_decrypt($ciphertext, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
	if ($e === false) {
		throw new Exception ("Decryption failed");
	}
	return $e;
}

// This is how it should be done securely
$iv = random_bytes(16);

/*
// Keeping the clear text to 16 characters or less will keep us in a single block
$e = encrypt ("hello", $key, $iv);

*/

$iv = "1234567812345678";
$key = "your key 16bytes";

print "IV: " . ($iv) . "\n";
$clear = "hello from PHP";
print "Clear text: ";
var_dump ($clear);
$e = encrypt ($clear, $key, $iv);
var_dump (base64_encode($e));

$e = base64_decode ("6ICeeJ5rgksuK9KWTT3e8A==");

$d = decrypt ($e, $key, $iv);

var_dump ($d);
