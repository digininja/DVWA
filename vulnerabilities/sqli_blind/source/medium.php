<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$id = $_POST[ 'id' ];
	$id = mysqli_real_escape_string($con, $id );

	// Check database
	$getid  = "SELECT first_name, last_name FROM users WHERE user_id = $id;";
	$result = mysqli_query($con, $getid ); // Removed 'or die' to suppress mysqli errors

	// Get results
	$num = @mysqli_num_rows( $result ); // The '@' character suppresses errors
	if( $num > 0 ) {
		// Feedback for end user
		$html .= '<pre>User ID exists in the database.</pre>';
	}
	else {
		// Feedback for end user
		$html .= '<pre>User ID is MISSING from the database.</pre>';
	}

	//mysqli_close();
}

?>
