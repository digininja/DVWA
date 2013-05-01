<div class="body_padded">
	<h1>Help - SQL Injection (Blind)</h1>
	
	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">

		<p>When an attacker executes SQL Injection attacks, sometimes the server responds with error messages from the database server complaining that the SQL Query's syntax is incorrect. 
		Blind SQL injection is identical to normal SQL Injection except that when an attacker attempts to exploit an application, rather then getting a useful error message, 
		they get a generic page specified by the developer instead. This makes exploiting a potential SQL Injection attack more difficult but not impossible. An attacker can still steal data 
		by asking a series of True and False questions through SQL statements.</p>
		
		<p>The 'id' variable within this PHP script is vulnerable to SQL injection.</p>
		
		<p>There are 5 users in the database, with id's from 1 to 5. Your mission... to steal passwords!</p>
		
		<p>If you have received a Magicquotes error, turn them off in php.ini.</p>
		
	</div></td>
	</tr>
	</table>
	
	</div>
	
	<br />
	
	<p>Reference: http://www.owasp.org/index.php/Blind_SQL_Injection</p>

</div>
		