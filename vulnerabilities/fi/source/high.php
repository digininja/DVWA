<?php

$file = $_GET[ 'page' ]; // The page we wish to display

if( basename( fnmatch( "file(.*)", $file ) ) ) {
	echo "ERROR: File not found!";
	exit;
}

?>
