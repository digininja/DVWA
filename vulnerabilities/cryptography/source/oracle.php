<?php

/*
Openssl on the command line:

openssl enc -nosalt -p -aes-128-cbc -in un_encrypted.data -out encrypted.data
openssl enc -d -aes-128-cbc -p -nosalt -in encrypted.data -out un_encrypted.data

clear text = hello
password = a
key = CA978112CA1BBDCAFAC231B39A23DC4D
iv = A786EFF8147C4E72B9807785AFEE48BB
data = 745506d1a3787f98c7ec064764ad94ad

*/

# PKCS7 pad string

# https://stackoverflow.com/questions/7314901/how-to-add-remove-pkcs7-padding-from-an-aes-encrypted-string

# var_dump ( openssl_get_cipher_methods());

function xor_byte_array ($a1, $a2) {
	if (count ($a1) != count ($a2)) {
		throw new Exception ("Arrays are different length");
	}
	$out = [];
	for ($i = 0; $i < count ($a1); $i++) {
		$out[] = $a1[$i] ^ $a2[$i];
	}
	return $out;
}

function byte_array_to_string ($array) {
	$str = "";
	foreach ($array as $c) {
		$str .= "0x" . sprintf ("%02x", $c) . " ";
	}
	return $str;
}

function zero_array($length) {
	$array = [];
	for ($i = 0; $i < $length; $i++) {
		$array[$i] = 0x00;
	}
	return $array;
}

define ("KEY", "my key 16 bytes.");

function encrypt ($plaintext, $iv) {
	# Default padding is PKCS#7 which is interchangable with PKCS#5
	# https://en.wikipedia.org/wiki/Padding_%28cryptography%29#PKCS#5_and_PKCS#7

	if (strlen ($iv) != 16) {
		throw new Exception ("IV must be 16 bytes, " . strlen ($iv) . " passed");
	}
	$e = openssl_encrypt($plaintext, 'aes-128-cbc', KEY, OPENSSL_RAW_DATA, $iv);
	if ($e === false) {
		throw new Exception ("Encryption failed");
	}
	return $e;
}

function decrypt ($ciphertext, $iv) {
	if (strlen ($iv) != 16) {
		throw new Exception ("IV must be 16 bytes, " . strlen ($iv) . " passed");
	}
	$e = openssl_decrypt($ciphertext, 'aes-128-cbc', KEY, OPENSSL_RAW_DATA, $iv);
	if ($e === false) {
		throw new Exception ("Decryption failed");
	}
	return $e;
}

function create_token () {
	$token = "userid:2";

	# This gives a string of 16 random bytes
	$iv_string = random_bytes(16);

	$e = encrypt ($token, $iv_string);
	$data = array (
					"token" => base64_encode ($e),
					"iv" => base64_encode ($iv_string)
				);
	return json_encode($data);
}

$t = json_decode (create_token(), true);
var_dump ($t);
$token = $t['token'];
$iv_string_b64 = $t['iv'];
$iv_string = base64_decode ($iv_string_b64);
$iv = unpack('C*', $iv_string);

$o = make_call ($token, $iv);

var_dump ($o);
exit;

function make_call ($token, $iv) {
	# This maps the IV byte array down to a string
	$iv_string = implode(array_map("chr", $iv));

	# Now base64 encode it so it is safe to send
	$iv_string_b64 = base64_encode ($iv_string);

	$data = array (
					"token" => $token,
					"iv" => $iv_string_b64
				);
	$ret = check_token (json_encode ($data));
	return $ret;
}

function check_token ($data) {
	$users = array ();
	$users[1] = array ("name" => "Geoffery", "level" => "admin");
	$users[2] = array ("name" => "Bungle", "level" => "user");
	$users[3] = array ("name" => "Zippy", "level" => "user");
	$users[4] = array ("name" => "George", "level" => "user");

	$data_array = false;
	try {
		$data_array = json_decode ($data, true);
	} catch (TypeError $exp) {
		$ret = array (
						"status" => 503,
						"message" => "Data in wrong format",
						"extra" => $exp->getMessage()
					);
	}
	var_dump ($data_array);

	if ($data_array === false) {
		$ret = array (
						"status" => 502,
						"message" => "Data in wrong format"
					);
	} else {
		$ciphertext = base64_decode ($data_array['token']);
		$iv = base64_decode ($data_array['iv']);

		$ret = array (
						"status" => 500,
						"message" => "Unknown error"
					);
		try {
			$d = decrypt ($ciphertext, $iv); 
			if (preg_match ("/^userid:(\d+)$/", $d, $matches)) {
				$id = $matches[1];
				if (array_key_exists ($id, $users)) {
					$user = $users[$id];
					$ret = array (
									"status" => 200,
									"user" => $user["name"],
									"level" => $user['level']
								);
				} else {
					$ret = array (
									"status" => 500,
									"message" => "User not found"
								);
				}
			}
		} catch(Exception $exp) {
			$ret = array (
							"status" => 501,
							"message" => "Unable to decrypt token",
							"extra" => $exp->getMessage()
						);
		}
	}
	return json_encode ($ret);
}

/*

This is a test decrypt of a message encrypted on the command line using openssl.
xxd shows the 0x0b padding bytes at the end of the message.

00000000: 7374 7269 6e67 2831 3629 2022 6865 6c6c  string(16) "hell
00000010: 6f0b 0b0b 0b0b 0b0b 0b0b 0b0b 220a       o...........".

$key = chr(0xCA) . chr(0x97) . chr(0x81) . chr(0x12) . chr(0xCA) . chr(0x1B) . chr(0xBD) . chr(0xCA) . chr(0xFA) . chr(0xC2) . chr(0x31) . chr(0xB3) . chr(0x9A) . chr(0x23) . chr(0xDC) . chr(0x4D);
$iv = [ 0xA7, 0x86, 0xEF, 0xF8, 0x14, 0x7C, 0x4E, 0x72, 0xB9, 0x80, 0x77, 0x85, 0xAF, 0xEE, 0x48, 0xBB];
$data = chr(0x74) . chr(0x55) . chr(0x06) . chr(0xd1) . chr(0xa3) . chr(0x78) . chr(0x7f) . chr(0x98) . chr(0xc7) . chr(0xec) . chr(0x06) . chr(0x47) . chr(0x64) . chr(0xad) . chr(0x94) . chr(0xad);
$d = decrypt ($data, $iv);
var_dump ($d);
exit;

*/

/*

This is a test decrypt of a message encrypted using the encrypt function.
xxd shows the 0x07 padding bytes at the end of the message.

00000000: 7374 7269 6e67 2831 3629 2022 6120 6d65  string(16) "a me
00000010: 7373 6167 6507 0707 0707 0707 220a       ssage.......".

$key = "my key 16 bytes.";
$iv = [1,2,3,4,5,6,7,8,1,2,3,4,5,6,7,8];
$clear_text = "a message";
$e = encrypt ($clear_text, $iv);
$d = decrypt ($e, $iv);
var_dump ($d);
exit;

*/

$init_iv = [1,2,3,4,5,6,7,8,1,2,3,4,5,6,7,8];
$clear = "hello";
$clear = "hello world";
$clear = "u:123 l:0";
print "Clear text: " . $clear . "\n";
print "Encryption key: " . KEY . "\n";
print "Encryption IV: " . byte_array_to_string ($init_iv) . "\n";

$e = encrypt ($clear, $init_iv);
print "\n";

print "Trying to decrypt\n";

$iv = zero_array(16);
$zeroing = zero_array(16);

for ($padding = 1; $padding <= 16; $padding++) {
	$offset = 16 - $padding;
	print ("Looking at offset $offset for padding $padding\n");
	for ($i = 0; $i <= 0xff; $i++) {
		$iv[$offset] = $i;
		for ($k = $offset + 1; $k < 16; $k++) {
			$iv[$k] = $zeroing[$k] ^ $padding;
		}
		try {
			# print "IV: " . bin2hex($iv) . "\n";
			$d = check_token ($e, $iv);

			# Only get here if the decrypt works correctly

			/*

			Check for edge case on offset 15 (right most byte).
			The decrypted data could look like this:

			0x44 ... 0x02 0x02
						  ^^^^ Real last byte of value 2 byte padding

			In this situation, if we happen to land on a value 
			that sets the last byte to 0x02 then that will
			look like valid padding as it will make the data end
			in 0x02 0x02 as it already does:

			0x44 ... 0x02 0x02
						  ^^^^ Fluke, we want 0x01 for 1 byte padding

			This is what we want:

			0x44 ... 0x02 0x01
						  ^^^^ Valid 1 byte padding

			To do this, change the IV value for offset 14 which will
			change the second to last byte and make the call again.
			If we were in the edge case we would now have:

			0x44 ... 0xf3 0x02
						  ^^^^ No longer valid padding

			This is no longer valid padding so it will fail and we can 
			continue looking till we find the value that gives us
			valid 1 byte padding.

			*/

			if ($offset == 15) {
				print "Got a valid decrypt for offset 15, checking edge case\n";
				$temp_iv = $iv;
				$temp_iv[14] = 0xff;
				$temp_d = check_token ($e, $temp_iv);
				print "Not edge case, can continue\n";
			}

			print "There was a match\n";
			$zeroing[$offset] = $i ^ $padding; 
			# print "IV: " . byte_array_to_string ($iv) . "\n";
			# print "Zero: " . byte_array_to_string ($zeroing) . "\n";
			break (1);
		} catch(Exception $exp) {
			# print "Fail\n";
			# var_dump ($e);
		}
	}
}

print "\n";
print "Derived IV is: " . byte_array_to_string ($iv) . "\n";

# If you want to check this, it should be all 16 to show it is all padding
#$x = xor_byte_array ($iv, $zeroing);
#print "Derived IV XOR and zeroing string: " . byte_array_to_string ($x) . "\n";

print "Real IV is: " . byte_array_to_string ($init_iv) . "\n";
print "Zeroing array is: " . byte_array_to_string ($zeroing) . "\n";

$x = xor_byte_array ($init_iv, $zeroing);
print "Decrypted string with padding: " . byte_array_to_string ($x) . "\n";
$number_of_padding_bytes = $x[15];
$without_padding = array_slice ($x, 0, 16 - $number_of_padding_bytes);
print "\n";
print "Decrypted string dump: " . byte_array_to_string ($without_padding) . "\n";

$str = '';
for ($i = 0; $i < count ($without_padding); $i++) {
	$c = $without_padding[$i];
	if ($c > 0x19 && $c < 0x7f) {
		$str .= chr($c);
	} else {
		$str .= "0x" . sprintf ("%02x", $c) . " ";
	}
}

print "Decrypted string as text: " . $str . "\n";
print "\n";

/*
Trying to modify decrypted data by playing with the zeroing array.
*/

print "\n";
print "Trying to modify string\n";

$new_clear = "u:1 l:1";
print "New clear text: " . $new_clear . "\n";

for ($i = 0; $i < strlen($new_clear); $i++) {
	$zeroing[$i] = $zeroing[$i] ^ ord($new_clear[$i]);
}
$padding = 16 - strlen($new_clear);
$offset = 16 - $padding;
for ($i = $offset; $i < 16; $i++) {
	$zeroing[$i] = $zeroing[$i] ^ $padding;
}

print "New IV is: " . byte_array_to_string ($zeroing) . "\n";

print "Sending new data to server\n";

$result = check_token ($e, $zeroing);
print $result . "\n";

if (strpos ($result, "admin") === false) {
	print "Hack failed\n";
} else {
	print "Hack success!\n";
}
