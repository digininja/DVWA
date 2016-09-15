<?php

if( isset( $_POST[ 'btnSign' ] ) ) {
	// Get input
	$message = trim( $_POST[ 'mtxMessage' ] );
	$name    = trim( $_POST[ 'txtName' ] );

	// Sanitize message input
	$message = strip_tags( addslashes( $message ) );
	$message = mysqli_real_escape_string($con, $message );
	$message = htmlspecialchars( $message );

	// Sanitize name input
	$name = str_replace( '<script>', '', $name );
	$name = mysqli_real_escape_string($con, $name );

	// Update database
	$query  = "INSERT INTO guestbook ( comment, name ) VALUES ( '$message', '$name' );";
	$result = mysqli_query($con, $query ) or die( '<pre>' . mysqli_error($con) . '</pre>' );

	//mysqli_close();
}

?>
