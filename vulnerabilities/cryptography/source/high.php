<?php

define ("KEY", "rainbowclimbing.");

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

function create_token () {
	$token = "userid:2";

	$iv_string = "1234567812345678";

	$e = encrypt ($token, $iv_string);
	$data = array (
					"token" => base64_encode ($e),
					"iv" => base64_encode ($iv_string)
				);
	return json_encode($data);
}

$message = "";

$token_data = create_token();

$html = "
	<script>
		function send_token() {

			const url = 'source/check_token.php';
			const data = document.getElementById ('token').value;

			console.log (data);
			 
			fetch(url, { 
					method: 'POST', 
					headers: { 
						'Content-Type': 'application/json' 
					}, 
					body: data
				}) 
				.then(response => { 
					if (!response.ok) { 
						throw new Error('Network response was not ok'); 
				} 
				return response.json(); 
				}) 
				.then(data => { 
					console.log(data);
					message_line = document.getElementById ('message');
					if (data.status == 200) {
						message_line.innerText = 'Welcome back ' + data.user + ' (' + data.level + ')';
						message_line.setAttribute('class', 'success');
					} else {
						message_line.innerText = 'Error: ' + data.message;
						message_line.setAttribute('class', 'warning');
					}
				}) 
				.catch(error => { 
					console.error('There was a problem with your fetch operation:', error); 
			}); 

		}
	</script>
		<p>
			You have managed to steal the following token from a user of the Prognostication application.
		</p>
		<p>
			<textarea style='width: 600px; height: 18px'>" . htmlentities ($token_data) . "</textarea>
		</p>
		<p>
			You can use the form below to provide the token to access the system. You have two challenges, first, decrypt the token to find out the secret it contains, and then create a new token to access the system as a other users. See if you can make yourself an administrator.
		</p>
		<hr>
		<form name=\"check_token\" action=\"\">
			<div id='message'></div>
			<p>
				<label for='token'>Token:</lable><br />
				<textarea id='token' name='token' style='width: 600px; height: 18px'>" . htmlentities ($token_data) . "</textarea>
			</p>
			<p>
				<input type=\"button\" value=\"Submit\" onclick='send_token();'>
			</p>
		</form>
";

?>
