<div class="body_padded">
	<h1>Help - Command Injection</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>The purpose of the command injection attack is to inject and execute commands specified by the attacker in the vulnerable application.
			In situation like this, the application, which executes unwanted system commands, is like a pseudo system shell, and the attacker may use it
			as any authorized system user. However, commands are executed with the same privileges and environment as the web service has.</p>

		<p>Command injection attacks are possible in most cases because of lack of correct input data validation, which can be manipulated by the attacker
			(forms, cookies, HTTP headers etc.).</p>

		<p>The syntax and commands may differ between the Operating Systems (OS), such as Linux and Windows, depending on their desired actions.</p>

		<p>This attack may also be called "Remote Command Execution (RCE)".</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Remotely, find out the user of the web service on the OS, as well as the machines hostname via RCE.</p>

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

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/Command_Injection' ); ?></p>
</div>
