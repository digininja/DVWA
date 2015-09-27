<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Cross Site Request Forgery (CSRF)' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'csrf';
$page[ 'help_button' ]   = 'csrf';
$page[ 'source_button' ] = 'csrf';

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( $_COOKIE[ 'security' ] ) {
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
		break;
}

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/csrf/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Cross Site Request Forgery (CSRF)</h1>

	<div class=\"vulnerable_code_area\">
		<h3>Change your admin password:</h3>
		<br />

		<form action=\"#\" method=\"GET\">";

if( $vulnerabilityFile == 'impossible.php' ) {
	$page[ 'body' ] .= "
			Current password:<br />
			<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_current\"><br />";
}

$page[ 'body' ] .= "
			New password:<br />
			<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_new\"><br />
			Confirm new password:<br />
			<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_conf\"><br />
			<br />
			<input type=\"submit\" value=\"Change\" name=\"Change\">\n";

if( $vulnerabilityFile == 'high.php' || $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "			" . tokenField();

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Cross-Site_Request_Forgery' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'http://www.cgisecurity.com/csrf-faq.html' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Cross-site_request_forgery ' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
