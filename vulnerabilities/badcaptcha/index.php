<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';
require_once DVWA_WEB_PAGE_TO_ROOT . "external/simple-php-captcha/simple-php-captcha.php";

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Bad CAPTCHA' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'badcaptcha';
$page[ 'help_button' ]   = 'badcaptcha';
$page[ 'source_button' ] = 'badcaptcha';

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

$hide_form = false;
require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/badcaptcha/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
	<div class=\"body_padded\">
	<h1>Vulnerability: Bad CAPTCHA</h1>

	<div class=\"vulnerable_code_area\">
		<form action=\"#\" method=\"POST\" ";

if( $hide_form )
	$page[ 'body' ] .= "style=\"display:none;\"";

$page[ 'body' ] .= ">
			<h3>Please enter the CAPTCHA code to continue logged in the web site:</h3>
			<br />

			<img src=\"" . $_SESSION['captcha']['image_src'] . "\" alt=\"CAPTCHA code\">
			CAPTCHA code:<br />
			<input type=\"captcha\" AUTOCOMPLETE=\"off\" name=\"captcha\"><br />
			";

$page[ 'body' ] .= "
			<br />

			<input type=\"submit\" value=\"Submit\" name=\"Submit\">
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'http://www.captcha.net/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.google.com/recaptcha/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Testing_for_Captcha_(OWASP-AT-012)' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
