<?php
			
	if (isset($_GET['Change'])) {
	
		// Turn requests into variables
		$pass_curr = $_GET['password_current'];
		$pass_new = $_GET['password_new'];
		$pass_conf = $_GET['password_conf'];

		// Sanitise current password input
		$pass_curr = stripslashes( $pass_curr );
		$pass_curr = mysql_real_escape_string( $pass_curr );
		$pass_curr = md5( $pass_curr );
		
		// Check that the current password is correct
		$qry = "SELECT password FROM `users` WHERE user='admin' AND password='$pass_curr';";
		$result = mysql_query($qry) or die('<pre>' . mysql_error() . '</pre>' );

		if (($pass_new == $pass_conf) && ( $result && mysql_num_rows( $result ) == 1 )){
			$pass_new = mysql_real_escape_string($pass_new);
			$pass_new = md5($pass_new);

			$insert="UPDATE `users` SET password = '$pass_new' WHERE user = 'admin';";
			$result=mysql_query($insert) or die('<pre>' . mysql_error() . '</pre>' );
						
			$html .= "<pre> Password Changed </pre>";		
			mysql_close();
		}
	
		else{		
			$html .= "<pre> Passwords did not match or current password incorrect. </pre>";			
		}

	}
?>