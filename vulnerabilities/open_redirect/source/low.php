<?php

if (!empty($_GET['redirect'])) {
	header ("location: " . $_GET['redirect']);
	exit;
}

http_response_code (500);
echo "Missing redirect target.";
exit;
?>
