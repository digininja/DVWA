<?php

if( isset( $_POST[ 'Submit' ] ) ) {
	// Comprobar token Anti-CSRF.
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	$message = $_POST[ 'message' ];

	// Diseño SEGURO: los datos sensibles de la nómina NUNCA se cargan en el
	// contexto del modelo. El asistente solo conoce información pública de
	// RRHH, así que no hay ningún dato salarial que una inyección de prompt
	// pueda filtrar: no se puede robar lo que el modelo nunca recibió.
	$system_prompt = "Sos 'RH-Bot', asistente de Recursos Humanos.
Solo tenés información general de RRHH: horarios de atención, políticas de vacaciones, procedimientos de licencias y datos de contacto del área.
No tenés acceso a sueldos, recibos, CBU, CUIL ni a la nómina de la empresa.
Si te consultan por información salarial o datos personales de empleados, indicá amablemente que esa información debe solicitarse por los canales oficiales de RRHH, con la debida autorización.";

	$api_key   = $GLOBALS['_DVWA']['gemini_api_key'];
	$respuesta = ia_gemini_request( $api_key, $system_prompt, $message );
	$html .= ia_render_chat( $message, $respuesta );
}

// Generar token Anti-CSRF.
generateSessionToken();

?>
