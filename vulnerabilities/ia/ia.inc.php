<?php

/*
 * Helpers compartidos del módulo 'AI Assistant (IA)'.
 *
 * Este archivo contiene la infraestructura reutilizable del módulo:
 *   - ia_get_nomina()      -> lee la nómina de sueldos desde la base de datos.
 *   - ia_nomina_to_text()  -> formatea la nómina como texto para el prompt.
 *   - ia_gemini_request()  -> hace la llamada HTTP real a la API de Gemini.
 *   - ia_render_chat()     -> renderiza el intercambio usuario / RH-Bot.
 *
 * La lógica de seguridad que cambia entre niveles (el system prompt y los
 * controles) vive en source/{low,medium,high,impossible}.php, que es lo que
 * muestra el botón "View Source".
 */

// Modelo de Gemini a utilizar.
if( !defined( 'IA_GEMINI_MODEL' ) ) {
	define( 'IA_GEMINI_MODEL', 'gemini-2.5-flash' );
}

/**
 * Trae la nómina completa de sueldos desde la tabla `nomina`.
 *
 * @param PDO $db Conexión PDO de DVWA.
 * @return array  Filas de la nómina (o array vacío si la tabla no existe).
 */
function ia_get_nomina( $db ) {
	$rows = array();
	try {
		$stmt = $db->query( 'SELECT legajo, nombre, cargo, departamento, sueldo_bruto, sueldo_neto, cbu, cuil FROM nomina ORDER BY id;' );
		if( $stmt ) {
			$rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
		}
	} catch( Exception $e ) {
		$rows = array();
	}
	return $rows;
}

/**
 * Convierte las filas de la nómina en un bloque de texto legible para el LLM.
 */
function ia_nomina_to_text( $rows ) {
	if( empty( $rows ) ) {
		return '(no hay datos de nómina cargados; ejecutá "Setup / Reset DB")';
	}
	$lines = array();
	foreach( $rows as $r ) {
		$lines[] = sprintf(
			'- Legajo %s | %s | %s (%s) | Sueldo bruto: $%s | Sueldo neto: $%s | CBU: %s | CUIL: %s',
			$r['legajo'], $r['nombre'], $r['cargo'], $r['departamento'],
			$r['sueldo_bruto'], $r['sueldo_neto'], $r['cbu'], $r['cuil']
		);
	}
	return implode( "\n", $lines );
}

/**
 * Realiza la llamada real a la API de Google Gemini.
 *
 * La API key se envía por header (x-goog-api-key), nunca en la URL.
 *
 * @param string $apiKey        Clave de la API de Gemini.
 * @param string $systemPrompt  Instrucciones de sistema (rol + guardrails).
 * @param string $userMessage   Mensaje del usuario.
 * @return array{ok: bool, text: string}
 */
function ia_gemini_request( $apiKey, $systemPrompt, $userMessage ) {
	if( empty( $apiKey ) ) {
		return array(
			'ok'   => false,
			'text' => 'No hay API key de Gemini configurada. Definí $_DVWA[\'gemini_api_key\'] en config/config.inc.php o la variable de entorno GEMINI_API_KEY.'
		);
	}
	if( !function_exists( 'curl_init' ) ) {
		return array(
			'ok'   => false,
			'text' => 'La extensión cURL de PHP no está disponible. Habilitala para poder llamar a la API de Gemini.'
		);
	}

	$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . IA_GEMINI_MODEL . ':generateContent';

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
	// Nota: no se llama a curl_close(): desde PHP 8.0 el handle es un objeto
	// que se libera solo, y curl_close() quedó deprecado en PHP 8.5.

	if( $response === false ) {
		return array( 'ok' => false, 'text' => 'Error de conexión con la API de Gemini: ' . $curlErr );
	}

	$data = json_decode( $response, true );

	if( $httpCode !== 200 ) {
		$msg = isset( $data['error']['message'] ) ? $data['error']['message'] : $response;
		return array( 'ok' => false, 'text' => 'La API de Gemini devolvió un error (HTTP ' . $httpCode . '): ' . $msg );
	}

	if( isset( $data['candidates'][0]['content']['parts'][0]['text'] ) ) {
		return array( 'ok' => true, 'text' => $data['candidates'][0]['content']['parts'][0]['text'] );
	}

	// Puede venir vacío por filtros de seguridad del propio modelo.
	$finish = isset( $data['candidates'][0]['finishReason'] ) ? $data['candidates'][0]['finishReason'] : 'desconocido';
	return array( 'ok' => false, 'text' => 'El modelo no devolvió texto (finishReason: ' . $finish . ').' );
}

/**
 * Renderiza el intercambio de chat (mensaje del usuario + respuesta del bot).
 * Escapa toda salida para que el foco quede en la vulnerabilidad del LLM y no
 * en un XSS accidental.
 */
function ia_render_chat( $userMessage, $respuesta ) {
	$userSafe = nl2br( htmlspecialchars( $userMessage, ENT_QUOTES, 'UTF-8' ) );
	$botSafe  = nl2br( htmlspecialchars( $respuesta['text'], ENT_QUOTES, 'UTF-8' ) );
	$botStyle = $respuesta['ok']
		? 'background:#eef6ff;border-left:4px solid #3b82f6;'
		: 'background:#fff0f0;border-left:4px solid #dc2626;';

	return "
	<div class=\"ia-chat\" style=\"margin-top:1em;\">
		<p style=\"background:#f3f4f6;border-left:4px solid #9ca3af;padding:.6em .8em;margin:.4em 0;\"><strong>Vos:</strong><br />{$userSafe}</p>
		<p style=\"{$botStyle}padding:.6em .8em;margin:.4em 0;\"><strong>RH-Bot:</strong><br />{$botSafe}</p>
	</div>";
}

?>
