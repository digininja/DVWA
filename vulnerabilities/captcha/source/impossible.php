<?php

if( isset( $_POST[ 'Change' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	// Hide the CAPTCHA form
	$hide_form = true;

	// Get input
	$pass_new  = $_POST[ 'password_new' ];
	$pass_new  = stripslashes( $pass_new );
	$pass_new  = mysql_real_escape_string( $pass_new );
	$pass_new  = md5( $pass_new );

	$pass_conf = $_POST[ 'password_conf' ];
	$pass_conf = stripslashes( $pass_conf );
	$pass_conf = mysql_real_escape_string( $pass_conf );
	$pass_conf = md5( $pass_conf );

	$pass_curr = $_POST[ 'password_current' ];
	$pass_curr = stripslashes( $pass_curr );
	$pass_curr = mysql_real_escape_string( $pass_curr );
	$pass_curr = md5( $pass_curr );

	// Check CAPTCHA from 3rd party
	$resp = recaptcha_check_answer( $_DVWA[ 'recaptcha_private_key' ],
		$_SERVER[ 'REMOTE_ADDR' ],
		$_POST[ 'recaptcha_challenge_field' ],
		$_POST[ 'recaptcha_response_field' ] );

	// Did the CAPTCHA fail?
	if( !$resp->is_valid ) {
		// What happens when the CAPTCHA was entered incorrectly
		$html .= "<pre><br />The CAPTCHA was incorrect. Please try again.</pre>";
		$hide_form = false;
		return;
	}
	else {
		// Check that the current password is correct
		$query  = "SELECT password FROM `users` WHERE user = '" . dvwaCurrentUser() . "' AND password = '$pass_curr';";
		$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

		// Do both new password match and was the current password correct?
		if( ( $pass_new == $pass_conf) && ( $result && mysql_num_rows( $result ) == 1 ) ) {
			// Update the database
			$insert = "UPDATE `users` SET password = '$pass_new' WHERE user = '" . dvwaCurrentUser() . "';";
			$result = mysql_query( $insert ) or die( '<pre>' . mysql_error() . '</pre>' );

			// Feedback for the end user - success!
			$html .= "<pre>Password Changed.</pre>";
		}
		else {
			// Feedback for the end user - failed!
			$html .= "<pre>Either your current password is incorrect or the new passwords did not match. Please try again.</pre>";
			$hide_form = false;
		}
	}

	mysql_close();
}

// Generate Anti-CSRF token
generateSessionToken();

?>
