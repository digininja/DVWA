<div class="body_padded">
	<h1>Help - Content Security Policy (CSP) Bypass</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>Blah about CSP.</p>

		<p></p>
		<p></p>
		<p></p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Bypass Content Security Policy (CSP) and execute JavaScript in the page.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>This allows for direct input into one of <u>many PHP functions</u> that will execute commands on the OS. It is possible to escape out of the designed command and
			executed unintentional actions.</p>
		<p>This can be done by adding on to the request, "once the command has executed successfully, run this command".
		<pre>Spoiler: <span class="spoiler">To add a command "&&"</span>. Example: <span class="spoiler">127.0.0.1 && dir</span>.</pre>

		<br />

		<h3>Medium Level</h3>
		<p>The developer has read up on some of the issues with command injection, and placed in various pattern patching to filter the input. However, this isn't enough.</p>
		<p>Various other system syntaxes can be used to break out of the desired command.</p>
		<pre>Spoiler: <span class="spoiler">e.g. background the ping command</span>.</pre>

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
