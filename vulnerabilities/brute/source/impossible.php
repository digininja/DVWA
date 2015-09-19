<?php

if( isset( $_GET[ 'Login' ] ) ) {
	// Anti-CSRF
	checkTokens( $_REQUEST[ 'user_token' ], 'index.php' );

	// Sanitise username input
	$user = $_GET[ 'username' ];
	$user = stripslashes( $user );
	$user = mysql_real_escape_string( $user );

	// Sanitise password input
	$pass = $_GET[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = mysql_real_escape_string( $pass );
	$pass = md5( $pass );

	$total_failed_login = '3';
	$lockout_time       = '15';
	$account_locked     = false;

	$query  = "UPDATE `users` SET `last_login` = now() WHERE user='$user' LIMIT 1;";
	$result = @mysql_query( $query );

	$query  = "SELECT failed_login,last_login FROM `users` WHERE user='$user' LIMIT 1;";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

	if( $result && mysql_num_rows( $result ) == 1 && mysql_result( $result, 0, "failed_login" ) >= $total_failed_login ) {
		// User locked out.  Note, this method will allow for user enumeration!
		// $html .= "<pre><br />This account has been locked due to too many incorrect logins.</pre>";

		$last_login    = strtotime( mysql_result( $result, 0, "last_login" ) );
		$timeout_login = strtotime( "{$last_login} +{$lockout_time} minutes" );
		$timenow       = strtotime( $last_login );

		if( $timeout_login < $timenow )
			$account_locked = true;
		else
			$account_locked = false;
	}

	$query  = "SELECT * FROM `users` WHERE user='$user' AND password='$pass' LIMIT 1;";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

	if( $result && mysql_num_rows( $result ) == 1 && $account_locked == false) {
		// Get users details
		$avatar = mysql_result( $result, 0, "avatar" );

		// Login Successful
		$html .= "<p>Welcome to the password protected area {$user}</p>";
		$html .= '<img src="' . $avatar . '" />';

		// Reset bad login count
		$insert = "UPDATE `users` SET `failed_login` = '0' WHERE user='$user' LIMIT 1;";
		$result = @mysql_query( $insert );
	}
	else {
		// Login failed
		sleep(rand( 2, 4 ) );

		$html .= "<pre><br />Username and/or password incorrect.<br /><br/>Alternative, the account has been locked because of too many failed logins.<br />If this is the case, please try again in {$lockout_time} minutes.</pre>";

		$query  = "UPDATE `users` SET `failed_login` = failed_login + 1 WHERE user='$user' LIMIT 1;";
		$result = @mysql_query( $query );
	}
}

?>
