<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: API Security' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'api';
$page[ 'help_button' ]   = 'api';
$page[ 'source_button' ] = 'api';

dvwaDatabaseConnect();

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
		break;
}

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/api/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "<div class=\"body_padded\">
	<h1>Vulnerability: API Security</h1>

	<div class=\"vulnerable_code_area\">
";

$page[ 'body' ] .= "
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/12-API_Testing/00-API_Testing_Overview', "OWASP WSTG API Testing Overview" ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>

