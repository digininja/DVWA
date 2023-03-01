<div class="body_padded">
	<h1>Help - Open HTTP Redirect</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>
		OWASP define this as:
		</p>
		<blockquote cite="https://cheatsheetseries.owasp.org/cheatsheets/Unvalidated_Redirects_and_Forwards_Cheat_Sheet.html">
			Unvalidated redirects and forwards are possible when a web application accepts untrusted input that could cause the web application to redirect the request to a URL contained within untrusted input. By modifying untrusted URL input to a malicious site, an attacker may successfully launch a phishing scam and steal user credentials.
		</blockquote>

		<p>As suggested above, a common use for this is to create a URL which initially goes to the real site but then redirects the victim off to a site controlled by the attacker. This site could be a clone of the target's login page to steal credentials, a request for credit card details to pay for a service on the target site, or simply a spam page full of advertising.</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Abuse the redirect page to move the user off the DVWA site or onto a different page on the site than expected.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The redirect page has no limitations, you can redirect to anywhere you want.</p>
		<p>Spoiler: <span class="spoiler">Try browsing to /vulnerabilities/open_redirect/source/low.php?redirect=https://digi.ninja</span></p>

		<br />

		<h3>Medium Level</h3>
		<p>The code prevents you from using absolute URLs to take the user off the site, so you can either use relative URLs to take them to other pages on the same site or a <a href="https://en.wikipedia.org/wiki/Wikipedia:Protocol-relative_URL" target="_blank">Protocol-relative URL</a>.</p>

		<p>Spoiler: <span class="spoiler">Try browsing to /vulnerabilities/open_redirect/source/low.php?redirect=//digi.ninja</span></p>

		<br />

		<h3>High Level</h3>
		<p>The redirect page tries to lock you to only redirect to the info.php page, but does this by checking that the URL contains "info.php".</p>

		<p>Spoiler: <span class="spoiler">Try browsing to /vulnerabilities/open_redirect/source/low.php?redirect=https://digi.ninja/?a=info.php</span></p>

		<br />

		<h3>Impossible Level</h3>
		<p>Rather than accepting a page or URL as the redirect target, the system uses ID values to tell the redirect page where to redirect to. This ties the system down to only redirect to pages it knows about and so there is no way for an attacker to modify things to go to a page of their choosing.</p>

	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Password_cracking' ); ?></p>
</div>
