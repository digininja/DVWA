<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$id = $_POST[ 'id' ];
	$id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $id ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	// Check database
	$getid  = "SELECT first_name, last_name FROM users WHERE user_id = $id;";
	$result = mysqli_query($GLOBALS["___mysqli_ston"],  $getid ); // Removed 'or die' to suppress mysql errors

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

	//mysql_close();
}

?>
