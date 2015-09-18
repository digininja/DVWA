<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	// Anti-CSRF
	checkTokens( $_REQUEST[ 'user_token' ], 'index.php' );

	// Retrieve data
	$id = $_GET[ 'id' ];

	if(is_numeric( $id )) {
		$data = $db->query( 'SELECT first_name, last_name FROM users WHERE user_id = ' . $db->quote( $id ) );
		if( count( $data->fetchAll() ) > 0 ) {
			$html .= '<pre>User ID exists in the database.</pre>';
		}
		else {
			header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );
			$html .= '<pre>User ID is MISSING from the database.</pre>';
		}
	}
}

?>
