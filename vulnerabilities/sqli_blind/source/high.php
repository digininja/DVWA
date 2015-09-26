<?php

if( isset( $_COOKIE[ 'id' ] ) ) {
	// Retrieve data
	$id = $_COOKIE[ 'id' ];

	$getid  = "SELECT first_name, last_name FROM users WHERE user_id = '$id'";
	$result = mysql_query( $getid ); // Removed 'or die' to suppres mysql errors

	$num = @mysql_numrows( $result ); // The '@' character suppresses errors
	if( $num > 0 ) {
		$html .= '<pre>User ID exists in the database.</pre>';
	}
	else {
		if( rand( 0, 5 ) == 3 ) {
			sleep( rand( 2, 4 ) );
			header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );
		}
		$html .= '<pre>User ID is MISSING from the database.</pre>';
	}
}

?>
