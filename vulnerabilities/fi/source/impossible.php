<?php

$file = $_GET[ 'page' ]; // The page we wish to display

// Only allow include.php or file{1..3}.php
if( $file != "include.php" && $file != "file1.php" && $file != "file2.php" && $file != "file3.php" ) {
	echo "ERROR: File not found!";
	exit;
}

?>
