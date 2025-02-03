<?php

$html .= "
	<script>
		function update_username(user_json) {
			console.log(user_json);
			var user_info = document.getElementById ('user_info');
			var name_input = document.getElementById ('name');

			if (user_json.name == '') {
				user_info.innerHTML = 'User details: unknown user';
				name_input.value = 'unknown';
			} else {
				var level = 'unknown';
				if (user_json.level == 0) {
					level = 'admin';
					successDiv = document.getElementById ('message');
					successDiv.style.display = 'block';
				} else {
					level = 'user';
				}
				user_info.innerHTML = 'User details: ' + user_json.name + ' (' + level + ')';
				name_input.value = user_json.name;
			}
		}

		function get_user() {
			const url = '/vulnerabilities/api/v2/user/2';
			 
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
			const url = '/vulnerabilities/api/v2/user/2';
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
			Look at the call used to update your name and exploit it to elevate your user to admin (level 0).
		</p>
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
		<div class='success' style='display:none' id='message'>Well done, you elevated your user to admin.</div>
		<script>
			get_user();
		</script>
";

?>
