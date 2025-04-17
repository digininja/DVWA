<?php
$errors = "";
$success = "";
$messages = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
}

$html .= "
<p>
	Click the buttons below to open two new pages which are generated from templates. See if you can abuse this process to have the system use one of your own templates and then use that to read local system files. 
</p>
";

$html .= "

<input type='button' value='Template One' class='' id='template1_button' data-url='smarty/low.php?template=template_one.tpl' )'=''>

<input type='button' value='Template Two' class='' id='template2_button' data-url='smarty/low.php?template=template_two.tpl' )'=''>

<script>

	var template1_button = document.getElementById ('template1_button');
	var template2_button = document.getElementById ('template2_button');

	if (template1_button) {
		template1_button.addEventListener('click', function() {
			var url=template1_button.dataset.url;
			popUp (url);
		});
	}

	if (template2_button) {
		template2_button.addEventListener('click', function() {
			var url=template2_button.dataset.url;
			popUp (url);
		});
	}

</script>
";

?>
