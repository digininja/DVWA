<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'Vulnerability: File Upload';
$page[ 'page_id' ] = 'upload';

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

require_once DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/upload/source/{$vulnerabilityFile}";

$page[ 'help_button' ] = 'upload';
$page[ 'source_button' ] = 'upload';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: File Upload</h1>

	<div class=\"vulnerable_code_area\">

		<form enctype=\"multipart/form-data\" action=\"#\" method=\"POST\" />
			<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000\" />
			Choose an image to upload:
			<br />
			<input name=\"uploaded\" type=\"file\" /><br />
			<br />
			<input type=\"submit\" name=\"Upload\" value=\"Upload\" />
		</form>

		{$html}

	</div>
	
	<h2>More info</h2>
	<ul>
		<li>".dvwaExternalLinkUrlGet( 'http://www.owasp.org/index.php/Unrestricted_File_Upload')."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://blogs.securiteam.com/index.php/archives/1268')."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://www.acunetix.com/websitesecurity/upload-forms-threat.htm')."</li>
	</ul>

</div>

";

dvwaHtmlEcho( $page );

?>