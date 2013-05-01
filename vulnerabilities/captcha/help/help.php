<div class="body_padded">
	<h1>Help - Insecure CAPTCHA (Intro to Logic Flaws</h1>
	
	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
	
	<p>A <a href="http://www.captcha.net">CAPTCHA</a> is a program that can tell whether its user is a human or a computer. You&#39;ve probably seen 
		them &mdash; colorful images with distorted text at the bottom of Web registration forms. CAPTCHAs are used by many websites to prevent abuse from 
		&quot;bots,&quot; or automated programs usually written to generate spam. No computer program can read distorted text as well as humans can, so bots 
		cannot navigate sites protected by CAPTCHAs.</p>
	
	<p>CAPTCHAs are often used to protect sensative functionality from automated bots. Such functionality typically includes user registration and changes, 
		password changes, and posting content. In this example, the CAPTCHA is guarding the change password functionality for the Administrator account. This provides 
		limited protection from CSRF attacks as well as automated bot guessing.</p>

	<p>The issue with this CAPTCHA is that it is easily bypassed. The developer has made the assumption that all users will progress through screen 1, complete the CAPTCHA, and then 
		move on to the next screen where the password is actually updated. By submitting the new password directly to the change page, the user may bypass the CAPTCHA. </p>
	<p>The parameters required to complete this challenge in low security would be similar to the following:</p>
	<p>step=2&password_new=password&password_conf=password&Change=Change</p>
        
        <p>For the medium level challenge, the developer has attempted to place state around the session and keep track of whether the user successfully completed the 
            CAPTCHA prior to submitting data. Because the state variable ("passed_captcha") is on the client side, it can also be manipulated by the attacker like so:</p>
        <p>step=2&password_new=password&password_conf=password&passed_captcha=true&Change=Change</p>
        
        <p>In the high level, the developer has removed all avenues of attack. The process has been simplified so that data and CAPTCHA verification occurs in one
            single step. Alternatively, the developer could have moved the state variable server side, or NONCE'd the form.</p>

	</div></td>
	</tr>
	</table>
	
	</div>
	
	<br />
	
	<p>Reference: http://www.captcha.net/</p>

</div>
