<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Open HTTP Redirect' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'open_redirect';
$page[ 'help_button' ]   = 'open_redirect';
$page[ 'source_button' ] = 'open_redirect';
dvwaDatabaseConnect();

switch( dvwaSecurityLevelGet() ) {
	case 'low':
		$link1 = "source/low.php?redirect=info.php?id=1";
		$link2 = "source/low.php?redirect=info.php?id=2";
		break;
	case 'medium':
		$link1 = "source/medium.php?redirect=info.php?id=1";
		$link2 = "source/medium.php?redirect=info.php?id=2";
		break;
	case 'high':
		$link1 = "source/high.php?redirect=info.php?id=1";
		$link2 = "source/high.php?redirect=info.php?id=2";
		break;
	default:
		$link1 = "source/impossible.php?redirect=1";
		$link2 = "source/impossible.php?redirect=2";
		break;
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Open HTTP Redirect</h1>

	<div class=\"vulnerable_code_area\">
		<h2>Hacker History</h2>
		<p>
			Here are two links to some famous hacker quotes, see if you can hack them.
		</p>
		<ul>
			<li><a href='{$link1}'>Quote 1</a></li>
			<li><a href='{$link2}'>Quote 2</a></li>
		</ul>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://cheatsheetseries.owasp.org/cheatsheets/Unvalidated_Redirects_and_Forwards_Cheat_Sheet.html', "OWASP Unvalidated Redirects and Forwards Cheat Sheet" ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/stable/4-Web_Application_Security_Testing/11-Client-side_Testing/04-Testing_for_Client-side_URL_Redirect', "WSTG - Testing for Client-side URL Redirect") . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://cwe.mitre.org/data/definitions/601.html', "Mitre - CWE-601: URL Redirection to Untrusted Site ('Open Redirect')" ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
