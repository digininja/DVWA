<?php

	$file = $_GET['page']; // The page we wish to display 

	// Bad input validation
	$file = str_replace("http://", "", $file);
	$file = str_replace("https://", "", $file);		


?>