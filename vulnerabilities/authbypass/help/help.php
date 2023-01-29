<div class="body_padded">
	<h1>Help - Authorisation Bypass</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>
			When developers have to build authorisation matrices into complex systems it is easy for them to miss adding the right checks in every place, especially those
			which are not directly accessible through a browser, for example API calls.
		</p>

		<p>
			As a tester, you need to be looking at every call a system makes and then testing it using every level of user to ensure that the checks are being carried out correctly.
			This can often be a long and boring task, especially with a large matrix with lots of different user types, but it is critical that the testing is carried out as one missed
			check could lead to an attacker gaining access to confidential data or functions.
		</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Your goal is to test this user management system at all four security levels to identify any areas where authorisation checks have been missed.</p>
		<p>The system is only designed to be accessed by the admin user, so have a look at all the calls made while logged in as the admin, and then try to reproduce them while logged in as different user.</p>
		<p>If you need a second user, you can use <i>gordonb / abc123</i>.

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>Non-admin users do not have the 'Authorisation Bypass' menu option.</p>
		<p>Spoiler: <span class="spoiler">Try browsing directly to /vulnerabilities/authbypass/</span>.</p>


		<br />

		<h3>Medium Level</h3>
		<p>The developer has locked down access to the HTML for the page, but have a look how the page is populated when logged in as the admin.</p>
		<p>Spoiler: <span class="spoiler">Try browsing directly to /vulnerabilities/authbypass/get_user_data.php to access the API which returns the user data for the page.</span></p>

		<br />

		<h3>High Level</h3>
		<p>Both the HTML page and the API to retrieve data have been locked down, but what about updating data? You have to make sure you test every call to the site.</p>
		<p>Spoiler: <span class="spoiler">GET calls to retrieve data have been locked down but the POST to update the data has been missed, can you figure out how to call it?</span></p>

		<p>Spoiler: <span class="spoiler">This is one way to do it:</p>

		<pre><span class="spoiler">fetch('change_user_details.php', {
method: 'POST',
headers: {
'Accept': 'application/json',
'Content-Type': 'application/json'
},
body: JSON.stringify({ 'id':1, "first_name": "Harry", "surname": "Hacker" })
}
)
.then((response) => response.json())
.then((data) => console.log(data));
</span></pre>

		<br />

		<h3>Impossible Level</h3>
		<p>
			Hopefully on this level all the functions correctly check authorisation before allowing access to the data.
		</p>
		<p>
			There may however be some non-authorisation related issues on the page, so do not write it off as fully secure.
		</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/v42/4-Web_Application_Security_Testing/05-Authorization_Testing/02-Testing_for_Bypassing_Authorization_Schema' ); ?></p>
	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/04-Authentication_Testing/04-Testing_for_Bypassing_Authentication_Schema' ); ?></p>
	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-top-ten/2017/A2_2017-Broken_Authentication' ); ?></p>
	
</div>
