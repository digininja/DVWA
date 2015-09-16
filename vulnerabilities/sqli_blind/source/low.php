<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	// Retrieve data
	$id = $_GET[ 'id' ];

	$getid  = "SELECT first_name, last_name FROM users WHERE user_id = '$id'";
	$result = mysql_query( $getid ); // Removed 'or die' to suppres mysql errors

	$num = @mysql_numrows( $result ); // The '@' character suppresses errors making the injection 'blind'
	if( $num > 0 ) {
		$html .= '<pre>User ID exists in the database.</pre>';
	}
	else {
		header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );
		$html .= '<pre>User ID is MISSING from the database.</pre>';
	}
}

?>
