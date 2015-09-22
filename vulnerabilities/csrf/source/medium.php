<?php

if( isset( $_GET[ 'Change' ] ) ) {
	// Checks the http referer header
	if( eregi( $_SERVER[ 'SERVER_NAME' ], $_SERVER[ 'HTTP_REFERER' ] ) ) {
		// Turn requests into variables
		$pass_new  = $_GET[ 'password_new' ];
		$pass_conf = $_GET[ 'password_conf' ];

		if( $pass_new == $pass_conf ) {
			$pass_new = mysql_real_escape_string( $pass_new );
			$pass_new = md5( $pass_new );

			$insert = "UPDATE `users` SET password = '$pass_new' WHERE user = '" . dvwaCurrentUser() . "';";
			$result = mysql_query( $insert ) or die( '<pre>' . mysql_error() . '</pre>' );

			$html .= "<pre>Password Changed.</pre>";
		}
		else {
			$html .= "<pre>Passwords did not match.</pre>";
		}
	}
	else {
		$html .= "<pre>That request didn't look correct.</pre>";
	}
}

?>
