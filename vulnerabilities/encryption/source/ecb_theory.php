<?php
function encrypt ($plaintext, $key) {
	if (strlen($plaintext) % 16 != 0) {
		throw new Exception ("Plaintext is not a multiple of 16, encryption aborted.");
	}
		
	$e = openssl_encrypt($plaintext, 'aes-128-ecb', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);
	if ($e === false) {
		throw new Exception ("Encryption failed");
	}
	return $e;
}
function decrypt ($ciphertext, $key) {
	if (strlen($ciphertext) % 16 != 0) {
		throw new Exception ("Ciphertext is not a multiple of 16, decryption aborted.");
	}
		
	$e = openssl_decrypt($ciphertext, 'aes-128-ecb', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);
	if ($e === false) {
		throw new Exception ("Decryption failed");
	}
	return $e;
}

$key = "hello";

// These are all blocks of 16 characters so I
// don't need to worry about padding.

$sooty_plaintext  = '{"user":"sooty",';
$sooty_plaintext .= '"ex":1723620672,';
$sooty_plaintext .= '"level":"admin"}';

$sweep_plaintext  = '{"user":"sweep",';
$sweep_plaintext .= '"ex":1723620672,';
$sweep_plaintext .= '"level": "user"}';

$sue_plaintext  = '{"user" : "sue",';
$sue_plaintext .= '"ex":1823620672,';
$sue_plaintext .= '"level": "user"}';

print "Sooty Plaintext\n";
var_dump ($sooty_plaintext);
$sooty_ciphered = encrypt($sooty_plaintext, $key);
print "Sooty Ciphertext\n";
var_dump (bin2hex($sooty_ciphered));
print "\n";

print "Sweep Plaintext\n";
var_dump ($sweep_plaintext);
$sweep_ciphered = encrypt($sweep_plaintext, $key);
print "Sweep Ciphertext\n";
var_dump (bin2hex($sweep_ciphered));
print "\n";

print "Sue Plaintext\n";
var_dump ($sue_plaintext);
$sue_ciphered = encrypt($sue_plaintext, $key);
print "Sue Ciphertext\n";
var_dump (bin2hex($sue_ciphered));
print "\n";

$p1 = substr (bin2hex($sweep_ciphered), 0, 32); // Sweep's username
$p2 = substr (bin2hex($sue_ciphered), 32, 32); // Sue's expiry time
$p3 = substr (bin2hex($sooty_ciphered), 64, 32); // Sooty's admin status

$c = hex2bin($p1 . $p2 . $p3);
if (strlen($c) != 48) {
	throw new Exception ("Wrong token length.");
}

$hacked_deciphered = decrypt($c, $key);
print "Decrypted after swapping blocks around:\n";
var_dump ($hacked_deciphered);
$user = json_decode ($hacked_deciphered);
if ($user === null) {
	throw new Exception ("Could not decode JSON object.");
}
print "\n";
print "Converted to a JSON object:\n";
var_dump ($user);

if ($user->user == "sweep" && $user->ex > time() && $user->level == "admin") {
	print "Welcome administrator Sweep\n";
} else {
	print "Failed\n";
}

?>
