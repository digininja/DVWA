<div class="body_padded">
	<h1>Help - SQL Injection</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>A SQL injection attack consists of insertion or "injection" of a SQL query via the input data from the client to the application.
			A successful SQL injection exploit can read sensitive data from the database, modify database data (insert/update/delete), execute administration operations on the database
			(such as shutdown the DBMS), recover the content of a given file present on the DBMS file system (load_file) and in some cases issue commands to the operating system.</p>

		<p>SQL injection attacks are a type of injection attack, in which SQL commands are injected into data-plane input in order to effect the execution of predefined SQL commands.</p>

		<p>This attack may also be called "SQLi".</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>There are 5 users in the database, with id's from 1 to 5. Your mission... to steal their passwords via SQLi.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The SQL query uses RAW input that is directly controlled by the attacker. All they need to-do is escape the query and then they are able
			to execute any SQL query they wish.</p>
		<pre>Spoiler: <span class="spoiler">?id=a' UNION SELECT "text1","text2";-- -&Submit=Submit</span>.</pre>

		<br />

		<h3>Medium Level</h3>
		<p>The medium level uses a form of SQL injection protection, with the function of
			"<?php echo dvwaExternalLinkUrlGet( 'https://secure.php.net/manual/en/function.mysql-real-escape-string.php', 'mysql_real_escape_string()' ); ?>".
			However due to the SQL query not having quotes around the parameter, this will not fully protect the query from being altered.</p>

		<p>The text box has been replaced with a pre-defined dropdown list and uses POST to submit the form.</p>
		<pre>Spoiler: <span class="spoiler">?id=a UNION SELECT 1,2;-- -&Submit=Submit</span>.</pre>

		<br />

		<h3>High Level</h3>
		<p>This is very similar to the low level, however this time the attacker is inputting the value in a different manner.
			The input values are being transferred to the vulnerable query via session variables using another page, rather than a direct GET request.</p>
		<pre>Spoiler: <span class="spoiler">ID: a' UNION SELECT "text1","text2";-- -&Submit=Submit</span>.</pre>

		<br />

		<h3>Impossible Level</h3>
		<p>The queries are now parameterized queries (rather than being dynamic). This means the query has been defined by the developer,
			and has distinguish which sections are code, and the rest is data.</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/SQL_Injection' ); ?></p>
</div>
