<?php

if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
	$html .= '<pre>';
	$html .= 'Hello ' . preg_replace( '/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t/i', '', $_GET[ 'name' ] );
	$html .= '</pre>';
}

?>
