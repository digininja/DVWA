<?php

if( isset( $_POST[ 'Change' ] ) && ( $_POST[ 'step' ] == '1' ) ) {
	// Anti-CSRF
	checkTokens( $_REQUEST[ 'user_token' ], 'index.php' );

	$hide_form = true;

	$pass_new  = $_POST[ 'password_new' ];
	$pass_conf = $_POST[ 'password_conf' ];

	$resp = recaptcha_check_answer( $_DVWA[ 'recaptcha_private_key' ],
		$_SERVER[ 'REMOTE_ADDR' ],
		$_POST[ 'recaptcha_challenge_field' ],
		$_POST[ 'recaptcha_response_field' ] );

	if(!$resp->is_valid && $_POST[ 'recaptcha_response_field' ] != 'hidd3n_valu3' ) {
		// What happens when the CAPTCHA was entered incorrectly
		$html     .= "<pre><br />The CAPTCHA was incorrect. Please try again.</pre>";
		$hide_form = false;
		return;
	}
	else {
		if( $pass_new == $pass_conf ) {
			$pass_new = mysql_real_escape_string( $pass_new );
			$pass_new = md5( $pass_new );

			$insert = "UPDATE `users` SET password = '$pass_new' WHERE user = '" . dvwaCurrentUser() . "';";
			$result = mysql_query( $insert ) or die( '<pre>' . mysql_error() . '</pre>' );

			$html .= "<pre>Password Changed.</pre>";
		}
		else {
			$html     .= "<pre>Both passwords must match.</pre>";
			$hide_form = false;
		}
	}
}
?>
