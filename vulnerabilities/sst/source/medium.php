<?php
$errors = "";
$success = "";
$messages = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
}

$html .= "
<p>
	Below is a system which allows you to create a template to show off a user's profile. The template has full access to the user object.
</p>
<p>
You can use the following fields in your template:
</p>

<ul>
	<li>first_name</li>
	<li>last_name</li>
	<li>user</li>
	<li>avatar</li>
	<li>role</li>
</ul>
<p>If you need to debug your template, you can enable the <a href='https://www.smarty.net/docsv2/en/chapter.debugging.console.tpl'>Debugging Console</a>.</p>
";

$html .= "
<textarea style='width:100%;height:120px' id='template' name='template'><h1>User Profile</h1>
<p>Hello {\$first_name} {\$last_name}</p>

<p><img src='{\$avatar}'></p></textarea>

<p>
<label for='user_id'>User ID to show:</label><input type='text' value='2' id='user_id' name='user_id'>

<input type='button' value='Use Template' class='' id='template_button' data-url='source/medium_popup.php' )'=''>
</p>
<script>
	var template_button = document.getElementById ('template_button');

	if (template_button) {
		template_button.addEventListener('click', function() {
			var template_string = document.getElementById ('template').value;
			var user_id = document.getElementById ('user_id').value;
			var url=template_button.dataset.url;
			popUp (url+'?user_id=' + user_id + '&template='+btoa(template_string), 'sst');
		});
	}

</script>
";

?>
