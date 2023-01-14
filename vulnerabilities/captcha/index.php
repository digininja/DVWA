<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';
require_once DVWA_WEB_PAGE_TO_ROOT . "external/recaptcha/recaptchalib.php";

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Insecure CAPTCHA' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'captcha';
$page[ 'help_button' ]   = 'captcha';
$page[ 'source_button' ] = 'captcha';

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

$hide_form = false;
require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/captcha/source/{$vulnerabilityFile}";

// Check if we have a reCAPTCHA key
$WarningHtml = '';
if( $_DVWA[ 'recaptcha_public_key' ] == "" ) {
	$WarningHtml = "<div class=\"warning\"><em>reCAPTCHA API key missing</em> from config file: " . realpath( getcwd() . DIRECTORY_SEPARATOR . DVWA_WEB_PAGE_TO_ROOT . "config" . DIRECTORY_SEPARATOR . "config.inc.php" ) . "</div>";
	$html = "<em>Please register for a key</em> from reCAPTCHA: " . dvwaExternalLinkUrlGet( 'https://www.google.com/recaptcha/admin/create' );
	$hide_form = true;
}

$page[ 'body' ] .= "
	<div class=\"body_padded\">
	<h1>Vulnerability: Insecure CAPTCHA</h1>

	{$WarningHtml}

	<div class=\"vulnerable_code_area\">
		<form action=\"#\" method=\"POST\" ";

if( $hide_form )
	$page[ 'body' ] .= "style=\"display:none;\"";

$page[ 'body' ] .= ">
			<h3>Change your password:</h3>
			<br />

			<input type=\"hidden\" name=\"step\" value=\"1\" />\n";

if( $vulnerabilityFile == 'impossible.php' ) {
	$page[ 'body' ] .= "
			Current password:<br />
			<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_current\"><br />";
}

$page[ 'body' ] .= "			New password:<br />
			<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_new\"><br />
			Confirm new password:<br />
			<input type=\"password\" AUTOCOMPLETE=\"off\" name=\"password_conf\"><br />

			" . recaptcha_get_html( $_DVWA[ 'recaptcha_public_key' ] );
if( $vulnerabilityFile == 'high.php' )
	$page[ 'body' ] .= "\n\n			<!-- **DEV NOTE**   Response: 'hidd3n_valu3'   &&   User-Agent: 'reCAPTCHA'   **/DEV NOTE** -->\n";

if( $vulnerabilityFile == 'high.php' || $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "\n			" . tokenField();

$page[ 'body' ] .= "
			<br />

			<input type=\"submit\" value=\"Change\" name=\"Change\">
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/CAPTCHA' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.google.com/recaptcha/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-automated-threats-to-web-applications/assets/oats/EN/OAT-009_CAPTCHA_Defeat' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
