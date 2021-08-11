<?php

if( isset( $_REQUEST[ 'Submit' ] ) ) {
	// Get input
	$id = $_REQUEST[ 'id' ];

	define ("MYSQL", "mysql");
	define ("SQLITE", "sqlite");

	define ("SQLI_DB", SQLITE);
	//define ("SQLI_DB", MYSQL);

	switch (SQLI_DB) {
		case MYSQL:
			// Check database
			$query  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
			$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

			// Get results
			while( $row = mysqli_fetch_assoc( $result ) ) {
				// Get values
				$first = $row["first_name"];
				$last  = $row["last_name"];

				// Feedback for end user
				$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
			}

			mysqli_close($GLOBALS["___mysqli_ston"]);
			break;
		case SQLITE:
			$sqlite_db = "/var/sites/dvwa/non-secure/htdocs/vulnerabilities/sqli/source/sqli.db";
			$sqlite_db_connection = new SQLite3($sqlite_db);
			$query  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
			$results = $sqlite_db_connection->query($query);
			while ($row = $results->fetchArray()) {
				// Get values
				$first = $row["first_name"];
				$last  = $row["last_name"];

				// Feedback for end user
				$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
			}
			break;
	} 
}

?>
