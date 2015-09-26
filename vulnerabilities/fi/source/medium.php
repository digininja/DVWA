<?php

$file = $_GET[ 'page' ]; // The page we wish to display

// Input validation
$file = str_replace( array( "http://", "https://" ), "", $file );
$file = str_replace( array( "../", "..\"" ), "", $file );

?>
