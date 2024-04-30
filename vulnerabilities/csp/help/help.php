<div class="body_padded">
	<h1>Help - Content Security Policy (CSP) Bypass</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>Content Security Policy (CSP) is used to define where scripts and other resources can be loaded or executed from. This module will walk you through ways to bypass the policy based on common mistakes made by developers.</p>
		<p>None of the vulnerabilities are actual vulnerabilities in CSP, they are vulnerabilities in the way it has been implemented.</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Bypass Content Security Policy (CSP) and execute JavaScript in the page.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>Examine the policy to find all the sources that can be used to host external script files.</p>
		<p>This exercise was originally written to work with Pastebin, then updated for Hastebin, then Toptal, but all these stopped working as they set various headers that prevent the browser executing the JavaScript once it has downloaded it. To get around this, there are a selection of links included in the exercise, some will work, some will not, try to work out why.
		<pre>Spoiler: <span class="spoiler">
alert.js - Will work, this is a normal JavaScript file served with the correct headers.
alert.txt - This will not work as it has the wrong content type set by the web server due to its file extension.
cookie.js - This will work and will show your cookies
forced_download.js - As the name says, the server sets the "Content-Disposition: attachment" header for this to force the browser to download it rather than execute it.
wrong_content_type.js - This will not work as the web server ignores the file extension and forces the content type to get set as "plain/text" which prevents the browser executing it.</span></pre>

		<br />

		<h3>Medium Level</h3>
		<p>The CSP policy tries to use a nonce to prevent inline scripts from being added by attackers.</p>
		<pre>Spoiler: <span class="spoiler">Examine the nonce and see how it varies (or doesn't).</span></pre>

		<br />

		<h3>High Level</h3>
		<p>The page makes a JSONP call to source/jsonp.php passing the name of the function to callback to, you need to modify the jsonp.php script to change the callback function.</p>
		<pre>Spoiler: <span class="spoiler">The JavaScript on the page will execute whatever is returned by the page, changing this to your own code will execute that instead</span></pre>

		<br />

		<h3>Impossible Level</h3>
		<p>
			This level is an update of the high level where the JSONP call has its callback function hardcoded and the CSP policy is locked down to only allow external scripts.
		</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://content-security-policy.com/', "Content Security Policy Reference" ); ?></p>
	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP', "Mozilla Developer Network - CSP: script-src"); ?></p>
	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://blog.mozilla.org/security/2014/10/04/csp-for-the-web-we-have/', "Mozilla Security Blog - CSP for the web we have" ); ?></p>
</div>
