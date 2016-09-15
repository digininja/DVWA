<?php

if( isset( $_SESSION [ 'id' ] ) ) {
	// Get input
	$id = $_SESSION[ 'id' ];

	// Check database
	$query  = "SELECT first_name, last_name FROM users WHERE user_id = '$id' LIMIT 1;";
	$result = mysqli_query($con, $query ) or die( '<pre>Something went wrong.</pre>' );

	// Get results
	$num = mysqli_num_rows($result );
	$i   = 0;
	while( $i < $num ) {
		// Get values
		$first = mysqli_result( $result, $i, "first_name" );
		$last  = mysqli_result( $result, $i, "last_name" );

		// Feedback for end user
		$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";

		// Increase loop count
		$i++;
	}

	mysqli_close($con);
}

?>
