<?php

if (!empty($_GET['redirect'])) {
	if (strpos($_GET['redirect'], "info.php") !== false) {
		header ("location: " . $_GET['redirect']);
		exit;
	} else {
		echo "You can only redirect to the info page.";
		exit;
	}
}

http_response_code (500);
echo "Missing redirect target.";
exit;
?>
