<?php

if( isset( $_GET[ 'Login' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	// Sanitise username input
	$user = $_GET[ 'username' ];
	$user = stripslashes( $user );
	$user = mysql_real_escape_string( $user );

	// Sanitise password input
	$pass = $_GET[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = mysql_real_escape_string( $pass );
	$pass = md5( $pass );

	// Default values
	$total_failed_login = '3';
	$lockout_time       = '15';
	$account_locked     = false;

	// Check the database (Check user information)
	$query  = "SELECT failed_login,last_login FROM `users` WHERE user = '$user';";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

	// Check to see if the user has been locked out.
	if( $result && mysql_num_rows( $result ) == 1 && mysql_result( $result, 0, "failed_login" ) >= $total_failed_login ) {
		// User locked out.  Note, using this method would allow for user enumeration!
		//$html .= "<pre><br />This account has been locked due to too many incorrect logins.</pre>";

		// Calculate when the user would be allowed to login again
		$last_login = mysql_result( $result, 0, "last_login" );
		$last_login = strtotime( $last_login );
		$timeout    = strtotime( "${$last_login} +{$lockout_time} minutes" );
		$timenow    = strtotime( "now" );

		// Check to see if enough time has passed
		$account_locked = false;
		if( $timeout < $timenow )
			$account_locked = true;
	}

	// Check the database (if username matches the password)
	$query  = "SELECT * FROM `users` WHERE user = '$user' AND password = '$pass' AND failed_login < {$total_failed_login};";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

	// If its a valid login...
	if( $result && mysql_num_rows( $result ) == 1 && $account_locked == false) {
		// Get users details
		$avatar       = mysql_result( $result, 0, "avatar" );
		$failed_login = mysql_result( $result, 0, "failed_login" );
		$last_login   = mysql_result( $result, 0, "last_login" );

		// Login successful
		$html .= "<p>Welcome to the password protected area <em>{$user}</em></p>";
		$html .= "<img src=\"{$avatar}\" />";

		// Had the account been locked out since last login?
		if( $failed_login >= $total_failed_login ) {
			$html .= "<p><em>Warning</em>: Someone might of been brute forcing your account.</p>";
			$html .= "<p>Number of login attempts: <em>{$failed_login}</em>.<br />Last login attempt was at: <em>${last_login}</em>.</p>";
		}

		// Reset bad login count
		$insert = "UPDATE `users` SET `failed_login` = '0' WHERE user = '$user' LIMIT 1;";
		$result = @mysql_query( $insert );
	}
	else {
		// Login failed
		sleep( rand( 2, 4 ) );

		// Give the user some feedback
		$html .= "<pre><br />Username and/or password incorrect.<br /><br/>Alternative, the account has been locked because of too many failed logins.<br />If this is the case, <em>please try again in {$lockout_time} minutes</em>.</pre>";

		// Update bad login count
		$query  = "UPDATE `users` SET `failed_login` = failed_login + 1 WHERE user = '$user' LIMIT 1;";
		$result = @mysql_query( $query );
	}

	// Set the last login time
	$query  = "UPDATE `users` SET `last_login` = now() WHERE user = '$user' LIMIT 1;";
	$result = @mysql_query( $query );

	mysql_close();
}

// Generate Anti-CSRF token
generateSessionToken();

?>
