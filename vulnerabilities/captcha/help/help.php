<div class="body_padded">
	<h1>Help - Insecure CAPTCHA</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>A <?php echo dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/CAPTCHA', 'CAPTCHA' ); ?> is a program that can tell whether its user is a human or a computer. You've probably seen
			them - colourful images with distorted text at the bottom of Web registration forms. CAPTCHAs are used by many websites to prevent abuse from
			"bots", or automated programs usually written to generate spam. No computer program can read distorted text as well as humans can, so bots
			cannot navigate sites protected by CAPTCHAs.</p>

		<p>CAPTCHAs are often used to protect sensitive functionality from automated bots. Such functionality typically includes user registration and changes,
			password changes, and posting content. In this example, the CAPTCHA is guarding the change password functionality for the user account. This provides
			limited protection from CSRF attacks as well as automated bot guessing.</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Your aim, change the current user's password in a automated manner because of the poor CAPTCHA system.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The issue with this CAPTCHA is that it is easily bypassed. The developer has made the assumption that all users will progress through screen 1, complete the CAPTCHA, and then
			move on to the next screen where the password is actually updated. By submitting the new password directly to the change page, the user may bypass the CAPTCHA system.</p>

		<p>The parameters required to complete this challenge in low security would be similar to the following:</p>
		<pre>Spoiler: <span class="spoiler">?step=2&password_new=password&password_conf=password&Change=Change</span>.</pre>

		<br />

		<h3>Medium Level</h3>
		<p>The developer has attempted to place state around the session and keep track of whether the user successfully completed the
			CAPTCHA prior to submitting data. Because the state variable (Spoiler: <span class="spoiler">passed_captcha</span>) is on the client side,
			it can also be manipulated by the attacker like so:</p>
		<pre>Spoiler: <span class="spoiler">?step=2&password_new=password&password_conf=password&passed_captcha=true&Change=Change</span>.</pre>

		<br />

		<h3>High Level</h3>
		<p>There has been development code left in, which was never removed in production. It is possible to mimic the development values, to allow
			invalid values in be placed into the CAPTCHA field.</p>
		<p>You will need to spoof your user-agent (Spoiler: <span class="spoiler">reCAPTCHA</span>) as well as use the CAPTCHA value of
			(Spoiler: <span class="spoiler">hidd3n_valu3</span>) to skip the check.</p>

		<br />

		<h3>Impossible Level</h3>
		<p>In the impossible level, the developer has removed all avenues of attack. The process has been simplified so that data and CAPTCHA verification occurs in one
			single step. Alternatively, the developer could have moved the state variable server side (from the medium level), so the user cannot alter it.</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/CAPTCHA' ); ?></p>
</div>
