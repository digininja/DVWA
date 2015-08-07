<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'DVWA Security';
$page[ 'page_id' ] = 'security';

$securityHtml = '';
if( isset( $_POST['seclev_submit'] ) ) {
	$securityLevel = 'high';

	switch( $_POST[ 'security' ] ) {
		case 'low':
			$securityLevel = 'low';
			break;
		case 'medium':
			$securityLevel = 'medium';
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
$securityLevelHtml = '';
foreach( array( 'low', 'medium', 'high' ) as $securityLevel ) {
	$selected = '';
	if( $securityLevel == dvwaSecurityLevelGet() ) {
		$selected = ' selected="selected"';
		$securityLevelHtml = "<p>Security Level is currently <em>$securityLevel</em>.<p>";
	}
	$securityOptionsHtml .= "<option value=\"{$securityLevel}\"{$selected}>{$securityLevel}</option>";
}

$phpIdsHtml = 'PHPIDS is currently ';
if( dvwaPhpIdsIsEnabled() ) {
	$phpIdsHtml .= '<em>enabled</em>. [<a href="?phpids=off">disable PHPIDS</a>]';
} else {
	$phpIdsHtml .= '<em>disabled</em>. [<a href="?phpids=on">enable PHPIDS</a>]';
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>DVWA Security <img src=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/images/lock.png\"></h1>

	<br />
	
	<h2>Script Security</h2>

	{$securityHtml}

	<form action=\"#\" method=\"POST\">
		{$securityLevelHtml}
		<p>You can set the security level to low, medium or high.</p>
		<p>The security level changes the vulnerability level of DVWA.</p>

		<select name=\"security\">
			{$securityOptionsHtml}
		</select>
		<input type=\"submit\" value=\"Submit\" name=\"seclev_submit\">
	</form>

	<br />
	<hr />
	<br />

	<h2>PHPIDS</h2>

	<p>".dvwaExternalLinkUrlGet( 'http://php-ids.org/', 'PHPIDS' )." v.".dvwaPhpIdsVersionGet()." (PHP-Intrusion Detection System) is a security layer for PHP based web applications. </p>
	<p>You can enable PHPIDS across this site for the duration of your session.</p>

	<p>{$phpIdsHtml}</p>
	[<a href=\"?test=%22><script>eval(window.name)</script>\">Simulate attack</a>] -
	[<a href=\"ids_log.php\">View IDS log</a>]
	
</div>
";


dvwaHtmlEcho( $page );

?>
