<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	// Anti-CSRF
	checkTokens( $_REQUEST[ 'user_token' ], 'index.php' );

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

?>
