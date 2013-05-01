<div class="body_padded">
	<h1>Help - Command Execution</h1>
	
	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">

	<p>The purpose of the command injection attack is to inject and execute commands specified by the attacker in the vulnerable application. 
	In situation like this, the application, which executes unwanted system commands, is like a pseudo system shell, and the attacker may use it 
	as any authorized system user. However, commands are executed with the same privileges and environment as the application has. Command injection 
	attacks are possible in most cases because of lack of correct input data validation, which can be manipulated by the attacker (forms, cookies, HTTP headers etc.). </p>
	
	<p>To add a command use ; for linux and && for windows. Example: 127.0.0.1 && dir</p>

	</div></td>
	</tr>
	</table>
	
	</div>
	
	<br />
	
	<p>Reference: http://www.owasp.org/index.php/Command_Injection</p>

</div>
