<?php

if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "") {
	if (preg_match ("/http:\/\/|https:\/\//i", $_GET['redirect'])) {
		?>
		Absolute URLs not allowed.
		<?php
		exit;
	} else {
		header ("location: " . $_GET['redirect']);
		exit;
	}
}

?>
Missing redirect target.
