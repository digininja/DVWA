<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= 'Source' . $page[ 'title_separator' ].$page[ 'title' ];

$id       = $_GET[ 'id' ];
$security = $_GET[ 'security' ];


if( $id == 'fi' ) {
	$vuln = 'File Inclusion';
}
elseif( $id == 'brute' ) {
	$vuln = 'Brute Force';
}
elseif( $id == 'csrf' ) {
	$vuln = 'CSRF';
}
elseif( $id == 'exec' ) {
	$vuln = 'Command Injection';
}
elseif( $id == 'sqli' ) {
	$vuln = 'SQL Injection';
}
elseif( $id == 'sqli_blind' ) {
	$vuln = 'SQL Injection (Blind)';
}
elseif( $id == 'upload' ) {
	$vuln = 'File Upload';
}
elseif( $id == 'xss_r' ) {
	$vuln = 'XSS (Reflected)';
}
elseif( $id == 'captcha' ) {
	$vuln = 'Insecure CAPTCHA';
}
else {
	$vuln = 'XSS (Stored)';
}

$source = @file_get_contents( DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/source/{$security}.php" );
$source = str_replace( array( '$html .=' ), array( 'echo' ), $source );

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>{$vuln} Source</h1>

	<div id=\"code\">
		<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
			<tr>
				<td><div id=\"code\">" . highlight_string( $source, true ) . "</div></td>
			</tr>
		</table>
	</div>
	<br /> <br />

	<form>
		<input type=\"button\" value=\"Compare All Levels\" onclick=\"window.location.href='view_source_all.php?id=$id'\">
	</form>
</div>\n";

dvwaSourceHtmlEcho( $page );

?>
