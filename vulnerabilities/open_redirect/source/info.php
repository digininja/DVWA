<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Open HTTP Redirect' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'open_redirect';
$page[ 'help_button' ]   = 'open_redirect';
$page[ 'source_button' ] = 'open_redirect';
dvwaDatabaseConnect();

$info = "";

if (array_key_exists ("id", $_GET) && is_numeric($_GET['id'])) {
	switch (intval ($_GET['id'])) {
		case 1:
			$info = "Why did he come to you?<br />I got a record, I was Zero Cool<br />Zero Cool. Crashed 1507 systems in one day, biggest crash in history, front page, New York Times August 10th 1988.";
			break;
		case 2:
			$info = "Who are you anyway?<br />Johnny.<br />Johnny who?<br />Just... Johnny?";
			break;
		default:
			$info = "Some other stuff";
	}
}

if ($info == "") {
	http_response_code (500);
	?>
	<p>Missing quote ID.</p>
	<?php
	exit;
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Open HTTP Redirect</h1>

	<div class=\"vulnerable_code_area\">
		<h2>Hacker Quotes</h2>
		<p>
			{$info}
		</p>
		<p><a href='../'>Back</a></p>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://cheatsheetseries.owasp.org/cheatsheets/Unvalidated_Redirects_and_Forwards_Cheat_Sheet.html', "OWASP Unvalidated Redirects and Forwards Cheat Sheet" ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/stable/4-Web_Application_Security_Testing/11-Client-side_Testing/04-Testing_for_Client-side_URL_Redirect', "WSTG - Testing for Client-side URL Redirect") . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://cwe.mitre.org/data/definitions/601.html', "Mitre - CWE-601: URL Redirection to Untrusted Site ('Open Redirect')" ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
