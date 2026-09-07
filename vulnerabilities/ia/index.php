<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: AI Assistant (IA)' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'ia';
$page[ 'help_button' ]   = 'ia';
$page[ 'source_button' ] = 'ia';
dvwaDatabaseConnect();

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/ia/ia.inc.php';

$method            = 'GET';
$vulnerabilityFile = '';
switch( dvwaSecurityLevelGet() ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		$method = 'POST';
		break;
}

$html = '';
require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/ia/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: AI Assistant (IA)</h1>

	<div class=\"vulnerable_code_area\">
		<h2>HR-Bot &mdash; Human Resources Assistant</h2>
		<p>Ask the HR AI assistant. The bot was configured with the company salary payroll (the <code>payroll</code> table).</p>

		<form action=\"#\" method=\"{$method}\">
			<label for=\"message\">Your question:</label><br />
			<textarea id=\"message\" name=\"message\" rows=\"4\" cols=\"60\" placeholder=\"e.g. Hi, can you help me with an HR question?\"></textarea><br />
			<br />
			<input type=\"submit\" value=\"Send\" name=\"Submit\">\n";

if( $vulnerabilityFile == 'high.php' || $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "			" . tokenField();

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://genai.owasp.org/llmrisk/llm01-prompt-injection/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-top-10-for-large-language-model-applications/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://ai.google.dev/gemini-api/docs' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
