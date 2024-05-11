<?php

if (!empty($_GET['redirect'])) {
	if (preg_match ("/http:\/\/|https:\/\//i", $_GET['redirect'])) {
		http_response_code (500);
		echo "Absolute URLs not allowed.";
		exit;
	} else {
		header ("location: " . $_GET['redirect']);
		exit;
	}
}

http_response_code (500);
echo "Missing redirect target.";
exit;
?>
