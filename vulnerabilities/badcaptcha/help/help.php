<div class="body_padded">
	<h1>Help - Insecure CAPTCHA</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>A <?php echo dvwaExternalLinkUrlGet( 'http://www.captcha.net/', 'CAPTCHA' ); ?> is a program that can tell whether its user is a human or a computer. You've probably seen
			them - colourful images with distorted text at the bottom of Web registration forms. CAPTCHAs are used by many websites to prevent abuse from
			"bots", or automated programs usually written to generate spam. No computer program can read distorted text as well as humans can, so bots
			cannot navigate sites protected by CAPTCHAs.</p>

		<p>CAPTCHAs are often used to protect sensitive functionality from automated bots. Such functionality typically includes user registration and changes,
			password changes, and posting content. In this example, the CAPTCHA is keeping the session active for the logged in user.</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Your aim, keep the session active by guessing the bad CAPTCHA system using image recognition techniques.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The issue with this CAPTCHA is that it should be easy to guess using an OCR. The CAPTCHA code is composed only by three uppercase letters, always of the same
		size and without rotations.</p>

		<br />

		<h3>Medium Level</h3>
		<p>This CAPTCHA code is implemented by using 5 letters, that can be uppercase or lowercase. The code also applies some small rotations over the code and it has a
		 small shadow.</p>

		<br />

		<h3>High Level</h3>
		<p>The CAPTCHA in this level is implemented using 5 characters (uppercase, lowercase and digits), with variable font sizes and small rotations over the generated
		code.</p>

		<br />

		<h3>Impossible Level</h3>
		<p>In the impossible level, the CAPTCHA code is generated using the most advanced set of configuration. It uses 6 characters, variable font sizes, very variable
		angle rotations and a bigger shadow that should make the code difficult to guess using image recognition software.</p>

	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'http://www.captcha.net/' ); ?></p>
</div>
