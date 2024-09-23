<?php

require_once ("token_library_impossible.php");

$ret = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ($_SERVER['CONTENT_TYPE'] != "application/json") {
		$ret = json_encode (array (
						"status" => 527,
						"message" => "Content type must be application/json"
					));
	} else {
		$token = $jsonData = file_get_contents('php://input');
		$ret = check_token ($token);
	}
} else {
	$ret = json_encode (array (
					"status" => 405,
					"message" => "Method not supported"
				));
}

print $ret;
exit;
