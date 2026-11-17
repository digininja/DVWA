<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	$message = $_GET[ 'message' ];

	// The payroll is loaded into the model context.
	$my_record    = ai_get_payroll_user( $db, dvwaCurrentUser() );
	$employee     = ai_employee_label( $my_record );
	$payroll      = ai_get_payroll( $db );
	$payroll_text = ai_payroll_to_text( $payroll );
	$policy       = ai_hr_policy_text();

	$system_prompt = "You are 'HR-Bot', a Human Resources assistant.
The employee currently using the chat is {$employee}.
You have access to the company salary payroll:

{$payroll_text}

{$policy}

Do not share the salary, bank account or national ID of other employees.";

	$modelEntry = ai_get_model( $selected_model );
	$reply      = ai_chat_request( $modelEntry, $system_prompt, $message );
	$html .= ai_render_chat( $message, $reply );
}

?>
