<?php
$errors = "";
$success = "";
$messages = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
}

$html .= "
<p>
	Click the buttons below to see some info on two classic hacker moves. Both pages are template driven, see if you can abuse this process to have the system use one of your own templates and then use that to read local system files. 
</p>
";

$html .= "

<input type='button' value='Hackers' class='' id='template1_button' data-url='smarty/low.php?template=hackers.tpl' )'=''>

<input type='button' value='Sneakers' class='' id='template2_button' data-url='smarty/low.php?template=sneakers.tpl' )'=''>

<script>

	var template1_button = document.getElementById ('template1_button');
	var template2_button = document.getElementById ('template2_button');

	if (template1_button) {
		template1_button.addEventListener('click', function() {
			var url=template1_button.dataset.url;
			popUp (url, 'ssti', 800, 650);
		});
	}

	if (template2_button) {
		template2_button.addEventListener('click', function() {
			var url=template2_button.dataset.url;
			popUp (url, 'ssti', 800, 650);
		});
	}

</script>
";

?>
