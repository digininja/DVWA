<?php

/*
 * Shared helpers for the 'AI Assistant (IA)' module.
 *
 * This file holds the module's reusable infrastructure:
 *   - ia_get_payroll()          -> reads the full salary payroll from the DB.
 *   - ia_get_payroll_user()     -> reads only the logged-in employee's record.
 *   - ia_payroll_to_text()      -> formats payroll rows as text for the prompt.
 *   - ia_payroll_user_to_text() -> formats a single employee's row.
 *   - ia_render_chat()          -> renders the user / HR-Bot exchange.
 *   - ia_markdown_lite()        -> minimal, XSS-safe Markdown rendering.
 *
 * The security logic that changes between levels (the system prompt and the
 * guardrails) lives in source/{low,medium,high,impossible}.php, which is what
 * the "View Source" button shows.
 */

function ia_get_models() {
	if( !isset( $GLOBALS['_DVWA']['ai_models'] ) || !is_array( $GLOBALS['_DVWA']['ai_models'] ) ) {
		return array();
	}
	$models = array();
	foreach( $GLOBALS['_DVWA']['ai_models'] as $entry ) {
		if( !empty( $entry['url'] ) && !empty( $entry['model'] ) ) {
			if( empty( $entry['name'] ) ) {
				$entry['name'] = $entry['model'];
			}
			$models[] = $entry;
		}
	}
	return $models;
}

function ia_get_model( $index ) {
	$models = ia_get_models();
	$i = intval( $index );
	if( $i < 0 || $i >= count( $models ) ) {
		return null;
	}
	return $models[$i];
}

/**
 * Reads the full salary payroll from the `payroll` table.
 *
 * @param PDO $db DVWA PDO connection.
 * @return array  Payroll rows (or an empty array if the table is missing).
 */
function ia_get_payroll( $db ) {
	$rows = array();
	try {
		$stmt = $db->query( 'SELECT employee_id, full_name, position, department, gross_salary, net_salary, bank_account, national_id FROM payroll ORDER BY id;' );
		if( $stmt ) {
			$rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
		}
	} catch( Exception $e ) {
		$rows = array();
	}
	return $rows;
}

/**
 * Reads ONLY the payroll row of the given employee.
 *
 * This is the least-privilege version of ia_get_payroll(): the filter is
 * applied in the SQL query, not in the prompt, so the rest of the company's
 * salaries never leave the database. The username must come from the session
 * (dvwaCurrentUser()) and never from user input.
 *
 * @param PDO    $db       DVWA PDO connection.
 * @param string $username DVWA username of the logged-in employee.
 * @return array|null      The payroll row, or null if there is no linked record.
 */
function ia_get_payroll_user( $db, $username ) {
	try {
		$stmt = $db->prepare( 'SELECT employee_id, full_name, position, department, gross_salary, net_salary, bank_account, national_id FROM payroll WHERE username = :username LIMIT 1;' );
		$stmt->bindParam( ':username', $username, PDO::PARAM_STR );
		$stmt->execute();
		$row = $stmt->fetch( PDO::FETCH_ASSOC );
	} catch( Exception $e ) {
		$row = false;
	}
	return $row ? $row : null;
}

/**
 * Turns the payroll rows into a readable text block for the LLM.
 */
function ia_payroll_to_text( $rows ) {
	if( empty( $rows ) ) {
		return '(no payroll data loaded; run "Setup / Reset DB")';
	}
	$lines = array();
	foreach( $rows as $r ) {
		$lines[] = sprintf(
			'- ID %s | %s | %s (%s) | Gross salary: $%s | Net salary: $%s | Bank account: %s | National ID: %s',
			$r['employee_id'], $r['full_name'], $r['position'], $r['department'],
			$r['gross_salary'], $r['net_salary'], $r['bank_account'], $r['national_id']
		);
	}
	return implode( "\n", $lines );
}

/**
 * Formats a single employee's row for the prompt.
 *
 * @param array|null $row Result of ia_get_payroll_user().
 */
function ia_payroll_user_to_text( $row ) {
	if( empty( $row ) ) {
		return '(the logged-in employee has no linked payroll record)';
	}
	return ia_payroll_to_text( array( $row ) );
}

function ia_chat_request( $modelEntry, $systemPrompt, $userMessage ) {
	if( empty( $modelEntry ) ) {
		return array(
			'ok'   => false,
			'text' => "No AI model is configured.\n\n"
				. "Edit config/config.inc.php and add at least one entry to \$_DVWA['ai_models'].\n"
				. "Each entry needs: name, url (OpenAI-compatible chat completions endpoint), model and key.\n\n"
				. "You can also set the AI_MODEL_URL, AI_MODEL_ID and AI_MODEL_KEY environment variables.\n\n"
				. "See the README for examples with Gemini, OpenAI and Ollama."
		);
	}
	if( !function_exists( 'curl_init' ) ) {
		return array(
			'ok'   => false,
			'text' => 'The PHP cURL extension is not available. Enable it to call the AI model.'
		);
	}

	$payload = array(
		'model'    => $modelEntry['model'],
		'messages' => array(
			array( 'role' => 'system',  'content' => $systemPrompt ),
			array( 'role' => 'user',    'content' => $userMessage ),
		),
	);

	if( isset( $modelEntry['params'] ) && is_array( $modelEntry['params'] ) ) {
		$payload = array_merge( $payload, $modelEntry['params'] );
	}

	$headers = array( 'Content-Type: application/json' );
	if( !empty( $modelEntry['key'] ) ) {
		$headers[] = 'Authorization: Bearer ' . $modelEntry['key'];
	}

	$ch = curl_init( $modelEntry['url'] );
	curl_setopt_array( $ch, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST           => true,
		CURLOPT_HTTPHEADER     => $headers,
		CURLOPT_POSTFIELDS     => json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE ),
		CURLOPT_TIMEOUT        => 60,
	) );

	$response = curl_exec( $ch );
	$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	$curlErr  = curl_error( $ch );

	if( $response === false ) {
		return array( 'ok' => false, 'text' => 'Connection error while calling ' . $modelEntry['name'] . ': ' . $curlErr );
	}

	$data = json_decode( $response, true );

	if( $httpCode !== 200 ) {
		$err  = isset( $data['error'] ) ? $data['error'] : ( isset( $data[0]['error'] ) ? $data[0]['error'] : null );
		$msg  = isset( $err['message'] ) ? $err['message'] : ( is_string( $err ) ? $err : $response );
		$text = $modelEntry['name'] . ' returned an error (HTTP ' . $httpCode . '): ' . $msg;
		if( empty( $modelEntry['key'] ) && in_array( $httpCode, array( 400, 401, 403 ) ) ) {
			$text = "No API key is configured for this model, and the provider rejected the request.\n\n"
				. "This module works with any provider that offers an OpenAI-compatible chat API:\n"
				. "- A hosted provider such as Gemini or OpenAI. Google Gemini has a free tier that is plenty for this lab: get a key at https://aistudio.google.com/app/apikey.\n"
				. "- A local model, for example with Ollama, which needs no key at all.\n\n"
				. "To set it up, edit config/config.inc.php and fill in 'url', 'model' and 'key' for this model in \$_DVWA['ai_models']. For the first model you can use the AI_MODEL_URL, AI_MODEL_ID and AI_MODEL_KEY environment variables instead (handy for Docker).\n\n"
				. "See the README, section \"AI Models (AI Assistant module)\", for examples.\n\n"
				. $text;
		}
		return array( 'ok' => false, 'text' => $text );
	}

	if( isset( $data['choices'][0]['message']['content'] ) ) {
		return array( 'ok' => true, 'text' => $data['choices'][0]['message']['content'] );
	}

	$finish = isset( $data['choices'][0]['finish_reason'] ) ? $data['choices'][0]['finish_reason'] : 'unknown';
	return array( 'ok' => false, 'text' => 'The model returned no text (finish_reason: ' . $finish . ').' );
}

/**
 * Minimal, XSS-safe rendering of the little Markdown the LLM tends to emit.
 * The text is HTML-escaped first, then a couple of common constructs
 * (**bold** and "* " / "- " bullets) are turned into safe HTML, so replies do
 * not show raw asterisks.
 */
function ia_markdown_lite( $text ) {
	$safe = htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	// **bold** -> <strong>bold</strong>
	$safe = preg_replace( '/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $safe );
	// leading "* " or "- " list markers -> bullet
	$safe = preg_replace( '/^[ \t]*[\*\-][ \t]+/m', '&bull; ', $safe );
	return nl2br( $safe );
}

/**
 * Renders the chat exchange (user message + bot reply).
 * Output is escaped (see ia_markdown_lite) so the focus stays on the LLM
 * vulnerability and not on an accidental XSS.
 */
function ia_render_chat( $userMessage, $reply ) {
	$userSafe = nl2br( htmlspecialchars( $userMessage, ENT_QUOTES, 'UTF-8' ) );
	$botSafe  = ia_markdown_lite( $reply['text'] );
	$botStyle = $reply['ok']
		? 'background:#eef6ff;border-left:4px solid #3b82f6;'
		: 'background:#fff0f0;border-left:4px solid #dc2626;';

	return "
	<div class=\"ia-chat\" style=\"margin-top:1em;\">
		<p style=\"background:#f3f4f6;border-left:4px solid #9ca3af;padding:.6em .8em;margin:.4em 0;\"><strong>You:</strong><br />{$userSafe}</p>
		<p style=\"{$botStyle}padding:.6em .8em;margin:.4em 0;\"><strong>HR-Bot:</strong><br />{$botSafe}</p>
	</div>";
}

?>
