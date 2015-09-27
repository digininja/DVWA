<?php

$file = $_GET[ 'page' ]; // The page we wish to display

if( !fnmatch( "file*", $file ) && $file != "include.php" ) {
	echo "ERROR: File not found!";
	exit;
}

?>
