<?php

/*
 * Shared helpers for the 'AI Assistant (IA)' module.
 *
 * This file holds the module's reusable infrastructure:
 *   - ia_get_payroll()          -> reads the full salary payroll from the DB.
 *   - ia_get_payroll_user()     -> reads only the logged-in employee's record.
 *   - ia_payroll_to_text()      -> formats payroll rows as text for the prompt.
 *   - ia_payroll_user_to_text() -> formats a single employee's row.
 *   - ia_gemini_model()         -> resolves the Gemini model from the config.
 *   - ia_gemini_request()       -> makes the real HTTP call to the Gemini API.
 *   - ia_render_chat()          -> renders the user / HR-Bot exchange.
 *   - ia_markdown_lite()        -> minimal, XSS-safe Markdown rendering.
 *
 * The security logic that changes between levels (the system prompt and the
 * guardrails) lives in source/{low,medium,high,impossible}.php, which is what
 * the "View Source" button shows.
 */

// Default Gemini model, used when 'gemini_model' is not set in config.inc.php.
if( !defined( 'IA_GEMINI_DEFAULT_MODEL' ) ) {
	define( 'IA_GEMINI_DEFAULT_MODEL', 'gemini-3.6-flash' );
}

/**
 * Returns the Gemini model to use: the value of $_DVWA['gemini_model'] from the
 * config file if it is set, otherwise the built-in default. Google occasionally
 * retires models, so keeping this configurable avoids a hard-coded dead model.
 */
function ia_gemini_model() {
	if( isset( $GLOBALS['_DVWA']['gemini_model'] ) && $GLOBALS['_DVWA']['gemini_model'] !== '' ) {
		return $GLOBALS['_DVWA']['gemini_model'];
	}
	return IA_GEMINI_DEFAULT_MODEL;
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

/**
 * Performs the real call to the Google Gemini API.
 *
 * The API key is sent as a header (x-goog-api-key), never in the URL.
 *
 * @param string $apiKey        Gemini API key.
 * @param string $systemPrompt  System instructions (role + guardrails).
 * @param string $userMessage   User message.
 * @return array{ok: bool, text: string}
 */
function ia_gemini_request( $apiKey, $systemPrompt, $userMessage ) {
	if( empty( $apiKey ) ) {
		return array(
			'ok'   => false,
			'text' => "No Google Gemini API key is configured.\n\n"
				. "How to get one (Google offers a free tier that is plenty for this lab):\n"
				. "1) Go to https://aistudio.google.com/app/apikey and sign in with a Google account.\n"
				. "2) Click \"Create API key\" and copy the key.\n\n"
				. "How to configure it in DVWA (either option):\n"
				. "- Edit config/config.inc.php and set:  \$_DVWA['gemini_api_key'] = 'YOUR_API_KEY';\n"
				. "- Or set the GEMINI_API_KEY environment variable (handy for Docker).\n\n"
				. "See the README, section \"Google Gemini API (AI Assistant module)\", for more details."
		);
	}
	if( !function_exists( 'curl_init' ) ) {
		return array(
			'ok'   => false,
			'text' => 'The PHP cURL extension is not available. Enable it to be able to call the Gemini API.'
		);
	}

	$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . ia_gemini_model() . ':generateContent';

	$payload = array(
		'system_instruction' => array(
			'parts' => array( array( 'text' => $systemPrompt ) )
		),
		'contents' => array(
			array(
				'role'  => 'user',
				'parts' => array( array( 'text' => $userMessage ) )
			)
		),
		'generationConfig' => array(
			'temperature'     => 0.2,
			'maxOutputTokens' => 1024
		)
	);

	$ch = curl_init( $url );
	curl_setopt_array( $ch, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST           => true,
		CURLOPT_HTTPHEADER     => array(
			'Content-Type: application/json',
			'x-goog-api-key: ' . $apiKey,
		),
		CURLOPT_POSTFIELDS     => json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE ),
		CURLOPT_TIMEOUT        => 30,
	) );

	$response = curl_exec( $ch );
	$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	$curlErr  = curl_error( $ch );
	// Note: curl_close() is not called: since PHP 8.0 the handle is an object
	// that is freed automatically, and curl_close() was deprecated in PHP 8.5.

	if( $response === false ) {
		return array( 'ok' => false, 'text' => 'Connection error while calling the Gemini API: ' . $curlErr );
	}

	$data = json_decode( $response, true );

	if( $httpCode !== 200 ) {
		$msg = isset( $data['error']['message'] ) ? $data['error']['message'] : $response;
		return array( 'ok' => false, 'text' => 'The Gemini API returned an error (HTTP ' . $httpCode . '): ' . $msg );
	}

	if( isset( $data['candidates'][0]['content']['parts'][0]['text'] ) ) {
		return array( 'ok' => true, 'text' => $data['candidates'][0]['content']['parts'][0]['text'] );
	}

	// It may come back empty due to the model's own safety filters.
	$finish = isset( $data['candidates'][0]['finishReason'] ) ? $data['candidates'][0]['finishReason'] : 'unknown';
	return array( 'ok' => false, 'text' => 'The model returned no text (finishReason: ' . $finish . ').' );
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
