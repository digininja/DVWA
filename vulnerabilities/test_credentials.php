<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );
dvwaDatabaseConnect();

if( isset( $_POST[ 'Login' ] ) ) {

	$user = $_POST[ 'username' ];
	$user = stripslashes( $user );
	$user = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $user ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	$pass = $_POST[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $pass ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$pass = md5( $pass );

	$query = ("SELECT table_schema, table_name, create_time
				FROM information_schema.tables
				WHERE table_schema='{$_DVWA['db_database']}' AND table_name='users'
				LIMIT 1");
	$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query );

	$query  = "SELECT * FROM `users` WHERE user='$user' AND password='$pass';";
	$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '.<br />Try <a href="setup.php">installing again</a>.</pre>' );
	if( $result && mysqli_num_rows( $result ) == 1 ) {    // Login Successful...
		dvwaMessagePush( "Valid Credentials" );
		dvwaMessagePush( "You have logged in as '{$user}'" );
	}else{
		// Login failed
		dvwaMessagePush( 'Wrong Credentials' );
	}

}
$messagesHtml = messagesPopAllToHtml();
$page = dvwaPageNewGrab();

$page[ 'title' ] .= "Test Credentials";
$page[ 'body' ] .= "
		<div class=\"body_padded\">
			<h1>Test Credentials</h1>
			<h2>vulnerabilities/CSRF</h2>
			<div id=\"code\">
				<form action=\"" . DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/test_credentials.php\" method=\"post\">
					<fieldset><br />
							<label for=\"user\">Username</label><br /> <input type=\"text\" class=\"loginInput\" size=\"20\" name=\"username\"><br />
							<label for=\"pass\">Password</label><br /> <input type=\"password\" class=\"loginInput\" AUTOCOMPLETE=\"off\" size=\"20\" name=\"password\"><br />
							<br />
							<p class=\"submit\"><input type=\"submit\" value=\"Login\" name=\"Login\"></p>
					</fieldset>
				</form>
				{$messagesHtml}
			</div>
		</div>\n";

dvwaSourceHtmlEcho( $page );

?>
