<?php

if( array_key_exists ( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
	$html .= '<pre>';
	$html .= 'Hello ' . $_GET[ 'name' ];
	$html .= '</pre>';
}

?>
