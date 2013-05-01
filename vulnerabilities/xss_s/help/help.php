<div class="body_padded">
	<h1>Help - Cross Site Scripting (XSS)</h1>
	
	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">

		<p>Cross-Site Scripting attacks are a type of injection problem, in which malicious scripts are injected into the otherwise benign and trusted web sites. 
		Cross-site scripting (XSS) attacks occur when an attacker uses a web application to send malicious code, generally in the form of a browser side script, to a different end user.
		Flaws that allow these attacks to succeed are quite widespread and occur anywhere a web application uses input from a user in the output it generates without validating or encoding it.</P>

		<p>An attacker can use XSS to send a malicious script to an unsuspecting user. The end user's browser has no way to know that the script should not be trusted, and will execute the script.
		Because it thinks the script came from a trusted source, the malicious script can access any cookies, session tokens, or other sensitive information retained by your browser and used with 
		that site. These scripts can even rewrite the content of the HTML page. </p>
		
		<p>The XSS payload is stored in the database. The XSS is permanent until the database is reset or the payload is manually deleted.</p>
		
	</div></td>
	</tr>
	</table>
	
	</div>
	
	<br />
	
	<p>Reference: http://www.owasp.org/index.php/Cross-site_Scripting_(XSS)</p>

</div>
		
