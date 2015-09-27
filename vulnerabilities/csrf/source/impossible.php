<?php

if( isset( $_GET[ 'Change' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	// Get input
	$pass_curr = $_GET[ 'password_current' ];
	$pass_new  = $_GET[ 'password_new' ];
	$pass_conf = $_GET[ 'password_conf' ];

	// Sanitise current password input
	$pass_curr = stripslashes( $pass_curr );
	$pass_curr = mysql_real_escape_string( $pass_curr );
	$pass_curr = md5( $pass_curr );

	// Check that the current password is correct
	$query  = "SELECT password FROM `users` WHERE user = '" . dvwaCurrentUser() . "' AND password = '$pass_curr';";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

	// Do both new passwords match and does the current password match the user?
	if( ( $pass_new == $pass_conf ) && ( $result && mysql_num_rows( $result ) == 1 ) ) {
		// It does!
		$pass_new = mysql_real_escape_string( $pass_new );
		$pass_new = md5( $pass_new );

		// Update database with new password
		$insert = "UPDATE `users` SET password = '$pass_new' WHERE user = '" . dvwaCurrentUser() . "';";
		$result = mysql_query( $insert ) or die( '<pre>' . mysql_error() . '</pre>' );

		// Feedback for the user
		$html .= "<pre>Password Changed.</pre>";
	}
	else {
		// Issue with passwords matching
		$html .= "<pre>Passwords did not match or current password incorrect.</pre>";
	}

	mysql_close();
}

// Generate Anti-CSRF token
generateSessionToken();

?>
