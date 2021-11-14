<?php
# Possible idea - XSS through jQuery append user content into the page

$name="";

if (array_key_exists ("name", $_GET)) {
	$name = $_GET['name'];
}
$page[ 'body' ] .= <<<EOF
<form method="GET">
	<label for="name">Name:</label>
	<input type="text" id="name" name="name" value="{$name}" />

	<input type="submit" value="Submit" />
</form>
EOF;
?>
