<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$id = $_POST[ 'id' ];
	$id = mysql_real_escape_string( $id );

	// Check database
	$getid  = "SELECT first_name, last_name FROM users WHERE user_id = $id;";
	$result = mysql_query( $getid ); // Removed 'or die' to suppress mysql errors

	// Get results
	$num = @mysql_numrows( $result ); // The '@' character suppresses errors
	if( $num > 0 ) {
		// Feedback for end user
		$html .= '<pre>User ID exists in the database.</pre>';
	}
	else {
		// Feedback for end user
		$html .= '<pre>User ID is MISSING from the database.</pre>';
	}

	//mysql_close();
}

?>
