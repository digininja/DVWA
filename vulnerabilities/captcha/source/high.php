<?php
if( isset( $_POST['Change'] ) && ( $_POST['step'] == '1' ) ) {
	
	$hide_form = true;
	
        $pass_new = $_POST['password_new'];
	$pass_new = stripslashes( $pass_new );
	$pass_new = mysql_real_escape_string( $pass_new );
	$pass_new = md5( $pass_new );

        $pass_conf = $_POST['password_conf'];
        $pass_conf = stripslashes( $pass_conf );
	$pass_conf = mysql_real_escape_string( $pass_conf );
	$pass_conf = md5( $pass_conf );
	
        $resp = recaptcha_check_answer ($_DVWA['recaptcha_private_key'],
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		$html .= "<pre><br />The CAPTCHA was incorrect. Please try again.</pre>";
		$hide_form = false;
		return;	
	} else {
                // Check that the current password is correct
		$qry = "SELECT password FROM `users` WHERE user='admin' AND password='$pass_curr';";
		$result = mysql_query($qry) or die('<pre>' . mysql_error() . '</pre>' );
                
                if (($pass_new == $pass_conf)  && ( $result && mysql_num_rows( $result ) == 1 )){
                       $insert="UPDATE `users` SET password = '$pass_new' WHERE user = '" . dvwaCurrentUser() . "';";
                       $result=mysql_query($insert) or die('<pre>' . mysql_error() . '</pre>' );

                       $html .= "<pre> Password Changed </pre>";
                       mysql_close();
                }

                else{
                       $html .= "<pre> Either your current password is incorrect or the new passwords did not match. Please try again. </pre>";
                }
	}
}
?>