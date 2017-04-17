<div class="body_padded">
	<h1>Help - Cross Site Scripting (DOM Based)</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>"Cross-Site Scripting (XSS)" attacks are a type of injection problem, in which malicious scripts are injected into the otherwise benign and trusted web sites.
			XSS attacks occur when an attacker uses a web application to send malicious code, generally in the form of a browser side script,
			to a different end user. Flaws that allow these attacks to succeed are quite widespread and occur anywhere a web application using input from a user in the output,
			without validating or encoding it.</p>

		<p>An attacker can use XSS to send a malicious script to an unsuspecting user. The end user's browser has no way to know that the script should not be trusted,
			and will execute the JavaScript. Because it thinks the script came from a trusted source, the malicious script can access any cookies, session tokens, or other
			sensitive information retained by your browser and used with that site. These scripts can even rewrite the content of the HTML page.</p>

		<p>DOM Based XSS is a special case of reflected where the JavaScript is hidden in the URL and pulled out by JavaScript in the page while it is rendering rather than being embedded in the page when it is served. This can make it stealthier than other attacks and WAFs or other protections which are reading the page body do not see any malicious content.</p>

		<p><hr /></p>

		<h3>Objective</h3>
		<p>Run your own JavaScript in another user's browser, use this to steal the cookie of a logged in user.</p>

		<p><hr /></p>

		<h3>Low Level</h3>
		<p>Low level will not check the requested input, before including it to be used in the output text.</p>
		<pre>Spoiler: <span class="spoiler"><?=htmlentities ("/vulnerabilities/xss_d/?default=English<script>alert(1)</script>")?></span>.</pre>

		<p><br /></p>

		<h3>Medium Level</h3>
		<p>The developer has tried to add a simple pattern matching to remove any references to "&lt;script" to disable any JavaScript. Find a way to run JavaScript without using the script tags.</p>
		<pre>Spoiler: <span class="spoiler">You must first break out of the select block then you can add an image with an onerror event:<br />
<?=htmlentities ("/vulnerabilities/xss_d/?default=English>/option></select><img src='x' onerror='alert(1)'>");?></span>.</pre>

		<p><br /></p>

		<h3>High Level</h3>
		<p>The developer is now white listing only the allowed languages, you must find a way to run your code without it going to the server.</p>
		<pre>Spoiler: <span class="spoiler">The fragment section of a URL (anything after the # symbol) does not get sent to the server and so cannot be blocked. The bad JavaScript being used to render the page reads the content from it when creating the page.<br />
<?=htmlentities ("/vulnerabilities/xss_d/?default=English#<script>alert(1)</script>")?></span>.</pre>

		<p><br /></p>

		<h3>Impossible Level</h3>
		<p>The contents taken from the URL are encoded by default by most browsers which prevents any injected JavaScript from being executed.</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Cross-site_Scripting_(XSS)' ); ?></p>
</div>
