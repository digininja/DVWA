<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Content Security Policy (CSP) Bypass' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'csp';
$page[ 'help_button' ]   = 'csp';
$page[ 'source_button' ] = 'csp';

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

$page[ 'body' ] = <<<EOF
<div class="body_padded">
	<h1>Vulnerability: Content Security Policy (CSP) Bypass</h1>

	<div class="vulnerable_code_area">
EOF;
 
require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/csp/source/{$vulnerabilityFile}";

$page[ 'body' ] .= <<<EOF
	</div>
EOF;

$page[ 'body' ] .= "
	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://content-security-policy.com/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
