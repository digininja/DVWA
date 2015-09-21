<?php

if( isset( $_GET[ 'Login' ] ) ) {
	$user = $_GET[ 'username' ];

	$pass = $_GET[ 'password' ];
	$pass = md5( $pass );

	$query  = "SELECT * FROM `users` WHERE user='$user' AND password='$pass';";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

	if( $result && mysql_num_rows( $result ) == 1 ) {
		// Get users details
		$avatar = mysql_result( $result, 0, "avatar" );

		// Login Successful
		$html .= "<p>Welcome to the password protected area {$user}</p>";
		$html .= "<img src=\"{$avatar}\" />";
	}
	else {
		// Login failed
		$html .= "<pre><br />Username and/or password incorrect.</pre>";
	}
}

?>
