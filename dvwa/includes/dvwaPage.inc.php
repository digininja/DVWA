<?php

if( !defined( 'DVWA_WEB_PAGE_TO_ROOT' ) ) {
	define( 'DVWA System error- WEB_PAGE_TO_ROOT undefined' );
	exit;
}

session_start(); // Creates a 'Full Path Disclosure' vuln.

// Include configs
require_once DVWA_WEB_PAGE_TO_ROOT.'config/config.inc.php';

require_once( 'dvwaPhpIds.inc.php' );

// Declare the $html variable
if(!isset( $html )) {
	$html = "";
}

// Valid security levels
$security_levels = array('low', 'medium', 'high');

if(!isset( $_COOKIE[ 'security' ] ) || !in_array( $_COOKIE[ 'security' ], $security_levels )) {
    // Set security cookie to high if no cookie exists
    if(in_array( $_DVWA[ 'default_security_level' ], $security_levels)) {
		dvwaSecurityLevelSet( $_DVWA[ 'default_security_level' ] );
    }
    else {
		dvwaSecurityLevelSet( 'high' );
	}
}

// DVWA version
function dvwaVersionGet() {
	return '1.8';
}

// DVWA release date
function dvwaReleaseDateGet() {
	return '11/01/2011';
}


// Start session functions --

function &dvwaSessionGrab() {
	if( !isset( $_SESSION[ 'dvwa' ] ) ) {
		$_SESSION[ 'dvwa' ] = array();
	}
	return $_SESSION[ 'dvwa' ];
}


function dvwaPageStartup( $pActions ) {
	if( in_array( 'authenticated', $pActions ) ) {
		if( !dvwaIsLoggedIn()) {
			dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT.'login.php' );
		}
	}

	if( in_array( 'phpids', $pActions ) ) {
		if( dvwaPhpIdsIsEnabled() ) {
			dvwaPhpIdsTrap();
		}
	}
}


function dvwaPhpIdsEnabledSet( $pEnabled ) {
	$dvwaSession =& dvwaSessionGrab();
	if( $pEnabled ) {
		$dvwaSession[ 'php_ids' ] = 'enabled';
	}
	else {
		unset( $dvwaSession[ 'php_ids' ] );
	}
}


function dvwaPhpIdsIsEnabled() {
	$dvwaSession =& dvwaSessionGrab();
	return isset( $dvwaSession[ 'php_ids' ] );
}


function dvwaLogin( $pUsername ) {
	$dvwaSession =& dvwaSessionGrab();
	$dvwaSession[ 'username' ] = $pUsername;
}


function dvwaIsLoggedIn() {
	$dvwaSession =& dvwaSessionGrab();
	return isset( $dvwaSession[ 'username' ] );
}


function dvwaLogout() {
	$dvwaSession =& dvwaSessionGrab();
	unset( $dvwaSession[ 'username' ] );
}


function dvwaPageReload() {
	dvwaRedirect( $_SERVER[ 'PHP_SELF' ] );
}

function dvwaCurrentUser() {
	$dvwaSession =& dvwaSessionGrab();
	return ( isset( $dvwaSession[ 'username' ]) ? $dvwaSession[ 'username' ] : '') ;
}

// -- END (Session functions)

function &dvwaPageNewGrab() {
	$returnArray = array(
		'title' => 'Damn Vulnerable Web App (DVWA) v'.dvwaVersionGet().'',
		'title_separator' => ' :: ',
		'body' => '',
		'page_id' => '',
		'help_button' => '',
		'source_button' => '',
	);
	return $returnArray;
}


function dvwaSecurityLevelGet() {
	return isset( $_COOKIE[ 'security' ] ) ? $_COOKIE[ 'security' ] : 'high';
}


function dvwaSecurityLevelSet( $pSecurityLevel ) {
	if( $pSecurityLevel == 'high' ) {
		$httponly = true;
	}
	else {
		$httponly = false;
	}
	setcookie( session_name(), session_id(), null, '/', null, null, $httponly );
	setcookie( 'security', $pSecurityLevel, NULL, NULL, NULL, NULL, $httponly );
}


// Start message functions --

function dvwaMessagePush( $pMessage ) {
	$dvwaSession =& dvwaSessionGrab();
	if( !isset( $dvwaSession[ 'messages' ] ) ) {
		$dvwaSession[ 'messages' ] = array();
	}
	$dvwaSession[ 'messages' ][] = $pMessage;
}


function dvwaMessagePop() {
	$dvwaSession =& dvwaSessionGrab();
	if( !isset( $dvwaSession[ 'messages' ] ) || count( $dvwaSession[ 'messages' ] ) == 0 ) {
		return false;
	}
	return array_shift( $dvwaSession[ 'messages' ] );
}


function messagesPopAllToHtml() {
	$messagesHtml = '';
	while( $message = dvwaMessagePop() ) {    // TODO- sharpen!
		$messagesHtml .= "<div class=\"message\">{$message}</div>";
	}

	return $messagesHtml;
}

// --END (message functions)

function dvwaHtmlEcho( $pPage ) {

	$menuBlocks = array();

	$menuBlocks[ 'home' ] = array();
	if( dvwaIsLoggedIn() ) {
		$menuBlocks[ 'home' ][] = array( 'id' => 'home', 'name' => 'Home', 'url' => '.' );
		$menuBlocks[ 'home' ][] = array( 'id' => 'instructions', 'name' => 'Instructions', 'url' => 'instructions.php' );
		$menuBlocks[ 'home' ][] = array( 'id' => 'setup', 'name' => 'Setup / Reset DB', 'url' => 'setup.php' );
	}
	else {
		$menuBlocks[ 'home' ][] = array( 'id' => 'setup', 'name' => 'Setup DVWA', 'url' => 'setup.php' );
		$menuBlocks[ 'home' ][] = array( 'id' => 'instructions', 'name' => 'Instructions', 'url' => 'instructions.php' );
	}

	if( dvwaIsLoggedIn() ) {
		$menuBlocks[ 'vulnerabilities' ] = array();
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'brute', 'name' => 'Brute Force', 'url' => 'vulnerabilities/brute/.' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'exec', 'name' => 'Command Execution', 'url' => 'vulnerabilities/exec/.' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'csrf', 'name' => 'CSRF', 'url' => 'vulnerabilities/csrf/.' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'fi', 'name' => 'File Inclusion', 'url' => 'vulnerabilities/fi/.?page=include.php' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'upload', 'name' => 'File Upload', 'url' => 'vulnerabilities/upload/.' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'captcha', 'name' => 'Insecure CAPTCHA', 'url' => 'vulnerabilities/captcha/.' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'sqli', 'name' => 'SQL Injection', 'url' => 'vulnerabilities/sqli/.' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'sqli_blind', 'name' => 'SQL Injection (Blind)', 'url' => 'vulnerabilities/sqli_blind/.' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_r', 'name' => 'XSS (Reflected)', 'url' => 'vulnerabilities/xss_r/.' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_s', 'name' => 'XSS (Stored)', 'url' => 'vulnerabilities/xss_s/.' );
	}

	$menuBlocks[ 'meta' ] = array();
	if( dvwaIsLoggedIn() ) {
		$menuBlocks[ 'meta' ][] = array( 'id' => 'security', 'name' => 'DVWA Security', 'url' => 'security.php' );
		$menuBlocks[ 'meta' ][] = array( 'id' => 'phpinfo', 'name' => 'PHP Info', 'url' => 'phpinfo.php' );
	}
	$menuBlocks[ 'meta' ][] = array( 'id' => 'about', 'name' => 'About', 'url' => 'about.php' );

	if( dvwaIsLoggedIn() ) {
		$menuBlocks[ 'logout' ] = array();
		$menuBlocks[ 'logout' ][] = array( 'id' => 'logout', 'name' => 'Logout', 'url' => 'logout.php' );
	}

	$menuHtml = '';

	foreach( $menuBlocks as $menuBlock ) {
		$menuBlockHtml = '';
		foreach( $menuBlock as $menuItem ) {
			$selectedClass = ( $menuItem[ 'id' ] == $pPage[ 'page_id' ] ) ? 'selected' : '';
			$fixedUrl = DVWA_WEB_PAGE_TO_ROOT.$menuItem[ 'url' ];
			$menuBlockHtml .= "<li onclick=\"window.location='{$fixedUrl}'\" class=\"{$selectedClass}\"><a href=\"{$fixedUrl}\">{$menuItem[ 'name' ]}</a></li>\n";
		}
		$menuHtml .= "<ul class=\"menuBlocks\">{$menuBlockHtml}</ul>";
	}

    // Get security cookie --
	$securityLevelHtml = '';
	switch( dvwaSecurityLevelGet() ) {
		case 'low':
			$securityLevelHtml = 'low';
			break;

		case 'medium':
			$securityLevelHtml = 'medium';
			break;

		case 'high':
			$securityLevelHtml = 'high';
			break;
		default:
			$securityLevelHtml = 'high';
			break;
	}
    // -- END (security cookie)

	$phpIdsHtml   = '<em>PHPIDS:</em> '.( dvwaPhpIdsIsEnabled() ? 'enabled' : 'disabled' );
	$userInfoHtml = '<em>Username:</em> '.( dvwaCurrentUser() );

	$messagesHtml = messagesPopAllToHtml();
	if( $messagesHtml ) {
		$messagesHtml = "<div class=\"body_padded\">{$messagesHtml}</div>";
	}

	$systemInfoHtml = "";
	if( dvwaIsLoggedIn() )
		$systemInfoHtml = "<div align=\"left\">{$userInfoHtml}<br /><b>Security Level:</b> {$securityLevelHtml}<br />{$phpIdsHtml}</div>";
	if( $pPage[ 'source_button' ] ) {
		$systemInfoHtml = dvwaButtonSourceHtmlGet( $pPage[ 'source_button' ] )." $systemInfoHtml";
	}
	if( $pPage[ 'help_button' ] ) {
		$systemInfoHtml = dvwaButtonHelpHtmlGet( $pPage[ 'help_button' ] )." $systemInfoHtml";
	}

    // Send Headers + main HTML code
	Header( 'Cache-Control: no-cache, must-revalidate');    // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );	    // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );	    // Date in the past

	echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\">

	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/css/main.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"".DVWA_WEB_PAGE_TO_ROOT."favicon.ico\" />

		<script type=\"text/javascript\" src=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/js/dvwaPage.js\"></script>

	</head>

	<body class=\"home\">
		<div id=\"container\">

			<div id=\"header\">

				<img src=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/images/logo.png\" alt=\"Damn Vulnerable Web App\" />

			</div>

			<div id=\"main_menu\">

				<div id=\"main_menu_padded\">
				{$menuHtml}
				</div>

			</div>

			<div id=\"main_body\">

				{$pPage[ 'body' ]}
				<br /><br />
				{$messagesHtml}

			</div>

			<div class=\"clear\">
			</div>

			<div id=\"system_info\">
				{$systemInfoHtml}
			</div>

			<div id=\"footer\">

				<p>Damn Vulnerable Web Application (DVWA) v".dvwaVersionGet()."</p>

			</div>

		</div>

	</body>

</html>";
}


function dvwaHelpHtmlEcho( $pPage ) {
    // Send Headers
	Header( 'Cache-Control: no-cache, must-revalidate');    // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );	    // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );	    // Date in the past

	echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/css/help.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"".DVWA_WEB_PAGE_TO_ROOT."favicon.ico\" />

	</head>

	<body>

	<div id=\"container\">

			{$pPage[ 'body' ]}

		</div>

	</body>

</html>";
}


function dvwaSourceHtmlEcho( $pPage ) {
    // Send Headers
	Header( 'Cache-Control: no-cache, must-revalidate');    // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );	    // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );	    // Date in the past

	echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/css/source.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"".DVWA_WEB_PAGE_TO_ROOT."favicon.ico\" />

	</head>

	<body>

		<div id=\"container\">

			{$pPage[ 'body' ]}

		</div>

	</body>

</html>";
}

// To be used on all external links --
function dvwaExternalLinkUrlGet( $pLink,$text=null ) {
	if(is_null( $text )) {
		return '<a href="http://hiderefer.com/?'.$pLink.'" target="_blank">'.$pLink.'</a>';
	}
	else {
		return '<a href="http://hiderefer.com/?'.$pLink.'" target="_blank">'.$text.'</a>';
	}
}
// -- END ( external links)

function dvwaButtonHelpHtmlGet( $pId ) {
	$security = dvwaSecurityLevelGet();
	return "<input type=\"button\" value=\"View Help\" class=\"popup_button\" onClick=\"javascript:popUp( '".DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/view_help.php?id={$pId}&security={$security}' )\">";
}


function dvwaButtonSourceHtmlGet( $pId ) {
	$security = dvwaSecurityLevelGet();
	return "<input type=\"button\" value=\"View Source\" class=\"popup_button\" onClick=\"javascript:popUp( '".DVWA_WEB_PAGE_TO_ROOT."vulnerabilities/view_source.php?id={$pId}&security={$security}' )\">";
}


// Database Management --

if( $DBMS == 'MySQL' ) {
	$DBMS = htmlspecialchars(strip_tags( $DBMS ));
	$DBMS_errorFunc = 'mysql_error()';
}
elseif( $DBMS == 'PGSQL' ) {
	$DBMS = htmlspecialchars(strip_tags( $DBMS ));
	$DBMS_errorFunc = 'pg_last_error()';
}
else {
	$DBMS = "No DBMS selected.";
	$DBMS_errorFunc = '';
}

$DBMS_connError = '
	<div align="center">
		<img src="'.DVWA_WEB_PAGE_TO_ROOT.'dvwa/images/logo.png" />
		<pre>Unable to connect to the database.<br />'.$DBMS_errorFunc.'<br /><br /></pre>
		Click <a href="'.DVWA_WEB_PAGE_TO_ROOT.'setup.php">here</a> to setup the database.
	</div>';

function dvwaDatabaseConnect() {
	global $_DVWA;
	global $DBMS;
	global $DBMS_connError;
	global $db;

	if( $DBMS == 'MySQL' ) {
		if( !@mysql_connect( $_DVWA[ 'db_server' ], $_DVWA[ 'db_user' ], $_DVWA[ 'db_password' ] )
		|| !@mysql_select_db( $_DVWA[ 'db_database' ] ) ) {
			//die( $DBMS_connError );
			dvwaMessagePush( $DBMS_connError );
			dvwaRedirect( 'setup.php' );
		}
		// MySQL PDO Prepared Statements (high levels)
		$db = new PDO('mysql:host='.$_DVWA[ 'db_server' ].';dbname='.$_DVWA[ 'db_database' ].';charset=utf8', $_DVWA[ 'db_user' ], $_DVWA[ 'db_password' ]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
	elseif ( $DBMS == 'PGSQL' ) {
		//$dbconn = pg_connect("host=".$_DVWA[ 'db_server' ]." dbname=".$_DVWA[ 'db_database' ]." user=".$_DVWA[ 'db_user' ]." password=".$_DVWA[ 'db_password' ])
		//or die( $DBMS_connError );
		dvwaMessagePush( 'PostgreSQL is not yet fully supported.' );
		dvwaPageReload();
	}
	else {
		die ( 'Unknown $DBMS selected' );
	}
}

// -- END (Database Management)


function dvwaRedirect( $pLocation ) {
	session_commit();
	header( "Location: {$pLocation}" );
	exit;
}

// XSS Stored guestbook function --
function dvwaGuestbook() {
	$query  = "SELECT name, comment FROM guestbook";
	$result = mysql_query( $query );

	$guestbook = '';

	while( $row = mysql_fetch_row( $result ) ) {
		if( dvwaSecurityLevelGet() == 'high' ) {
			$name    = htmlspecialchars( $row[0] );
			$comment = htmlspecialchars( $row[1] );
		}
		else {
			$name    = $row[0];
			$comment = $row[1];
		}

		$guestbook .= "<div id=\"guestbook_comments\">Name: {$name}<br />" . "Message: {$comment}<br /></div>\n";
	}
	return $guestbook;
}
// -- END (XSS Stored guestbook)


// Token functions --
function generateTokens() {  # Generate a brand new TOKEN
	if( isset( $_SESSION[ 'user_token' ] ) ) {
		destroyTokens( $_SESSION[ 'user_token' ] );
	}
	$_SESSION[ 'user_token' ] = md5( uniqid() );
}

function checkTokens( $token , $returnURL ) {  # Validate the Given TOKEN
	if( $token !== $_SESSION[ 'user_token' ] ) {
		dvwaRedirect( $returnURL );
	}
}

function destroyTokens( $token ) {  # Destroy any session with the name 'User_token'
	unset( $_SESSION['user_token'] );
}

function tokenField() {  # Return a field for the token
	return "<input type='hidden' name='token' value='" . $_SESSION[ 'user_token' ] . "' />";
}
// -- END (Token functions)


$phpSafeMode      = 'PHP safe mode: <em>' . ( ini_get( 'safe_mode' )  ? 'Enabled' : 'Disabled' ) . '</em>';
$phpDisplayErrors = 'PHP display errors: <em>'.( ini_get( 'display_errors' )  ? 'Enabled</em> <i>(Easy Mode!)</i>' : 'Disabled</em>' );
$phpURLInclude    = 'PHP allow URL Include: <em>'.( ini_get( 'allow_url_include' )  ? 'Enabled' : 'Disabled' ) . '</em>';
$phpURLFopen      = 'PHP allow URL fopen: <em>'.( ini_get( 'allow_url_fopen' )  ? 'Enabled' : 'Disabled' ) . '</em>';
$DVWARecaptcha    = 'reCAPTCHA key: <em>' . ( isset ( $_DVWA[ 'recaptcha_public_key' ] ) ? $_DVWA[ 'recaptcha_public_key' ] : 'Missing(*)' ) . '</em>';
$DVWAUploadsWrite = 'Writable "/hackable/uploads/": <em>' . ( is_writable( realpath( dirname( dirname( getcwd() ) ) )."/hackable/uploads/" ) ? 'Yes' : 'No(*)' ) . '</em>';
$DVWAPHPWrite     = 'Writable "/external/phpids/0.6/lib/IDS/tmp": <em>' . ( is_writable( realpath( dirname( dirname( getcwd() ) ) )."external/phpids/0.6/lib/IDS/tmp" ) ? 'Yes' : 'No(*)' ) . '</em>';
$DVWAOS           = 'Operating system: <em>' . ( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : '*nix' ) . '</em>';

?>
