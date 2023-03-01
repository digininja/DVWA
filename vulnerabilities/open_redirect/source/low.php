<?php

if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "") {
	header ("location: " . $_GET['redirect']);
	exit;
}

?>
Missing redirect target.
