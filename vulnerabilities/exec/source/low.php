<?php

if( isset( $_POST[ 'submit' ] ) ) {

	$target = $_REQUEST[ 'ip' ];

	// Determine OS and execute the ping command.
	if (stristr(php_uname('s'), 'Windows NT')) { 
	
		$cmd = shell_exec( 'ping  ' . $target );
		$html .= '<pre>'.$cmd.'</pre>';
		
	} else { 
	
		$cmd = shell_exec( 'ping  -c 3 ' . $target );
		$html .= '<pre>'.$cmd.'</pre>';
		
	}
	
}
?>