<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Open HTTP Redirect' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'open_redirect';
$page[ 'help_button' ]   = 'open_redirect';
$page[ 'source_button' ] = 'open_redirect';
dvwaDatabaseConnect();

$method            = 'GET';
$vulnerabilityFile = '';
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
		<h2>Change Pages</h2>
		<p>
			Here are two redirects to get you started.
		</p>
		<ul>
			<li><a href='{$link1}'>Info 1</a></li>
			<li><a href='{$link2}'>Info 2</a></li>
		</ul>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/Brute_force_attack' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'http://www.symantec.com/connect/articles/password-crackers-ensuring-security-your-password' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'http://www.sillychicken.co.nz/Security/how-to-brute-force-http-forms-in-windows.html' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
