<?php

if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "") {
	header ("location: " . $_GET['redirect']);
	exit;
}

http_response_code (500);
?>
<p>Missing redirect target.</p>
<?php
exit;
?>
