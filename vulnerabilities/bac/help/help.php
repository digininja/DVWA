<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(array('authenticated'));

$page = dvwaPageNewGrab();
$page['title'] = 'Help - Broken Access Control' . $page['title_separator'] . $page['title'];

$page['body'] .= '
<div class="body_padded">
	<h1>Help - Broken Access Control</h1>

	<div id="code">
	<table width="100%" bgcolor="white" style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>Broken Access Control (BAC) refers to a situation where an application fails to properly enforce restrictions on what authenticated users are allowed to do.
		   This vulnerability occurs when a user can access resources or perform actions that should be restricted to them.</p>

		<p>Access control enforces policy such that users cannot act outside of their intended permissions. Failures typically lead to unauthorized information disclosure,
		   modification, or destruction of data, or performing unauthorized functions.</p>

		<p>Common access control vulnerabilities include:</p>
		<ul>
			<li>Bypassing access control checks by modifying the URL or HTML page</li>
			<li>Allowing the primary key to be changed to another users record</li>
			<li>Elevation of privilege through manipulation of parameters</li>
			<li>Metadata manipulation like replaying or tampering with tokens</li>
			<li>CORS misconfiguration allowing unauthorized API access</li>
		</ul>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Your goal is to bypass the access controls to view other users\' profiles that you should not have access to. Each security level implements different types of access controls with varying degrees of effectiveness.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>This level has <u>no real access control checks</u> in place. The application directly uses user input to query the database without any validation.
		   An attacker can simply modify the user_id parameter to view any user\'s profile.</p>

		<br />

		<h3>Medium Level</h3>
		<p>This level implements a basic form of access control using cookies. However, it can be bypassed by manipulating the cookie values.
		   The application attempts to verify if you\'re an admin through a simple cookie check.</p>

		<p>While this is slightly better than no protection, cookies can be easily modified by users making this protection ineffective.</p>

		<br />

		<h3>High Level</h3>
		<p>This level implements role-based access control (RBAC) with session management. It uses prepared statements to prevent SQL injection and includes
		   basic logging of access attempts.</p>

		<p>However, it may still be vulnerable to session-based attacks or race conditions if proper session handling is not implemented.</p>

		<br />

		<h3>Impossible Level</h3>
		<p>This level implements proper access controls with multiple security layers:</p>
		<ul>
			<li>Comprehensive session security with fingerprinting</li>
			<li>Rate limiting to prevent brute force attempts</li>
			<li>Proper RBAC implementation with granular permissions</li>
			<li>Prepared statements for all database queries</li>
			<li>Extensive logging and monitoring</li>
			<li>Session timeout and regeneration</li>
		</ul>

		<p>Even with these protections, it\'s important to regularly audit access controls and monitor for new attack vectors.</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <a href="https://owasp.org/Top10/A01_2021-Broken_Access_Control/" target="_blank">OWASP - Broken Access Control</a></p>
	<p>Reference: <a href="https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/05-Authorization_Testing/02-Testing_for_Bypassing_Authorization_Schema" target="_blank">OWASP Testing Guide - Authorization Testing</a></p>

</div>';

?>
