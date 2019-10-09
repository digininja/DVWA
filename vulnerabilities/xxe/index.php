<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'XML external identity (XXE)' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'xxe';
$page[ 'help_button' ]   = 'xxe';
$page[ 'source_button' ] = 'xxe';

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

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/xxe/source/{$vulnerabilityFile}";


$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>XML external identity (XXE)</h1>

	<div class=\"vulnerable_code_area\">
		<form enctype=\"multipart/form-data\" action=\"#\" method=\"POST\">
			Choose an XML file to upload:<br /><br />
			<input name=\"uploaded\" type=\"file\" /><br />
			<br />
			<input type=\"submit\" name=\"Upload\" value=\"Upload\" />
		</form>
	</div>
	
	<div>
	<br />" . XXE() . "<br />
	</div>
		<br />
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Top_10-2017_A4-XML_External_Entities_(XXE)' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/XML_External_Entity_(XXE)_Processing' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Missing_XML_Validation' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
