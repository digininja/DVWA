<?php

if( isset( $_POST[ 'submit'] ) ) {

	$target = $_REQUEST[ 'ip' ];

	// Remove any of the charactars in the array (blacklist).
	$substitutions = array(
		'&&' => '',
		';' => '',
	);

	$target = str_replace( array_keys( $substitutions ), $substitutions, $target );
	
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