<?php

if( !defined( 'DVWA_WEB_PAGE_TO_ROOT' ) ) {
	die( 'DVWA System error- WEB_PAGE_TO_ROOT undefined' );
	exit;
}

if (!file_exists(DVWA_WEB_PAGE_TO_ROOT . 'config/config.inc.php')) {
	die ("DVWA System error - config file not found. Copy config/config.inc.php.dist to config/config.inc.php and configure to your environment.");
}

// Include configs
require_once DVWA_WEB_PAGE_TO_ROOT . 'config/config.inc.php';

// Declare the $html variable
if( !isset( $html ) ) {
	$html = "";
}

// Valid security levels
$security_levels = array('low', 'medium', 'high', 'impossible');
if( !isset( $_COOKIE[ 'security' ] ) || !in_array( $_COOKIE[ 'security' ], $security_levels ) ) {
	// Set security cookie to impossible if no cookie exists
	if( in_array( $_DVWA[ 'default_security_level' ], $security_levels) ) {
		dvwaSecurityLevelSet( $_DVWA[ 'default_security_level' ] );
	} else {
		dvwaSecurityLevelSet( 'impossible' );
	}
	// If the cookie wasn't set then the session flags need updating.
	dvwa_start_session();
}

/*
 * This function is called after login and when you change the security level.
 * It gets the security level and sets the httponly and samesite cookie flags
 * appropriately.
 *
 * To force an update of the cookie flags we need to update the session id,
 * just setting the flags and doing a session_start() does not change anything.
 * For this, session_id() or session_regenerate_id() can be used.
 * Both keep the existing session values, so nothing is lost,
 * it will just cause a new Set-Cookie header to be sent with the new right
 * flags and the new id (or the same one if we wish to keep it).
*/
function dvwa_start_session() {
	// This will setup the session cookie based on
	// the security level.

	$security_level = dvwaSecurityLevelGet();
	if ($security_level == 'impossible') {
		$httponly = true;
		$samesite = "Strict";
	}
	else {
		$httponly = false;
		$samesite = "";
	}

	$maxlifetime = 86400;
	$secure = false;
	$domain = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

	/*
	 * Need to do this as you can't update the settings of a session
	 * while it is open. So check if one is open, close it if needed
	 * then update the values and start it again.
	*/
	if (session_status() == PHP_SESSION_ACTIVE) {
		session_write_close();
	}

	session_set_cookie_params([
		'lifetime' => $maxlifetime,
		'path' => '/',
		'domain' => $domain,
		'secure' => $secure,
		'httponly' => $httponly,
		'samesite' => $samesite
	]);

	/*
	 * We need to force a new Set-Cookie header with the updated flags by updating
	 * the session id, either regenerating it or setting it to a value, because
	 * session_start() might not generate a Set-Cookie header if a cookie already
	 * exists.
	 *
	 * For impossible security level, we regenerate the session id, PHP will
	 * generate a new random id. This is good security practice because it
	 * prevents the reuse of a previous unauthenticated id that an attacker
	 * might have knowledge of (aka session fixation attack).
   *
	 * For lower levels, we want to allow session fixation attacks, so if an id
	 * already exists, we don't want it to change after authentication. We thus
	 * set the id to its previous value using session_id(), which will force
	 * the Set-Cookie header.
	*/
	if ($security_level == 'impossible') {
		session_start();
		session_regenerate_id(); // force a new id to be generated
	}
	else {
		if (isset($_COOKIE[session_name()])) // if a session id already exists
			session_id($_COOKIE[session_name()]); // we keep the same id
		session_start(); // otherwise a new one will be generated here
	}
}

if (array_key_exists ("Login", $_POST) && $_POST['Login'] == "Login") {
	dvwa_start_session();
} else {
	if (!session_id()) {
		session_start();
	}
}

if (!array_key_exists ("default_locale", $_DVWA)) {
	$_DVWA[ 'default_locale' ] = "en";
}

dvwaLocaleSet( $_DVWA[ 'default_locale' ] );

// Start session functions --

function &dvwaSessionGrab() {
	if( !isset( $_SESSION[ 'dvwa' ] ) ) {
		$_SESSION[ 'dvwa' ] = array();
	}
	return $_SESSION[ 'dvwa' ];
}


function dvwaPageStartup( $pActions ) {
	if (in_array('authenticated', $pActions)) {
		if( !dvwaIsLoggedIn()) {
			dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'login.php' );
		}
	}
}

function dvwaLogin( $pUsername ) {
	$dvwaSession =& dvwaSessionGrab();
	$dvwaSession[ 'username' ] = $pUsername;
}


function dvwaIsLoggedIn() {
	global $_DVWA;

	if (in_array("disable_authentication", $_DVWA) && $_DVWA['disable_authentication']) {
		return true;
	}
	$dvwaSession =& dvwaSessionGrab();
	return isset( $dvwaSession[ 'username' ] );
}


function dvwaLogout() {
	$dvwaSession =& dvwaSessionGrab();
	unset( $dvwaSession[ 'username' ] );
}


function dvwaPageReload() {
	if  ( array_key_exists( 'HTTP_X_FORWARDED_PREFIX' , $_SERVER )) {
		dvwaRedirect( $_SERVER[ 'HTTP_X_FORWARDED_PREFIX' ] . $_SERVER[ 'PHP_SELF' ] );
	}
	else {
		dvwaRedirect( $_SERVER[ 'PHP_SELF' ] );
	}
}

function dvwaCurrentUser() {
	$dvwaSession =& dvwaSessionGrab();
	return ( isset( $dvwaSession[ 'username' ]) ? $dvwaSession[ 'username' ] : 'Unknown') ;
}

// -- END (Session functions)

function &dvwaPageNewGrab() {
	$returnArray = array(
		'title'           => 'Damn Vulnerable Web Application (DVWA)',
		'title_separator' => ' :: ',
		'body'            => '',
		'page_id'         => '',
		'help_button'     => '',
		'source_button'   => '',
	);
	return $returnArray;
}


function dvwaSecurityLevelGet() {
	global $_DVWA;

	// If there is a security cookie, that takes priority.
	if (isset($_COOKIE['security'])) {
		return $_COOKIE[ 'security' ];
	}

	// If not, check to see if authentication is disabled, if it is, use
	// the default security level.
	if (in_array("disable_authentication", $_DVWA) && $_DVWA['disable_authentication']) {
		return $_DVWA[ 'default_security_level' ];
	}

	// Worse case, set the level to impossible.
	return 'impossible';
}


function dvwaSecurityLevelSet( $pSecurityLevel ) {
	if( $pSecurityLevel == 'impossible' ) {
		$httponly = true;
	}
	else {
		$httponly = false;
	}

	setcookie( 'security', $pSecurityLevel, 0, "/", "", false, $httponly );
	$_COOKIE['security'] = $pSecurityLevel;
}

function dvwaLocaleGet() {
	$dvwaSession =& dvwaSessionGrab();
	return $dvwaSession[ 'locale' ];
}

function dvwaSQLiDBGet() {
	global $_DVWA;
	return $_DVWA['SQLI_DB'];
}

function dvwaLocaleSet( $pLocale ) {
	$dvwaSession =& dvwaSessionGrab();
	$locales = array('en', 'zh');
	if( in_array( $pLocale, $locales) ) {
		$dvwaSession[ 'locale' ] = $pLocale;
	} else {
		$dvwaSession[ 'locale' ] = 'en';
	}
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
	while( $message = dvwaMessagePop() ) {   // TODO- sharpen!
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
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'brute', 'name' => 'Brute Force', 'url' => 'vulnerabilities/brute/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'exec', 'name' => 'Command Injection', 'url' => 'vulnerabilities/exec/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'csrf', 'name' => 'CSRF', 'url' => 'vulnerabilities/csrf/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'fi', 'name' => 'File Inclusion', 'url' => 'vulnerabilities/fi/.?page=include.php' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'upload', 'name' => 'File Upload', 'url' => 'vulnerabilities/upload/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'captcha', 'name' => 'Insecure CAPTCHA', 'url' => 'vulnerabilities/captcha/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'sqli', 'name' => 'SQL Injection', 'url' => 'vulnerabilities/sqli/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'sqli_blind', 'name' => 'SQL Injection (Blind)', 'url' => 'vulnerabilities/sqli_blind/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'weak_id', 'name' => 'Weak Session IDs', 'url' => 'vulnerabilities/weak_id/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_d', 'name' => 'XSS (DOM)', 'url' => 'vulnerabilities/xss_d/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_r', 'name' => 'XSS (Reflected)', 'url' => 'vulnerabilities/xss_r/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_s', 'name' => 'XSS (Stored)', 'url' => 'vulnerabilities/xss_s/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'csp', 'name' => 'CSP Bypass', 'url' => 'vulnerabilities/csp/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'javascript', 'name' => 'JavaScript', 'url' => 'vulnerabilities/javascript/' );
		if (dvwaCurrentUser() == "admin") {
			$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'authbypass', 'name' => 'Authorisation Bypass', 'url' => 'vulnerabilities/authbypass/' );
		}
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'open_redirect', 'name' => 'Open HTTP Redirect', 'url' => 'vulnerabilities/open_redirect/' );
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
			$menuBlockHtml .= "<li class=\"{$selectedClass}\"><a href=\"{$fixedUrl}\">{$menuItem[ 'name' ]}</a></li>\n";
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
			$securityLevelHtml = 'impossible';
			break;
	}
	// -- END (security cookie)

	$userInfoHtml = '<em>Username:</em> ' . ( dvwaCurrentUser() );
	$securityLevelHtml = "<em>Security Level:</em> {$securityLevelHtml}";
	$localeHtml = '<em>Locale:</em> ' . ( dvwaLocaleGet() );
	$sqliDbHtml = '<em>SQLi DB:</em> ' . ( dvwaSQLiDBGet() );


	$messagesHtml = messagesPopAllToHtml();
	if( $messagesHtml ) {
		$messagesHtml = "<div class=\"body_padded\">{$messagesHtml}</div>";
	}

	$systemInfoHtml = "";
	if( dvwaIsLoggedIn() )
		$systemInfoHtml = "<div align=\"left\">{$userInfoHtml}<br />{$securityLevelHtml}<br />{$localeHtml}<br />{$sqliDbHtml}</div>";
	if( $pPage[ 'source_button' ] ) {
		$systemInfoHtml = dvwaButtonSourceHtmlGet( $pPage[ 'source_button' ] ) . " $systemInfoHtml";
	}
	if( $pPage[ 'help_button' ] ) {
		$systemInfoHtml = dvwaButtonHelpHtmlGet( $pPage[ 'help_button' ] ) . " $systemInfoHtml";
	}

	// Send Headers + main HTML code
	Header( 'Cache-Control: no-cache, must-revalidate');   // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );     // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );    // Date in the past

	echo "<!DOCTYPE html>

<html lang=\"en-GB\">

	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/css/main.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

		<script type=\"text/javascript\" src=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/js/dvwaPage.js\"></script>

	</head>

	<body class=\"home\">
		<div id=\"container\">

			<div id=\"header\">

				<img src=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/images/logo.png\" alt=\"Damn Vulnerable Web Application\" />

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

				<p>Damn Vulnerable Web Application (DVWA)</p>
				<script src='" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/js/add_event_listeners.js'></script>

			</div>

		</div>

	</body>

</html>";
}


function dvwaHelpHtmlEcho( $pPage ) {
	// Send Headers
	Header( 'Cache-Control: no-cache, must-revalidate');   // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );     // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );    // Date in the past

	echo "<!DOCTYPE html>

<html lang=\"en-GB\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/css/help.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

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
	Header( 'Cache-Control: no-cache, must-revalidate');   // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );     // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );    // Date in the past

	echo "<!DOCTYPE html>

<html lang=\"en-GB\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/css/source.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

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
		return '<a href="' . $pLink . '" target="_blank">' . $pLink . '</a>';
	}
	else {
		return '<a href="' . $pLink . '" target="_blank">' . $text . '</a>';
	}
}
// -- END ( external links)

function dvwaButtonHelpHtmlGet( $pId ) {
	$security = dvwaSecurityLevelGet();
	$locale = dvwaLocaleGet();
	return "<input type=\"button\" value=\"View Help\" class=\"popup_button\" id='help_button' data-help-url='" . DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/view_help.php?id={$pId}&security={$security}&locale={$locale}' )\">";
}


function dvwaButtonSourceHtmlGet( $pId ) {
	$security = dvwaSecurityLevelGet();
	return "<input type=\"button\" value=\"View Source\" class=\"popup_button\" id='source_button' data-source-url='" . DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/view_source.php?id={$pId}&security={$security}' )\">";
}


// Database Management --

if( $DBMS == 'MySQL' ) {
	$DBMS = htmlspecialchars(strip_tags( $DBMS ));
}
elseif( $DBMS == 'PGSQL' ) {
	$DBMS = htmlspecialchars(strip_tags( $DBMS ));
}
else {
	$DBMS = "No DBMS selected.";
}

function dvwaDatabaseConnect() {
	global $_DVWA;
	global $DBMS;
	//global $DBMS_connError;
	global $db;
	global $sqlite_db_connection;

	if( $DBMS == 'MySQL' ) {
		if( !@($GLOBALS["___mysqli_ston"] = mysqli_connect( $_DVWA[ 'db_server' ],  $_DVWA[ 'db_user' ],  $_DVWA[ 'db_password' ], "", $_DVWA[ 'db_port' ] ))
		|| !@((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $_DVWA[ 'db_database' ])) ) {
			//die( $DBMS_connError );
			dvwaLogout();
			dvwaMessagePush( 'Unable to connect to the database.<br />' . mysqli_error($GLOBALS["___mysqli_ston"]));
			dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'setup.php' );
		}
		// MySQL PDO Prepared Statements (for impossible levels)
		$db = new PDO('mysql:host=' . $_DVWA[ 'db_server' ].';dbname=' . $_DVWA[ 'db_database' ].';port=' . $_DVWA['db_port'] . ';charset=utf8', $_DVWA[ 'db_user' ], $_DVWA[ 'db_password' ]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
	elseif( $DBMS == 'PGSQL' ) {
		//$dbconn = pg_connect("host={$_DVWA[ 'db_server' ]} dbname={$_DVWA[ 'db_database' ]} user={$_DVWA[ 'db_user' ]} password={$_DVWA[ 'db_password' ])}"
		//or die( $DBMS_connError );
		dvwaMessagePush( 'PostgreSQL is not currently supported.' );
		dvwaPageReload();
	}
	else {
		die ( "Unknown {$DBMS} selected." );
	}

	if ($_DVWA['SQLI_DB'] == SQLITE) {
		$location = DVWA_WEB_PAGE_TO_ROOT . "database/" . $_DVWA['SQLITE_DB'];
		$sqlite_db_connection = new SQLite3($location);
		$sqlite_db_connection->enableExceptions(true);
	#	print "sqlite db setup";
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
	$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query );

	$guestbook = '';

	while( $row = mysqli_fetch_row( $result ) ) {
		if( dvwaSecurityLevelGet() == 'impossible' ) {
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
function checkToken( $user_token, $session_token, $returnURL ) {  # Validate the given (CSRF) token
	global $_DVWA;

	if (in_array("disable_authentication", $_DVWA) && $_DVWA['disable_authentication']) {
		return true;
	}

	if( $user_token !== $session_token || !isset( $session_token ) ) {
		dvwaMessagePush( 'CSRF token is incorrect' );
		dvwaRedirect( $returnURL );
	}
}

function generateSessionToken() {  # Generate a brand new (CSRF) token
	if( isset( $_SESSION[ 'session_token' ] ) ) {
		destroySessionToken();
	}
	$_SESSION[ 'session_token' ] = md5( uniqid() );
}

function destroySessionToken() {  # Destroy any session with the name 'session_token'
	unset( $_SESSION[ 'session_token' ] );
}

function tokenField() {  # Return a field for the (CSRF) token
	return "<input type='hidden' name='user_token' value='{$_SESSION[ 'session_token' ]}' />";
}
// -- END (Token functions)


// Setup Functions --
$PHPUploadPath    = realpath( getcwd() . DIRECTORY_SEPARATOR . DVWA_WEB_PAGE_TO_ROOT . "hackable" . DIRECTORY_SEPARATOR . "uploads" ) . DIRECTORY_SEPARATOR;
$PHPCONFIGPath       = realpath( getcwd() . DIRECTORY_SEPARATOR . DVWA_WEB_PAGE_TO_ROOT . "config");


$phpDisplayErrors = 'PHP function display_errors: <span class="' . ( ini_get( 'display_errors' ) ? 'success">Enabled' : 'failure">Disabled' ) . '</span>';                                                  // Verbose error messages (e.g. full path disclosure)
$phpDisplayStartupErrors = 'PHP function display_startup_errors: <span class="' . ( ini_get( 'display_startup_errors' ) ? 'success">Enabled' : 'failure">Disabled' ) . '</span>';                                                  // Verbose error messages (e.g. full path disclosure)
$phpDisplayErrors = 'PHP function display_errors: <span class="' . ( ini_get( 'display_errors' ) ? 'success">Enabled' : 'failure">Disabled' ) . '</span>';                                                  // Verbose error messages (e.g. full path disclosure)
$phpURLInclude    = 'PHP function allow_url_include: <span class="' . ( ini_get( 'allow_url_include' ) ? 'success">Enabled' : 'failure">Disabled' ) . '</span>';                                   // RFI
$phpURLFopen      = 'PHP function allow_url_fopen: <span class="' . ( ini_get( 'allow_url_fopen' ) ? 'success">Enabled' : 'failure">Disabled' ) . '</span>';                                       // RFI
$phpGD            = 'PHP module gd: <span class="' . ( ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) ? 'success">Installed' : 'failure">Missing - Only an issue if you want to play with captchas' ) . '</span>';                    // File Upload
$phpMySQL         = 'PHP module mysql: <span class="' . ( ( extension_loaded( 'mysqli' ) && function_exists( 'mysqli_query' ) ) ? 'success">Installed' : 'failure">Missing' ) . '</span>';                // Core DVWA
$phpPDO           = 'PHP module pdo_mysql: <span class="' . ( extension_loaded( 'pdo_mysql' ) ? 'success">Installed' : 'failure">Missing' ) . '</span>';                // SQLi
$DVWARecaptcha    = 'reCAPTCHA key: <span class="' . ( ( isset( $_DVWA[ 'recaptcha_public_key' ] ) && $_DVWA[ 'recaptcha_public_key' ] != '' ) ? 'success">' . $_DVWA[ 'recaptcha_public_key' ] : 'failure">Missing' ) . '</span>';

$DVWAUploadsWrite = 'Writable folder ' . $PHPUploadPath . ': <span class="' . ( is_writable( $PHPUploadPath ) ? 'success">Yes' : 'failure">No' ) . '</span>';                                     // File Upload
$bakWritable = 'Writable folder ' . $PHPCONFIGPath . ': <span class="' . ( is_writable( $PHPCONFIGPath ) ? 'success">Yes' : 'failure">No' ) . '</span>';   // config.php.bak check                                  // File Upload

$DVWAOS           = 'Operating system: <em>' . ( strtoupper( substr (PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : '*nix' ) . '</em>';
$SERVER_NAME      = 'Web Server SERVER_NAME: <em>' . $_SERVER[ 'SERVER_NAME' ] . '</em>';                                                                                                          // CSRF

$MYSQL_USER       = 'Database username: <em>' . $_DVWA[ 'db_user' ] . '</em>';
$MYSQL_PASS       = 'Database password: <em>' . ( ($_DVWA[ 'db_password' ] != "" ) ? '******' : '*blank*' ) . '</em>';
$MYSQL_DB         = 'Database database: <em>' . $_DVWA[ 'db_database' ] . '</em>';
$MYSQL_SERVER     = 'Database host: <em>' . $_DVWA[ 'db_server' ] . '</em>';
$MYSQL_PORT       = 'Database port: <em>' . $_DVWA[ 'db_port' ] . '</em>';
// -- END (Setup Functions)

?>
