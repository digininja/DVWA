<?php

if( isset( $_POST[ 'Submit' ] ) ) {
	// Get input
	$id = $_POST[ 'id' ];
	$id = mysqli_real_escape_string($con, $id );

	// Check database
	$query  = "SELECT first_name, last_name FROM users WHERE user_id = $id;";
	$result = mysqli_query($con, $query ) or die( '<pre>' . mysqli_error($con) . '</pre>' );

	// Get results
	$num = mysqli_num_rows($result );
	$i   = 0;
	while( $i < $num ) {
		// Display values
		$first = mysqli_result( $result, $i, "first_name" );
		$last  = mysqli_result( $result, $i, "last_name" );

		// Feedback for end user
		$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";

		// Increase loop count
		$i++;
	}

	//mysqli_close();
}

?>
