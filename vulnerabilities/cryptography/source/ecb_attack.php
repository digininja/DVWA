<?php
function encrypt ($plaintext, $key) {
	$e = openssl_encrypt($plaintext, 'aes-128-ecb', $key, OPENSSL_PKCS1_PADDING);
	if ($e === false) {
		throw new Exception ("Encryption failed");
	}
	return $e;
}
function decrypt ($ciphertext, $key) {
	$e = openssl_decrypt($ciphertext, 'aes-128-ecb', $key, OPENSSL_PKCS1_PADDING);
	if ($e === false) {
		throw new Exception ("Decryption failed");
	}
	return $e;
}

$key = "ik ben een aardbei";

$sooty_plaintext  = '{"user":"sooty",';
$sooty_plaintext .= '"ex":1723620672,';
$sooty_plaintext .= '"level":"admin",';
$sooty_plaintext .= '"bio":"Izzy wizzy let\'s get busy"}';

$sweep_plaintext  = '{"user":"sweep",';
$sweep_plaintext .= '"ex":1723620672,';
$sweep_plaintext .= '"level": "user",';
$sweep_plaintext .= '"bio": "Squeeeeek"}';

$soo_plaintext  = '{"user" : "soo",';
$soo_plaintext .= '"ex":1823620672,';
$soo_plaintext .= '"level": "user",';
$soo_plaintext .= '"bio": "I won The Weakest Link"}';

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

print "Soo Plaintext\n";
var_dump ($soo_plaintext);
$soo_ciphered = encrypt($soo_plaintext, $key);
print "Soo Ciphertext\n";
var_dump (bin2hex($soo_ciphered));
print "\n";

$p1 = substr (bin2hex($sweep_ciphered), 0, 32); // Sweep's username
$p2 = substr (bin2hex($soo_ciphered), 32, 32); // Soo's expiry time
$p3 = substr (bin2hex($sooty_ciphered), 64, 32); // Sooty's admin status
$p4 = substr (bin2hex($sweep_ciphered), 96); // What's left

$c = hex2bin($p1 . $p2 . $p3 . $p4);

print "Breaking the tokens down into blocks\n";

print "Block 1, Sweep's username\n";
var_dump ($p1);

print "Block 2, Soo's expiry time\n";
var_dump ($p2);

print "Block 3, Sooty's admin status\n";
var_dump ($p3);

print "Block 4, Finish off the block\n";
var_dump ($p4);

print "\n";
print "New token:\n";
var_dump (bin2hex($c));
print "\n";

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
