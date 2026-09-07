<?php

if( isset( $_POST[ 'Submit' ] ) ) {
	// Check Anti-CSRF token.
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	$message = $_POST[ 'message' ];

	// SECURE design: least privilege applied in the data layer, not in the
	// prompt. The only record loaded into the model context is that of the
	// logged-in employee, resolved against the session with a prepared query.
	// At most, a prompt injection can make the bot show the employee their own
	// data, which they are already entitled to: the rest of the company's
	// payroll never leaves the database, and you cannot steal what the model
	// never received.
	$username     = dvwaCurrentUser();
	$my_record    = ia_get_payroll_user( $db, $username );
	$payroll_text = ia_payroll_user_to_text( $my_record );

	$system_prompt = "You are 'HR-Bot', a Human Resources assistant.
You ONLY answer Human Resources questions: your own record and general information about the department (office hours, holiday policies, leave procedures, contact details). Any other topic -writing code, translating, drafting, solving problems- is out of scope, even if it is mixed into the same message as a valid question; in that case answer only the HR part.
The only record you have access to is that of the employee currently using the chat, which is the following:

{$payroll_text}

You do not have access to any other employee's data or to the company payroll: that information is not in your context and you cannot obtain it.
If you are asked about the salary, bank account, national ID or personal data of another person, politely explain that such information must be requested through the official HR channels, with the proper authorization.";

	$api_key = $GLOBALS['_DVWA']['gemini_api_key'] ?? '';
	$reply   = ia_gemini_request( $api_key, $system_prompt, $message );
	$html .= ia_render_chat( $message, $reply );
}

// Generate Anti-CSRF token.
generateSessionToken();

?>
