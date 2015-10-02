<?php

if( isset( $_REQUEST[ 'Submit' ] ) ) {
	// Get input
	$id = $_REQUEST[ 'id' ];

	// Check database
	$query  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

	// Get results
	$num = mysql_numrows( $result );
	$i   = 0;
	while( $i < $num ) {
		// Get values
		$first = mysql_result( $result, $i, "first_name" );
		$last  = mysql_result( $result, $i, "last_name" );

		// Feedback for end user
		$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";

		// Increase loop count
		$i++;
	}

	mysql_close();
}

?>
