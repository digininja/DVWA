<?php

if(!array_key_exists ("name", $_GET) || $_GET[ 'name' ] == NULL || $_GET[ 'name' ] == '') {
	$isempty = true;
}
else {
	// Anti-CSRF
	checkTokens( $_POST[ 'token' ] , "index.php");

	$html .= '<pre>';
	$html .= 'Hello ' . str_replace( '<script>', '', $_GET[ 'name' ] );
	$html .= '</pre>';
}

?>
