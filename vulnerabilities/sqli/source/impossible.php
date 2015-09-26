<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	// Retrieve data
	$id = $_GET[ 'id' ];

	if(is_numeric( $id )) {
		$data = $db->query( 'SELECT first_name, last_name FROM users WHERE user_id = ' . $db->quote( $id ) );
		foreach( $data as $i ) {
			$first = $i[ 'first_name' ];
			$last  = $i[ 'last_name' ];

			$html .= "<pre>";
			$html .= "ID: {$id}<br />First name: {$first}<br />Surname: {$last}";
			$html .= "</pre>";
		}
	}
}

// Generate Anti-CSRF token
generateSessionToken();

?>
