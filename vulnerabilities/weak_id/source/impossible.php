<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$cookie_value = sha1(mt_rand() . time() . "Impossible");
	setcookie("dvwaSession", $cookie_value);
}
?>
