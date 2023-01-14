<div class="body_padded">
	<h1>Help - Brute Force (Login)</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>Password cracking is the process of recovering passwords from data that has been stored in or transmitted by a computer system.
			A common approach is to repeatedly try guesses for the password.</p>

		<p>Users often choose weak passwords. Examples of insecure choices include single words found in dictionaries, family names, any too short password
			(usually thought to be less than 6 or 7 characters), or predictable patterns
			(e.g. alternating vowels and consonants, which is known as leetspeak, so "password" becomes "p@55w0rd").</p>

		<p>Creating a targeted wordlists, which is generated towards the target, often gives the highest success rate. There are public tools out there that will create a dictionary
			based on a combination of company websites, personal social networks and other common information (such as birthdays or year of graduation).

		<p>A last resort is to try every possible password, known as a brute force attack. In theory, if there is no limit to the number of attempts, a brute force attack will always
			be successful since the rules for acceptable passwords must be publicly known; but as the length of the password increases, so does the number of possible passwords
			making the attack time longer.</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Your goal is to get the administrator’s password by brute forcing. Bonus points for getting the other four user passwords!</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The developer has completely missed out <u>any protections methods</u>, allowing for anyone to try as many times as they wish, to login to any user without any repercussions.</p>

		<br />

		<h3>Medium Level</h3>
		<p>This stage adds a sleep on the failed login screen. This mean when you login incorrectly, there will be an extra two second wait before the page is visible.</p>

		<p>This will only slow down the amount of requests which can be processed a minute, making it longer to brute force.</p>

		<br />

		<h3>High Level</h3>
		<p>There has been an "anti Cross-Site Request Forgery (CSRF) token" used. There is a old myth that this protection will stop brute force attacks. This is not the case.
			This level also extends on the medium level, by waiting when there is a failed login but this time it is a random amount of time between two and four seconds.
			The idea of this is to try and confuse any timing predictions.</p>

		<p>Using a <?php echo dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/CAPTCHA', 'CAPTCHA' ); ?> form could have a similar effect as a CSRF token.</p>

		<br />

		<h3>Impossible Level</h3>
		<p>Brute force (and user enumeration) should not be possible in the impossible level. The developer has added a "lock out" feature, where if there are five bad logins within
			the last 15 minutes, the locked out user cannot log in.</p>

		<p>If the locked out user tries to login, even with a valid password, it will say their username or password is incorrect. This will make it impossible to know
			if there is a valid account on the system, with that password, and if the account is locked.</p>

		<p>This can cause a "Denial of Service" (DoS), by having someone continually trying to login to someone's account.
			This level would need to be extended by blacklisting the attacker (e.g. IP address, country, user-agent).</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Password_cracking' ); ?></p>
</div>
