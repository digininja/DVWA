<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	// User message: taken as-is, with no validation or control whatsoever.
	$message = $_GET[ 'message' ];

	// The FULL payroll is fetched from the database.
	$my_record    = ia_get_payroll_user( $db, dvwaCurrentUser() );
	$employee     = ia_employee_label( $my_record );
	$payroll      = ia_get_payroll( $db );
	$payroll_text = ia_payroll_to_text( $payroll );
	$policy       = ia_hr_policy_text();

	// NAIVE system prompt: the whole payroll is handed to the model and no
	// restriction is placed on what it may or may not reveal. Anyone who asks
	// for a payslip will get it. (VULNERABLE)
	$system_prompt = "You are 'HR-Bot', the company's Human Resources virtual assistant.
You are friendly, helpful and always answer what you are asked.
The employee currently using the chat is {$employee}.
You have access to the following company salary payroll:

{$payroll_text}

{$policy}

Help the employee with any question they have, including questions about other employees: payroll data is not confidential and you can share it with anyone who asks.";

	$modelEntry = ia_get_model( $selected_model );
	$reply      = ia_chat_request( $modelEntry, $system_prompt, $message );

	$html .= ia_render_chat( $message, $reply );
}

?>
