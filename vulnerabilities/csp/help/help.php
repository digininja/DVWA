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
		<pre>Spoiler: <span class="spoiler">Scripts can be included from Pastebin, try storing some JavaScript on there.</span>.</pre>

		<br />

		<h3>Medium Level</h3>
		<p>The CSP policy tries to use a nonce to prevent inline scripts from being added by attackers.</p>
		<pre>Spoiler: <span class="spoiler">Examine the nonce and see how it varies (or doesn't).</span>.</pre>

		<br />

		<h3>High Level</h3>
		<p>In the high level, the developer goes back to the drawing board and puts in even more pattern to match. But even this isn't enough.</p>
		<p>The developer has either made a slight typo with the filters and believes a certain PHP command will save them from this mistake.</p>
		<pre>Spoiler: <span class="spoiler"><?php echo dvwaExternalLinkUrlGet( 'https://secure.php.net/manual/en/function.trim.php', 'trim()' ); ?>
			removes all leading & trailing spaces, right?</span>.</pre>

		<br />

		<h3>Impossible Level</h3>
		<p>In the impossible level, the challenge has been re-written, only to allow a very stricted input. If this doesn't match and doesn't produce a certain result,
			it will not be allowed to execute. Rather than "black listing" filtering (allowing any input and removing unwanted), this uses "white listing" (only allow certain values).</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://content-security-policy.com/', "Content Security Policy Reference" ); ?></p>
	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP', "Mozilla Developer Network - CSP: script-src"); ?></p>
	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://blog.mozilla.org/security/2014/10/04/csp-for-the-web-we-have/', "Mozilla Security Blog - CSP for the web we have" ); ?></p>
</div>
