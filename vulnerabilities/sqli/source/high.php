<?php

if( isset( $_SESSION [ 'id' ] ) ) {
	// Retrieve data
	$id = $_SESSION[ 'id' ];

	$query  = "SELECT first_name, last_name FROM users WHERE user_id = '$id' LIMIT 1;";
	$result = mysql_query( $query ) or die( '<pre>Something went wrong.</pre>' );

	sleep ( rand( 0, 2 ) );

	$num = mysql_numrows( $result );
	$i   = 0;
	while( $i < $num ) {
		$first = mysql_result( $result, $i, "first_name" );
		$last  = mysql_result( $result, $i, "last_name" );

		$html .= "<pre>";
		$html .= "ID: {$id}<br />First name: {$first}<br />Surname: {$last}";
		$html .= "</pre>";

		$i++;
	}
}

?>
