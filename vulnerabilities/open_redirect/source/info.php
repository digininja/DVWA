<?php

$info = "boring";

if (array_key_exists ("id", $_GET) && is_numeric($_GET['id'])) {
	switch (intval ($_GET['id'])) {
		case 1:
			$info = "info 1";
			break;
		case 2:
			$info = "info 2";
			break;
		default:
			$info = "Some other stuff";
	}
}

echo $info;

?>
