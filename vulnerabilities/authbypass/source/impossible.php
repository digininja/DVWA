<?php
/*

Only the admin user is allowed to access this page

*/

if (dvwaCurrentUser() != "admin") {
	print "Unauthorised";
	http_response_code(403);
	exit;
}
?>
