<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'Vulnerability: File Inclusion';
$page[ 'page_id' ] = 'fi';

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( $_COOKIE['security'] ) {
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

require_once DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/fi/source/{$vulnerabilityFile}";

$page[ 'help_button' ] = 'fi';
$page[ 'source_button' ] = 'fi';

include($file);

dvwaHtmlEcho( $page );

?>