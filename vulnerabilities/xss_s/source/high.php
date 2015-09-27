<?php

if( isset( $_POST[ 'btnSign' ] ) ) {
	// Get input
	$message = trim( $_POST[ 'mtxMessage' ] );
	$name    = trim( $_POST[ 'txtName' ] );

	// Sanitize message input
	$message = strip_tags( addslashes( $message ) );
	$message = mysql_real_escape_string( $message );
	$message = htmlspecialchars( $message );

	// Sanitize name input
	$name = preg_replace( '/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t/i', '', $name );
	$name = mysql_real_escape_string( $name );

	// Update database
	$query  = "INSERT INTO guestbook ( comment, name ) VALUES ( '$message', '$name' );";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

	//mysql_close();
}

?>
