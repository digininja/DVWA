<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Encryption Problems' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'encryption';
$page[ 'help_button' ]   = 'encryption';
$page[ 'source_button' ] = 'encryption';

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

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/encryption/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Encryption Problems</h1>

	<div class=\"vulnerable_code_area\">
		<p>
		You have managed to get hold of three session tokens:
		</p>
		<p>
		Sooty (admin), session expired<br />
69c4d747c94fdf98c35ddef5d2f5837234864b91766173d070ea88d65db89d49c8d096998c4ab4398461744a3b521363
		</p>
		<p>
		Sweep (user), session expired<br />
47899de18bf6d6e3f42fd4380fb2ee2534864b91766173d070ea88d65db89d49883f4cccde1544c898990b14bbd6475b
		</p>
		<p>
		Sue (user), session valid<br />
67f309a02169f997a4ba5f25a5bef16d8206f7b5b22657a68ac157eade9dc990883f4cccde1544c898990b14bbd6475b
		</p>
";
$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/xss/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/xss-filter-evasion-cheatsheet' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Cross-site_scripting' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.cgisecurity.com/xss-faq.html' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.scriptalert1.com/' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>

