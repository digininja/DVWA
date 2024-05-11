<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$cookie_value = sha1(mt_rand() . time() . "Impossible");
	$domain = ($_SERVER['SERVER_NAME'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	setcookie("dvwaSession", $cookie_value, time()+3600, "/vulnerabilities/weak_id/", $domain, true, true);
}
?>
