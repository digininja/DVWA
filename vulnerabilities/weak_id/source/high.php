<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (!isset ($_SESSION['last_session_id_high'])) {
		$_SESSION['last_session_id_high'] = 0;
	}
	$_SESSION['last_session_id_high']++;
	$cookie_value = md5($_SESSION['last_session_id_high']);
	$domain = ($_SERVER['SERVER_NAME'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	setcookie("dvwaSession", $cookie_value, time()+3600, "/vulnerabilities/weak_id/", $domain, false, false);
}

?>
