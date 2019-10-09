<div class="body_padded">
	<h1>Help - Cross Site Request Forgery (CSRF)</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>CSRF is an attack that forces an end user to execute unwanted actions on a web application in which they are currently authenticated.
			With a little help of social engineering (such as sending a link via email/chat), an attacker may force the users of a web application to execute actions of
			the attacker's choosing.</p>

		<p>A successful CSRF exploit can compromise end user data and operation in case of normal user. If the targeted end user is
			the administrator account, this can compromise the entire web application.</p>

		<p>This attack may also be called "XSRF", similar to "Cross Site scripting (XSS)", and they are often used together.</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Your task is to make the current user change their own password, without them knowing about their actions, using a CSRF attack.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>There are no measures in place to protect against this attack. This means a link can be crafted to achieve a certain action (in this case, change the current users password).
			Then with some basic social engineering, have the target click the link (or just visit a certain page), to trigger the action. </p>

		<br />

	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF)' ); ?></p>
</div>
