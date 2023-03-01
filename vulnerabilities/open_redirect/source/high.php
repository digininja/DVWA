<?php

if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "") {
	if (strpos($_GET['redirect'], "info.php") !== false) {
		header ("location: " . $_GET['redirect']);
		exit;
	} else {
		?>
		You can only redirect to the info page.
		<?php
		exit;
	}
}

?>
Missing redirect target.
