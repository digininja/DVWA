<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'Vulnerability: Reflected Cross Site Scripting (XSS)';
$page[ 'page_id' ] = 'xss_r';

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

require_once DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/xss_r/source/{$vulnerabilityFile}";

$page[ 'help_button' ] = 'xss_r';
$page[ 'source_button' ] = 'xss_r';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Reflected Cross Site Scripting (XSS)</h1>

	<div class=\"vulnerable_code_area\">

		<form name=\"XSS\" action=\"#\" method=\"GET\">
			<p>What's your name?</p>
			<input type=\"text\" name=\"name\">
			<input type=\"submit\" value=\"Submit\">
		</form>

		{$html}

	</div>

	<h2>More info</h2>

	<ul>
		<li>".dvwaExternalLinkUrlGet( 'http://ha.ckers.org/xss.html')."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://en.wikipedia.org/wiki/Cross-site_scripting')."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://www.cgisecurity.com/xss-faq.html')."</li>
	</ul>
</div>
";

dvwaHtmlEcho( $page );

?>