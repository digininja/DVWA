<div class="body_padded">
	<h1>Help - SQL Injection (Blind)</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>When an attacker executes SQL injection attacks, sometimes the server responds with error messages from the database server complaining that the SQL query's syntax is incorrect.
			Blind SQL injection is identical to normal SQL Injection except that when an attacker attempts to exploit an application, rather then getting a useful error message,
			they get a generic page specified by the developer instead. This makes exploiting a potential SQL Injection attack more difficult but not impossible.
			An attacker can still steal data by asking a series of True and False questions through SQL statements, and monitoring how the web application response
			(valid entry retunred or 404 header set).</p>

		<p>"time based" injection method is often used when there is no visible feedback in how the page different in its response (hence its a blind attack).
		 	This means the attacker will wait to see how long the page takes to response back. If it takes longer than normal, their query was successful.</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Find the version of the SQL database software through a blind SQL attack.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The SQL query uses RAW input that is directly controlled by the attacker. All they need to-do is escape the query and then they are able
			to execute any SQL query they wish.</p>
		<pre>Spoiler: <span class="spoiler">?id=1' AND sleep 5&Submit=Submit</span>.</pre>

		<br />

		<h3>Medium Level</h3>
		<p>The medium level uses a form of SQL injection protection, with the function of
			"<?php echo dvwaExternalLinkUrlGet( 'https://secure.php.net/manual/en/function.mysql-real-escape-string.php', 'mysql_real_escape_string()' ); ?>".
			However due to the SQL query not having quotes around the parameter, this will not fully protect the query from being altered.</p>

		<p>The text box has been replaced with a pre-defined dropdown list and uses POST to submit the form.</p>
		<pre>Spoiler: <span class="spoiler">?id=1 AND sleep 3&Submit=Submit</span>.</pre>

		<br />

		<h3>High Level</h3>
		<p>This is very similar to the low level, however this time the attacker is inputting the value in a different manner.
			The input values are being set on a different page, rather than a GET request.</p>
		<pre>Spoiler: <span class="spoiler">ID: 1' AND sleep 10&Submit=Submit</span>.
			Spoiler: <span class="spoiler">Should be able to cut out the middle man.</span>.</pre>

		<br />

		<h3>Impossible Level</h3>
		<p>The queries are now parameterized queries (rather than being dynamic). This means the query has been defined by the developer,
			and has distinguish which sections are code, and the rest is data.</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/Blind_SQL_Injection' ); ?></p>
</div>
