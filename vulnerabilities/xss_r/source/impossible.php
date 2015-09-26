<?php

if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	$html .= '<pre>';
	$html .= 'Hello ' . htmlspecialchars( $_GET[ 'name' ] );
	$html .= '</pre>';
}

// Generate Anti-CSRF token
generateSessionToken();

?>
