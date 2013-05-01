<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'Vulnerability: Brute Force';
$page[ 'page_id' ] = 'brute';

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
	default:
		$vulnerabilityFile = 'high.php';
		break;
}

require_once DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/brute/source/{$vulnerabilityFile}";

$page[ 'help_button' ] = 'brute';
$page[ 'source_button' ] = 'brute';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Brute Force</h1>

	<div class=\"vulnerable_code_area\">

		<h2>Login</h2>

		<form action=\"#\" method=\"GET\">
			Username:<br><input type=\"text\" name=\"username\"><br>
			Password:<br><input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password\"><br>
			<input type=\"submit\" value=\"Login\" name=\"Login\">
		</form>

		{$html}

	</div>

	<h2>More info</h2>
	<ul>
		<li>".dvwaExternalLinkUrlGet( 'http://www.owasp.org/index.php/Testing_for_Brute_Force_%28OWASP-AT-004%29')."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://www.securityfocus.com/infocus/1192')."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://www.sillychicken.co.nz/Security/how-to-brute-force-http-forms-in-windows.html')."</li>
	</ul>
</div>
";

dvwaHtmlEcho( $page );

?>