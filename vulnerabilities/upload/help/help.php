<div class="body_padded">
	<h1>Help - File Upload</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>Uploaded files represent a significant risk to web applications. The first step in many attacks is to get some code to the system to be attacked.
			Then the attacker only needs to find a way to get the code executed. Using a file upload helps the attacker accomplish the first step.</p>

		<p>The consequences of unrestricted file upload can vary, including complete system takeover, an overloaded file system, forwarding attacks to backend systems,
			and simple defacement. It depends on what the application does with the uploaded file, including where it is stored.</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Execute any PHP function of your choosing on the target system (such as <?php echo dvwaExternalLinkUrlGet( 'https://secure.php.net/manual/en/function.phpinfo.php', 'phpinfo()' ); ?>
			or <?php echo dvwaExternalLinkUrlGet( 'https://secure.php.net/manual/en/function.system.php', 'system()' ); ?>) thanks to this file upload vulnerability.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>Low level will not check the contents of the file being uploaded in any way. It relies only on trust.</p>
		<pre>Spoiler: <span class="spoiler">Upload any valid PHP file with command in it</span>.</pre>

		<br />

		<h3>Medium Level</h3>
		<p>When using the medium level, it will check the reported file type from the client when its being uploaded.</p>
		<pre>Spoiler: <span class="spoiler">Worth looking for any restrictions within any "hidden" form fields</span>.</pre>

		<br />

		<h3>High Level</h3>
		<p>Once the file has been received from the client, the server will try to resize any image that was included in the request.</p>
		<pre>Spoiler: <span class="spoiler">need to link in another vulnerability, such as file inclusion</span>.</pre>

		<br />

		<h3>Impossible Level</h3>
		<p>This will check everything from all the levels so far, as well then to re-encode the image. This will make a new image, therefore stripping
			any "non-image" code (including metadata).</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/vulnerabilities/Unrestricted_File_Upload' ); ?></p>
</div>

