<?php
$errors = "";
$success = "";
$messages = "";

$html .= "<p>
	The impossible level is designed to show off how a system should be used correctly and so this page is fairly simple, it just assigns a few values to variables and then shows them in the template.
</p>
";

$html .= "
<p>
	<input type='button' value='Show Page' class='' id='template_button' data-url='source/impossible_popup.php' )'=''>
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
