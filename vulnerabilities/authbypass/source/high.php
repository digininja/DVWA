<?php
/*
Only the admin user is allowed to access this page.

Make sure to remember to add checks to get and update data.

*/

if (dvwaCurrentUser() != "admin") {
	print "Unauthorised";
	http_response_code(403);
	exit;
}
?>
