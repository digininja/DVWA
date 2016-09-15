<?php

if( isset( $_POST[ 'btnSign' ] ) ) {
	// Get input
	$message = trim( $_POST[ 'mtxMessage' ] );
	$name    = trim( $_POST[ 'txtName' ] );

	// Sanitize message input
	$message = stripslashes( $message );
	$message = mysqli_real_escape_string($con, $message );

	// Sanitize name input
	$name = mysqli_real_escape_string($con, $name );

	// Update database
	$query  = "INSERT INTO guestbook ( comment, name ) VALUES ( '$message', '$name' );";
	$result = mysqli_query($con, $query ) or die( '<pre>' . mysqli_error($con) . '</pre>' );

	//mysqli_close();
}

?>
