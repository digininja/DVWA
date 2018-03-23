<?php

// new php7 captcha v2 implementation.

function recaptcha_check_answer($key, $response){
	return CheckCaptcha($key, $response);
}

function CheckCaptcha($key, $response) {

	try {
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$dat = array(
			'secret'   => $key,
			'response' => urlencode($response),
			'remoteip' => urlencode($_SERVER['REMOTE_ADDR'])
		);

		$opt = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($dat)
			)
		);

		$context = stream_context_create($opt);
		$result  = file_get_contents($url, false, $context);

		return json_decode($result)->success;

	} catch (Exception $e) {
		return null;
	}

}

function recaptcha_get_html($pubKey){
	return "
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<br /> <div class='g-recaptcha' data-theme='dark' data-sitekey='" . $pubKey . "'></div>
	";
}

?>
