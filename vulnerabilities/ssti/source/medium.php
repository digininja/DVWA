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

<input type='button' value='Template One' class='' id='template1_button' data-url='smarty/medium.php' )'=''>

<script>

	var template1_button = document.getElementById ('template1_button');

	if (template1_button) {
		template1_button.addEventListener('click', function() {
			var url=template1_button.dataset.url;
			popUp (url);
		});
	}

</script>
";

?>
