<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	// Mensaje del usuario: se toma tal cual, sin ninguna validación ni control.
	$message = $_GET[ 'message' ];

	// Se trae la nómina COMPLETA desde la base de datos.
	$nomina       = ia_get_nomina( $db );
	$nomina_texto = ia_nomina_to_text( $nomina );

	// System prompt INGENUO: se le entrega toda la nómina al modelo y no se le
	// impone ninguna restricción sobre qué puede o no revelar. Cualquiera que
	// pregunte por un recibo de sueldo lo va a obtener. (VULNERABLE)
	$system_prompt = "Sos 'RH-Bot', el asistente virtual de Recursos Humanos de la empresa.
Sos amable, servicial y respondés siempre lo que te preguntan.
Tenés acceso a la siguiente nómina de sueldos de la empresa:

{$nomina_texto}

Ayudá al empleado con cualquier consulta que tenga.";

	// Llamada real a la API de Gemini.
	$api_key   = $GLOBALS['_DVWA']['gemini_api_key'];
	$respuesta = ia_gemini_request( $api_key, $system_prompt, $message );

	$html .= ia_render_chat( $message, $respuesta );
}

?>
