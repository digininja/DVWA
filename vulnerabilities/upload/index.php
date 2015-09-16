<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]  .= $page[ 'title_separator' ].'Vulnerability: File Upload';
$page[ 'page_id' ] = 'upload';
$page[ 'help_button' ]   = 'upload';
$page[ 'source_button' ] = 'upload';

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

// Anti-CSRF
if( $vulnerabilityFile == 'high.php' )
	generateTokens();

require_once DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/upload/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: File Upload</h1>

	<div class=\"vulnerable_code_area\">";
if( is_writable( realpath( dirname( dirname( getcwd() ) ) )."/hackable/uploads/" ) ) {
	$page[ 'body' ] .= "
		<form enctype=\"multipart/form-data\" action=\"#\" method=\"POST\" />
			<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000\" />
			Choose an image to upload:<br /><br />
			<input name=\"uploaded\" type=\"file\" /><br />
			<br />
			<input type=\"submit\" name=\"Upload\" value=\"Upload\" />";

if( $vulnerabilityFile == 'high.php' )
	$page[ 'body' ] .= "			" . tokenField();

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>".dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Unrestricted_File_Upload' )."</li>
		<li>".dvwaExternalLinkUrlGet( 'https://blogs.securiteam.com/index.php/archives/1268' )."</li>
		<li>".dvwaExternalLinkUrlGet( 'https://www.acunetix.com/websitesecurity/upload-forms-threat/' )."</li>
	</ul>
";
}
else {
	$page[ 'body' ] .= "
	The upload folder (<em>".realpath( dirname( dirname( getcwd() ) ) )."/hackable/uploads/"."</em>) cannot be written to by the web server.<br />
	Please alter the folder permissions before continuing.
";
}
$page[ 'body' ] .= "</div>";


dvwaHtmlEcho( $page );

?>
