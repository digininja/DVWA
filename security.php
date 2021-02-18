<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'DVWA Security' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'security new';

$securityHtml = '';
if( isset( $_POST['seclev_submit'] ) ) {
	// Anti-CSRF
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'security.php' );

	$securityLevel = '';
	switch( $_POST[ 'security' ] ) {
		case 'low':
			$securityLevel = 'low';
			break;
		case 'medium':
			$securityLevel = 'medium';
			break;
		case 'high':
			$securityLevel = 'high';
			break;
		default:
			$securityLevel = 'impossible';
			break;
	}

	dvwaSecurityLevelSet( $securityLevel );
	dvwaMessagePush( "Security level set to {$securityLevel}" );
	dvwaPageReload();
}

if( isset( $_GET['phpids'] ) ) {
	switch( $_GET[ 'phpids' ] ) {
		case 'on':
			dvwaPhpIdsEnabledSet( true );
			dvwaMessagePush( "PHPIDS is now enabled" );
			break;
		case 'off':
			dvwaPhpIdsEnabledSet( false );
			dvwaMessagePush( "PHPIDS is now disabled" );
			break;
	}

	dvwaPageReload();
}

$securityOptionsHtml = '';
$securityLevelHtml   = '';
foreach( array( 'low', 'medium', 'high', 'impossible' ) as $securityLevel ) {
	$selected = '';
	if( $securityLevel == dvwaSecurityLevelGet() ) {
		$selected = ' selected="selected"';
		$securityLevelHtml = "<p>Security level is currently: <em>$securityLevel</em>.<p>";
	}
	$securityOptionsHtml .= "<option value=\"{$securityLevel}\"{$selected}>" . ucfirst($securityLevel) . "</option>";
}

$phpIdsHtml = 'PHPIDS is currently: ';

// Able to write to the PHPIDS log file?
$WarningHtml = '';

if( dvwaPhpIdsIsEnabled() ) {
	$phpIdsHtml .= '<em>enabled</em>. [<a href="?phpids=off">Disable PHPIDS</a>]';

	# Only check if PHPIDS is enabled
	if( !is_writable( $PHPIDSPath ) ) {
		$WarningHtml .= "<div class=\"warning\"><em>Cannot write to the PHPIDS log file</em>: ${PHPIDSPath}</div>";
	}
}
else {
	$phpIdsHtml .= '<em>disabled</em>. [<a href="?phpids=on">Enable PHPIDS</a>]';
}

// Anti-CSRF
generateSessionToken();

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>DVWA Security <img src=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/images/lock.png\" /></h1>
	<br />

	<h2>Security Level</h2>

	{$securityHtml}

	<form action=\"#\" method=\"POST\">
		{$securityLevelHtml}
		<p>You can set the security level to low, medium, high or impossible. The security level changes the vulnerability level of DVWA:</p>
		<ol>
			<li> Low - This security level is completely vulnerable and <em>has no security measures at all</em>. It's use is to be as an example of how web application vulnerabilities manifest through bad coding practices and to serve as a platform to teach or learn basic exploitation techniques.</li>
			<li> Medium - This setting is mainly to give an example to the user of <em>bad security practices</em>, where the developer has tried but failed to secure an application. It also acts as a challenge to users to refine their exploitation techniques.</li>
			<li> High - This option is an extension to the medium difficulty, with a mixture of <em>harder or alternative bad practices</em> to attempt to secure the code. The vulnerability may not allow the same extent of the exploitation, similar in various Capture The Flags (CTFs) competitions.</li>
			<li> Impossible - This level should be <em>secure against all vulnerabilities</em>. It is used to compare the vulnerable source code to the secure source code.<br />
				Prior to DVWA v1.9, this level was known as 'high'.</li>
		</ol>
		<select name=\"security\">
			{$securityOptionsHtml}
		</select>
		<input type=\"submit\" value=\"Submit\" name=\"seclev_submit\">
		" . tokenField() . "
	</form>

	<br />
	<hr />
	<br />

	<h2>PHPIDS</h2>
	{$WarningHtml}
	<p>" . dvwaExternalLinkUrlGet( 'https://github.com/PHPIDS/PHPIDS', 'PHPIDS' ) . " v" . dvwaPhpIdsVersionGet() . " (PHP-Intrusion Detection System) is a security layer for PHP based web applications.</p>
	<p>PHPIDS works by filtering any user supplied input against a blacklist of potentially malicious code. It is used in DVWA to serve as a live example of how Web Application Firewalls (WAFs) can help improve security and in some cases how WAFs can be circumvented.</p>
	<p>You can enable PHPIDS across this site for the duration of your session.</p>

	<p>{$phpIdsHtml}</p>
	[<a href=\"?test=%22><script>eval(window.name)</script>\">Simulate attack</a>] -
	[<a href=\"ids_log.php\">View IDS log</a>]
</div>";

dvwaHtmlEcho( $page );

?>
