<?php
		
	$file = $_GET['page']; //The page we wish to display 

	// Only allow include.php
	if ( $file != "include.php" ) {
		echo "ERROR: File not found!";
		exit;
	}
		
?>