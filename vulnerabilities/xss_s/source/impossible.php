<?php

if( isset( $_POST[ 'btnSign' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	$message = trim( $_POST[ 'mtxMessage' ] );
	$name    = trim( $_POST[ 'txtName' ] );

	// Sanitize message input
	$message = stripslashes( $message );
	$message = mysql_real_escape_string( $message );
	$message = htmlspecialchars( $message );

	// Sanitize name input
	$name = stripslashes( $name );
	$name = mysql_real_escape_string( $name );
	$name = htmlspecialchars( $name );

	$query  = "INSERT INTO guestbook ( comment, name ) VALUES ( '$message', '$name' );";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );
}

// Generate Anti-CSRF token
generateSessionToken();

?>
