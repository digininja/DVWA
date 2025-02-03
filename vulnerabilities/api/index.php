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

if (PHP_OS == "Linux") {
	$out = shell_exec ("apachectl -M | grep rewrite_module");
	if ($out == "") {
		$html .= "<em><span class='failure'>Warning, mod_rewrite is not enabled</span></em><br>";
		$html .= "See the <a href='https://github.com/digininja/DVWA/blob/master/README.md#apache-modules'>README</a> for more information.<br>";
	}
}

if (!is_dir ("./vendor")) {
	$html .= "<em><span class='failure'>Warning, composer has not been run.</span></em><br>";
	$html .= "See the <a href='https://github.com/digininja/DVWA/blob/master/README.md#vendor-files'>README</a> for more information.<br>";
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
		<li>" . dvwaExternalLinkUrlGet( 'https://portswigger.net/bappstore/6bf7574b632847faaaa4eb5e42f1757c', "Burp OpenAPI Parser" ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.zaproxy.org/docs/desktop/addons/openapi-support/', "ZAP OpenAPI Support" ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://swagger.io/tools/swagger-ui/', "Swagger UI" ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.postman.com/', "Postman" ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>

