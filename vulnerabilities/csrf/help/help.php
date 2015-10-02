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
			Then with some basic social engineering, have the target click the link (or just visit a certain page), to trigger the action.</p>
		<pre>Spoiler: <span class="spoiler">?password_new=password&password_conf=password&Change=Change</span>.</pre>

		<br />

		<h3>Medium Level</h3>
		<p>For the medium level challenge, there is a check to see where the last requested page came from. The developer believes if it matches the current domain,
			it must of come from the web application so it can be trusted.</p>
		<p>It may be required to link in multiple vulnerabilities to exploit this vector, such as reflective XSS.</p>

		<br />

		<h3>High Level</h3>
		<p>In the high level, the developer has added an "anti Cross-Site Request Forgery (CSRF) token". In order by bypass this protection method, another vulnerability will be required.</p>
		<pre>Spoiler: <span class="spoiler">e.g. Javascript is a executed on the client side, in the browser</span>.</pre>

		<br />

		<h3>Impossible Level</h3>
		<p>In the impossible level, the challenge will extent the high level and asks for the current user's password. As this cannot be found out (only predicted or brute forced),
			there is not an attack vector here.</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF)' ); ?></p>
</div>
