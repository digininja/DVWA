<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'Setup';
$page[ 'page_id' ] = 'setup';

if( isset( $_POST[ 'create_db' ] ) ) {
	if($DBMS == 'MySQL') {
		include_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/DBMS/MySQL.php';
	}
	elseif($DBMS == 'PGSQL') {
		include_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/DBMS/PGSQL.php';
	}
	else {
		dvwaMessagePush( "ERROR: Invalid database selected. Please review the config file syntax." );
		dvwaPageReload();
	}
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Database Setup <img src=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/images/spanner.png\" /></h1>

	<p>Click on the 'Create / Reset Database' button below to create or reset your database.</br>
	If you get an error make sure you have the correct user credentials in: <em>" . realpath(  getcwd()  ) . "/config/config.inc.php</em></p>
	<p>If the database already exists, it will be cleared and the data will be reset.</p>
	<br />

	Backend Database: <em>".$DBMS."</em>
	<br /><br /><br />

	<!-- Create db button -->
	<form action=\"#\" method=\"post\">
		<input name=\"create_db\" type=\"submit\" value=\"Create / Reset Database\">
	</form>
</div>
";

dvwaHtmlEcho( $page );

?>
