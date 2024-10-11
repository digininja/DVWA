<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	try {
		if (array_key_exists ('message', $_POST)) {
			$message = $_POST['message'];
			if (array_key_exists ('direction', $_POST) && $_POST['direction'] == "decode") {
				$encoded = xor_this (base64_decode ($message), $key);
				$encode_radio_selected = " ";
				$decode_radio_selected = " checked='checked' ";
			} else {
				$encoded = base64_encode(xor_this ($message, $key));
			}
		}
		if (array_key_exists ('password', $_POST)) {
			$password = $_POST['password'];
			$decoded = xor_this (base64_decode ($password), $key);
			if ($password == "Olifant") {
				$success = "Welcome back user";
			} else {
				$errors = "Login Failed";
			}
		}
	} catch(Exception $e) {
		$errors = $e->getMessage();
	}
}

$html = "
	<script>
		function update_username(user_json) {
			console.log(user_json);
			var user_info = document.getElementById ('user_info');
			var name_input = document.getElementById ('name');

			if (user_json.name == '') {
				user_info.innerHTML = 'User details: unknown user';
				name_input.value = 'unknown';
			} else {
				if (user_json.level == 0) {
					level = 'admin';
				} else {
					level = 'user';
				}
				user_info.innerHTML = 'User details: ' + user_json.name + ' (' + level + ')';
				name_input.value = user_json.name;
			}

			const message_line = document.getElementById ('message');
			if (user_json.id == 2 && user_json.level == 0) {
				message_line.style.display = 'block';
			} else {
				message_line.style.display = 'none';
			}
		}

		function get_user() {
			const url = '/vulnerabilities/api/user/3';
			 
			fetch(url, { 
					method: 'GET',
				}) 
				.then(response => { 
					if (!response.ok) { 
						throw new Error('Network response was not ok'); 
				} 
				return response.json(); 
				}) 
				.then(data => { 
					update_username (data);
				}) 
				.catch(error => { 
					console.error('There was a problem with your fetch operation:', error); 
			}); 

		}
		function update_name() {
			const url = '/vulnerabilities/api/user/2';
			const name = document.getElementById ('name').value;
			const data = JSON.stringify({name: name});
			 
			fetch(url, { 
					method: 'PUT', 
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
					update_username(data);
				}) 
				.catch(error => { 
					console.error('There was a problem with your fetch operation:', error); 
			}); 

		}
	</script>
";

$html .= "
		<p>
			Look at the call used to update your name and exploit it to elevate your user to level 0, admin.
		</p>
		<div class='success' style='display:none' id='message'>Well done, you elevated your user to admin.</div>
		<p id='user_info'></p>
		<form method='post' action=\"" . $_SERVER['PHP_SELF'] . "\">
			<p>
				<label for='name'>Name</label>
				<input type='text' value='' name='name' id='name'>
			</p>
			<p>
				<input type=\"button\" value=\"Submit\" onclick='update_name();'>
			</p>
		</form>
		<script>
			get_user();
		</script>
";

?>
