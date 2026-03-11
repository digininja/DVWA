<?php

if( isset( $_REQUEST[ 'Submit' ] ) ) {
	// Get input
	$id = $_REQUEST[ 'id' ];

	if(is_numeric( $id )) {
		$id = intval( $id );

		switch ($_DVWA['SQLI_DB']) {
			case MYSQL:
				// Check database using prepared statement to prevent SQLi
				$data = $db->prepare( 'SELECT first_name, last_name FROM users WHERE user_id = (:id) LIMIT 1;' );
				$data->bindParam( ':id', $id, PDO::PARAM_INT );
				$data->execute();

				// Get results
				while( $row = $data->fetch() ) {
					// Get values
					$first = $row["first_name"];
					$last  = $row["last_name"];

					// Feedback for end user
					$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
				}

				break;
			case SQLITE:
				global $sqlite_db_connection;

				// Use parameterized query to prevent SQLi
				$stmt = $sqlite_db_connection->prepare( 'SELECT first_name, last_name FROM users WHERE user_id = :id LIMIT 1;' );
				$stmt->bindValue( ':id', $id, SQLITE3_INTEGER );

				try {
					$result = $stmt->execute();
				} catch (Exception $e) {
					echo 'Caught exception: ' . $e->getMessage();
					exit();
				}

				if ($result) {
					while ($row = $result->fetchArray()) {
						// Get values
						$first = $row["first_name"];
						$last  = $row["last_name"];

						// Feedback for end user
						$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
					}
				} else {
					echo "Error in fetch ".$sqlite_db->lastErrorMsg();
				}
				break;
		}
	}
}

?>
