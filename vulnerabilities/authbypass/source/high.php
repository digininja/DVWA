<?php
/*

Only the admin user is allowed to access this page.

Have a look at this file for possible vulnerabilities: 

* vulnerabilities/authbypass/change_user_details.php

*/

if (dvwaCurrentUser() != "admin") {
	print "Unauthorised";
	http_response_code(403);
	exit;
}
?>
