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

$page[ 'body' ] .= "<div class=\"body_padded\">
	<h1>Vulnerability: Encryption Problems</h1>

	<div class=\"vulnerable_code_area\">
";

$page[ 'body' ] .= $content;

$page[ 'body' ] .= "
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://exploit-notes.hdks.org/exploit/cryptography/algorithm/aes-ecb-padding-attack' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.scottbrady91.com/cryptopals/implementing-and-breaking-aes-ecb' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Block_cipher_mode_of_operation' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>

