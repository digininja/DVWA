<?php

require '../bootstrap.php';
use Src\DVWAController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

# As DVWA can be installed in any directory, this works out where
# the API is so we know the base to start from.

$local_uri = array();
foreach ($uri as $pos => $dir) {
	if ($dir == "dvwaapi") {
		$local_uri = array_slice ($uri, $pos);
	}
}

// all of our endpoints start with /dvwaapi
// everything else results in a 404 Not Found

if ($local_uri[0] !== 'dvwaapi') {
	header("HTTP/1.1 404 Not Found");
	exit();
}

// the user id is, of course, optional and must be a number:
$userId = null;
if (isset($local_uri[1])) {
	$userId = (int) $local_uri[1];
}
$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and user ID to the DVWAController and process the HTTP request:
$controller = new DVWAController($requestMethod, $userId);
$controller->processRequest();
