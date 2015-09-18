<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );

require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'phpids' ) );

dvwaDatabaseConnect();

if( isset( $_POST[ 'Login' ] ) ) {
	// Anti-CSRF
	checkTokens( $_REQUEST[ 'user_token' ], 'index.php' );

	$user = $_POST[ 'username' ];
	$user = stripslashes( $user );
	$user = mysql_real_escape_string( $user );

	$pass = $_POST[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = mysql_real_escape_string( $pass );
	$pass = md5( $pass );

	$query = ("SELECT table_schema, table_name, create_time
				FROM information_schema.tables
				WHERE table_schema='{$_DVWA['db_database']}' AND table_name='users'
				LIMIT 1");
	$result = @mysql_query( $query );
	if( mysql_num_rows( $result ) != 1 ) {
		dvwaMessagePush( "First time.<br />Need to run 'setup.php'." );
		dvwaRedirect( 'setup.php' );
	}

	$query  = "SELECT * FROM `users` WHERE user='$user' AND password='$pass';";
	$result = @mysql_query( $query ) or die( '<pre>' . mysql_error() . '.<br />Try <a href="setup.php">installing again</a>.</pre>' );
	if( $result && mysql_num_rows( $result ) == 1 ) {    // Login Successful...
		dvwaMessagePush( "You have logged in as '{$user}'" );
		dvwaLogin( $user );
		dvwaRedirect( 'index.php' );
	}

    // Login failed
	dvwaMessagePush( 'Login failed' );
	dvwaRedirect( 'login.php' );
}

$messagesHtml = messagesPopAllToHtml();

Header( 'Cache-Control: no-cache, must-revalidate');    // HTTP/1.1
Header( 'Content-Type: text/html;charset=utf-8' );	    // TODO- proper XHTML headers...
Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );	    // Date in the past

// Anti-CSRF
generateTokens();

echo "

<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>Login :: Damn Vulnerable Web App (DVWA) v" . dvwaVersionGet() . "</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/css/login.css\" />

	</head>

	<body>

	<div align=\"center\">

	<br />

	<p><img src=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/images/login_logo.png\" /></p>

	<br />

	<form action=\"login.php\" method=\"post\">

	<fieldset>

			<label for=\"user\">Username</label> <input type=\"text\" class=\"loginInput\" size=\"20\" name=\"username\"><br />


			<label for=\"pass\">Password</label> <input type=\"password\" class=\"loginInput\" AUTOCOMPLETE=\"off\" size=\"20\" name=\"password\"><br />


			<p class=\"submit\"><input type=\"submit\" value=\"Login\" name=\"Login\"></p>

	</fieldset>

	".tokenField()."

	</form>


	<br />

	{$messagesHtml}

	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />

	<!-- <img src=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/images/RandomStorm.png\" /> -->

	<p>Damn Vulnerable Web Application (DVWA) is a RandomStorm OpenSource project</p>

	</div> <!-- end align div -->

	</body>

</html>
";

?>
