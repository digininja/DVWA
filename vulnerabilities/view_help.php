<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'Help';

$id = $_GET[ 'id' ];
$security = $_GET[ 'security' ];

$help = file_get_contents( DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/{$id}/help/help.php" );

$page[ 'body' ] .= "
<div class=\"body_padded\">
	{$help}
</div>
";

dvwaHelpHtmlEcho( $page );

?>