<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] = 'SQL Injection Session Input' . $page[ 'title_separator' ].$page[ 'title' ];

if( isset( $_POST[ 'id' ] ) ) {
	$_SESSION[ 'id' ] =  $_POST[ 'id' ];
	//$page[ 'body' ] .= "Session ID set!<br /><br /><br />";
	$page[ 'body' ] .= "Session ID: {$_SESSION[ 'id' ]}<br /><br /><br />";
	$page[ 'body' ] .= "<script>window.opener.location.reload(true);</script>";
}

$page[ 'body' ] .= "
<form action=\"#\" method=\"POST\">
	<input type=\"text\" size=\"15\" name=\"id\">
	<input type=\"submit\" name=\"Submit\" value=\"Submit\">
</form>
<hr />
<br />

<button onclick=\"self.close();\">Close</button>";

dvwaSourceHtmlEcho( $page );

?>


