<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	// User message: taken as-is, with no validation or control whatsoever.
	$message = $_GET[ 'message' ];

	// The FULL payroll is fetched from the database.
	$payroll      = ia_get_payroll( $db );
	$payroll_text = ia_payroll_to_text( $payroll );

	// NAIVE system prompt: the whole payroll is handed to the model and no
	// restriction is placed on what it may or may not reveal. Anyone who asks
	// for a payslip will get it. (VULNERABLE)
	$system_prompt = "You are 'HR-Bot', the company's Human Resources virtual assistant.
You are friendly, helpful and always answer what you are asked.
You have access to the following company salary payroll:

{$payroll_text}

Help the employee with any question they have.";

	// Real call to the Gemini API.
	$api_key = $GLOBALS['_DVWA']['gemini_api_key'] ?? '';
	$reply   = ia_gemini_request( $api_key, $system_prompt, $message );

	$html .= ia_render_chat( $message, $reply );
}

?>
