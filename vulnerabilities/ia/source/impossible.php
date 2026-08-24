<?php

if( isset( $_POST[ 'Submit' ] ) ) {
	// Check Anti-CSRF token.
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	$message = $_POST[ 'message' ];

	// SECURE design: the sensitive payroll data is NEVER loaded into the model
	// context. The assistant only knows public HR information, so there is no
	// salary data a prompt injection could leak: you cannot steal what the
	// model never received.
	$system_prompt = "You are 'HR-Bot', a Human Resources assistant.
You only have general HR information: office hours, holiday policies, leave procedures and the department's contact details.
You do NOT have access to salaries, payslips, bank accounts, national IDs or the company payroll.
If you are asked about salary information or employees' personal data, politely explain that such information must be requested through the official HR channels with the proper authorization.";

	$api_key = $GLOBALS['_DVWA']['gemini_api_key'];
	$reply   = ia_gemini_request( $api_key, $system_prompt, $message );
	$html .= ia_render_chat( $message, $reply );
}

// Generate Anti-CSRF token.
generateSessionToken();

?>
