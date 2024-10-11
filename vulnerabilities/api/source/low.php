<?php
$errors = "";
$success = "";
$messages = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
}

$html = "
		<p>
		Versioning is important in APIs, running multiple versions of an API can allow for backward compatibility and can allow new services to be added without affecting existing users. The downside to keeping old versions alive though is when those older versions contain vulnerabilities.
		</p>
";

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

		function get_users() {
			const url = '/vulnerabilities/api/v2/user/';
			 
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
					// data.forEach(loadTableData);
					loadTableData(data);
				}) 
				.catch(error => { 
					console.error('There was a problem with your fetch operation:', error); 
			}); 


		}

  function loadTableData(items) {
    const table = document.getElementById('testBody');
    items.forEach( item => {
      let row = table.insertRow();
      let date = row.insertCell(0);
      date.innerHTML = item.id;
      let name = row.insertCell(1);
      name.innerHTML = item.name;
      let level = row.insertCell(2);
	  if (item.level == 0) {
	  	level_name = 'admin';
	} else {
		level_name = 'user';
	}
      level.innerHTML = level_name;
    });
  }
	</script>
";

$html .= "

<table id='myTable' class=''>
  <thead>
    <tr>
      <th>id</th>
      <th>name</th>
      <th>level</th>
    </tr>
  </thead>
  <tbody id='testBody'></tbody>
</table>


		<p>
			Look at the call used to update your name and exploit it to elevate your user to level 0, admin.
		</p>
		<div class='success' style='display:none' id='message'>Well done, you elevated your user to admin.</div>
		<script>
			get_users();
		</script>
";

?>
