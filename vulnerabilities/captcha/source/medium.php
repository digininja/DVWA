<?php
if( isset( $_POST['Change'] ) && ( $_POST['step'] == '1' ) ) {
	
	$hide_form = true;
	$user = $_POST['username'];
	$pass_new = $_POST['password_new'];
	$pass_conf = $_POST['password_conf'];
	$resp = recaptcha_check_answer($_DVWA['recaptcha_private_key'],
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		$html .= "<pre><br />The CAPTCHA was incorrect. Please try again.</pre>";
		$hide_form = false;
		return;	
	} else {
        	if (($pass_new == $pass_conf)){
			$html .= "<pre><br />You passed the CAPTCHA! Click the button to confirm your changes. <br /></pre>";
			$html .= "
			<form action=\"#\" method=\"POST\">
				<input type=\"hidden\" name=\"step\" value=\"2\" />
				<input type=\"hidden\" name=\"password_new\" value=\"" . $pass_new . "\" />
				<input type=\"hidden\" name=\"password_conf\" value=\"" . $pass_conf . "\" />
				<input type=\"hidden\" name=\"passed_captcha\" value=\"true\" />
				<input type=\"submit\" name=\"Change\" value=\"Change\" />
			</form>";
	        }	

        	else{
                	$html .= "<pre> Both passwords must match </pre>";
			$hide_form = false;
	        }
	}
}

if( isset( $_POST['Change'] ) && ( $_POST['step'] == '2' ) ) 
{
	$hide_form = true;
	if (!$_POST['passed_captcha'])
	{
                $html .= "<pre><br />You have not passed the CAPTCHA. Bad hacker, no doughnut.</pre>";
		$hide_form = false;
		return;
	}
        $pass = md5($pass_new);
        if (($pass_new == $pass_conf)){
               $pass_new = mysql_real_escape_string($pass_new);
               $pass_new = md5($pass_new);

               $insert="UPDATE `users` SET password = '$pass_new' WHERE user = '" . dvwaCurrentUser() . "';";
               $result=mysql_query($insert) or die('<pre>' . mysql_error() . '</pre>' );

               $html .= "<pre> Password Changed </pre>";
               mysql_close();
        }

        else{
               $html .= "<pre> Passwords did not match. </pre>";
        }
}
?>
