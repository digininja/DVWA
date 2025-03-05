<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Cryptography Problems' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'cryptography';
$page[ 'help_button' ]   = 'cryptography';
$page[ 'source_button' ] = 'cryptography';

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

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/cryptography/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "<div class=\"body_padded\">
	<h1>Vulnerability: Cryptography Problems</h1>

	<div class=\"vulnerable_code_area\">
";

$page[ 'body' ] .= "
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://exploit-notes.hdks.org/exploit/cryptography/algorithm/aes-ecb-padding-attack', "AES-ECB Padding Attack" ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.scottbrady91.com/cryptopals/implementing-and-breaking-aes-ecb', "Implementing and breaking AES ECB") . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Block_cipher_mode_of_operation', "Wikipedia - Block cipher mode of operation" ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.nccgroup.com/us/research-blog/cryptopals-exploiting-cbc-padding-oracles/', "Cryptopals: Exploiting CBC Padding Oracles - Best article") . "</li> 
		<li>" . dvwaExternalLinkUrlGet( 'https://yurichev.org/pkcs7/', "[Crypto] PKCS#7 padding") . "</li> 
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Padding_oracle_attack', "Padding oracle attack") . "</li> 
		<li>" . dvwaExternalLinkUrlGet( 'https://medium.com/@masjadaan/oracle-padding-attack-a61369993c86', "Oracle Padding Attack") . "</li> 
		<li>" . dvwaExternalLinkUrlGet( 'https://robertheaton.com/2013/07/29/padding-oracle-attack/', "The Padding Oracle Attack") . "</li> 
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Padding_%28cryptography%29', "Wikipedia - Padding (cryptography)") . "</li> 
		<li>" . dvwaExternalLinkUrlGet( 'https://gchq.github.io/CyberChef/', "CyberChef") . "</li> 
		<li>" . dvwaExternalLinkUrlGet( 'https://www.101computing.net/xor-encryption-algorithm/', "XOR Encryption Algorithm") . "</li> 
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/XOR_cipher', "XOR Cipher") . "</li> 
		<li>" . dvwaExternalLinkUrlGet( 'https://www.youtube.com/watch?v=7WySPRERN0Q', "Video walk-through by CryptoCat") . "</li> 
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>

