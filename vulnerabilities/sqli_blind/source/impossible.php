<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );
	$exists = false;

	// Get input
	$id = $_GET[ 'id' ];

	// Was a number entered?
	if(is_numeric( $id )) {
		$id = intval ($id);
		switch ($_DVWA['SQLI_DB']) {
			case MYSQL:
				// Check the database
				$data = $db->prepare( 'SELECT first_name, last_name FROM users WHERE user_id = (:id) LIMIT 1;' );
				$data->bindParam( ':id', $id, PDO::PARAM_INT );
				$data->execute();

				$exists = $data->rowCount();
				break;
			case SQLITE:
				global $sqlite_db_connection;

				$stmt = $sqlite_db_connection->prepare('SELECT COUNT(first_name) AS numrows FROM users WHERE user_id = :id LIMIT 1;' );
				$stmt->bindValue(':id',$id,SQLITE3_INTEGER);
				$result = $stmt->execute();
				$result->finalize();
				if ($result !== false) {
					// There is no way to get the number of rows returned
					// This checks the number of columns (not rows) just
					// as a precaution, but it won't stop someone dumping
					// multiple rows and viewing them one at a time.

					$num_columns = $result->numColumns();
					if ($num_columns == 1) {
						$row = $result->fetchArray();

						$numrows = $row[ 'numrows' ];
						$exists = ($numrows == 1);
					}
				}
				break;
		}

	}

	// Get results
	if ($exists) {
		// Feedback for end user
		$html .= '<pre>User ID exists in the database.</pre>';
	} else {
		// User wasn't found, so the page wasn't!
		header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );

		// Feedback for end user
		$html .= '<pre>User ID is MISSING from the database.</pre>';
	}
}

// Generate Anti-CSRF token
generateSessionToken();

?>
