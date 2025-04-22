<?php
$errors = "";
$success = "";
$messages = "";

$template_directory = DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/sst/smarty_stuff/templates/";

$html .= "<p>
	The template used by this page brings together information from two other sources, both of which require login credentials to access.
</p>
<p>The setup has been done following the official Smarty Quick Install guide, but a key warning was ignored. Identify the weakness and recover three sets of credentials, basic authentication, FTP, and database.
</p>
";

$html .= "
<p>
	<input type='button' value='Show Page' class='' id='template_button' data-url='source/high_popup.php' )'=''>
</p>
<script>
	var template_button = document.getElementById ('template_button');

	if (template_button) {
		template_button.addEventListener('click', function() {
			var url=template_button.dataset.url;
			popUp (url, 'sst');
		});
	}

</script>
";

?>
