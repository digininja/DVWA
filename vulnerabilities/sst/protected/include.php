<?php
function send_basic_auth_headers() {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Sorry, only admins allowed in here.';
    exit;
}

function check_login() {
	if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
		send_basic_auth_headers();
	} else {
		$user = $_SERVER['PHP_AUTH_USER'];
		$password = $_SERVER['PHP_AUTH_PW'];

		if ($user == "admin" && $password = "secret") {
			# ok
		} else {
			send_basic_auth_headers();
		}
	}
}
?>
