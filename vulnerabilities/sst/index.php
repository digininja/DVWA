<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Server Side Template Attacks' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'sst';
$page[ 'help_button' ]   = 'sst';
$page[ 'source_button' ] = 'sst';

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

if (!is_dir ("./vendor")) {
	$html .= "<em><span class='failure'>Warning, composer has not been run.</span></em><br>";
	$html .= "See the <a href='https://github.com/digininja/DVWA/blob/master/README.md#vendor-files'>README</a> for more information.<br>";
}
	

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/sst/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "<div class=\"body_padded\">
	<h1>Vulnerability: Server Side Template Attacks</h1>

	<div class=\"vulnerable_code_area\">
";

$page[ 'body' ] .= "
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://portswigger.net/research/server-side-template-injection', "Server-Side Template Injection
 - PortSwigger" ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/stable/4-Web_Application_Security_Testing/07-Input_Validation_Testing/18-Testing_for_Server-side_Template_Injection', "OWASP WSTG Testing for Server-side Template Injection" ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.smarty.net/', "Smarty Documentation" ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>

