<?php

if( isset( $_POST[ 'submit' ] ) ) {

	$target = $_REQUEST["ip"];
	
	$target = stripslashes( $target );
	
	
	// Split the IP into 4 octects
	$octet = explode(".", $target);
	
	// Check IF each octet is an integer
	if ((is_numeric($octet[0])) && (is_numeric($octet[1])) && (is_numeric($octet[2])) && (is_numeric($octet[3])) && (sizeof($octet) == 4)  ) {
	
	// If all 4 octets are int's put the IP back together.
	$target = $octet[0].'.'.$octet[1].'.'.$octet[2].'.'.$octet[3];
	
	
		// Determine OS and execute the ping command.
		if (stristr(php_uname('s'), 'Windows NT')) { 
	
			$cmd = shell_exec( 'ping  ' . $target );
			$html .= '<pre>'.$cmd.'</pre>';
		
		} else { 
	
			$cmd = shell_exec( 'ping  -c 3 ' . $target );
			$html .= '<pre>'.$cmd.'</pre>';
		
		}
	
	}
	
	else {
		$html .= '<pre>ERROR: You have entered an invalid IP</pre>';
	}
	
	
}

?>
