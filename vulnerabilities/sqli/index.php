<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: SQL Injection' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'sqli';
$page[ 'help_button' ]   = 'sqli';
$page[ 'source_button' ] = 'sqli';

dvwaDatabaseConnect();

$method            = 'GET';
$vulnerabilityFile = '';
switch( $_COOKIE[ 'security' ] ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		$method = 'POST';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		break;
}

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/sqli/source/{$vulnerabilityFile}";

// Is PHP function magic_quotee enabled?
$WarningHtml = '';
if( ini_get( 'magic_quotes_gpc' ) == true ) {
	$WarningHtml .= "<div class=\"warning\">The PHP function \"<em>Magic Quotes</em>\" is enabled.</div>";
}
// Is PHP function safe_mode enabled?
if( ini_get( 'safe_mode' ) == true ) {
	$WarningHtml .= "<div class=\"warning\">The PHP function \"<em>Safe mode</em>\" is enabled.</div>";
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: SQL Injection</h1>

	{$WarningHtml}

	<div class=\"vulnerable_code_area\">";
if( $vulnerabilityFile == 'high.php' ) {
	$page[ 'body' ] .= "Click <a href=\"#\" onclick=\"javascript:popUp('session-input.php');return false;\">here to change your ID</a>.";
}
else {
	$page[ 'body' ] .= "
		<form action=\"#\" method=\"{$method}\">
			<p>
				User ID:";
	if( $vulnerabilityFile == 'medium.php' ) {
		$page[ 'body' ] .= "\n				<select name=\"id\">";

		for( $i = 1; $i < $number_of_rows + 1 ; $i++ ) { $page[ 'body' ] .= "<option value=\"{$i}\">{$i}</option>"; }
		$page[ 'body' ] .= "</select>";
	}
	else
		$page[ 'body' ] .= "\n				<input type=\"text\" size=\"15\" name=\"id\">";

	$page[ 'body' ] .= "\n				<input type=\"submit\" name=\"Submit\" value=\"Submit\">
			</p>\n";

	if( $vulnerabilityFile == 'impossible.php' )
		$page[ 'body' ] .= "			" . tokenField();

	$page[ 'body' ] .= "
		</form>";
}
$page[ 'body' ] .= "
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.securiteam.com/securityreviews/5DP0N1P76E.html' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/SQL_injection' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.netsparker.com/blog/web-security/sql-injection-cheat-sheet/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/SQL_Injection' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://bobby-tables.com/' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
