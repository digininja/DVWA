<div class="body_padded">
	<h1>Help - Broken Access Control</h1>

	<div id="code">
		<table width="100%" bgcolor="white" style="border:2px #C0C0C0 solid">
			<tr>
				<td>
					<div id="code">
						<h3>About</h3>
						<p>Broken Access Control (BAC) refers to a situation where an application fails to properly
							enforce restrictions on what authenticated users are allowed to do.
							This vulnerability occurs when a user can access resources or perform actions that should be
							restricted to them.</p>

						<br />
						<hr /><br />

						<h3>Objective</h3>
						<p>Your goal is to bypass the access controls to view other users\' profiles that you should not
							have access to. Each security level implements different types of access controls with
							varying degrees of effectiveness.</p>

						<br />
						<hr /><br />

						<h3>Low Level</h3>
						<p><strong>Hint:</strong> The application uses a simple token-based verification system. Can you
							spot how the token is being checked?</p>

						<div class="vulnerable_code_area">
							<h3>Solution</h3>
							<button class="popup_button" onclick="toggle_visibility('low_solution')">Show
								Solution</button>
								<br>

							<div id="low_solution" style="display: none;">
								The low level can be bypassed by:
								<ol>
									<li>Examining the page source to find the token hint</li>
									<li>Adding <code>?token=user_token</code> to the URL</li>
									<li>Now you can access any user\'s profile by changing the user_id parameter</li>
								</ol>
							</div>
						</div>

						<br />

						<h3>Medium Level</h3>
						<p><strong>Hint:</strong> The application uses cookies to determine user roles. What tools in
							your browser might help you examine and modify these?</p>

						<div class="vulnerable_code_area">
							<h3>Solution</h3>
							<button class="popup_button" onclick="toggle_visibility('medium_solution')">Show
								Solution</button>
								<br>
							<div id="medium_solution" style="display: none;">
								The medium level can be bypassed by:
								<ol>
									<li>Opening your browser\'s developer tools (F12)</li>
									<li>Going to the Application/Storage tab</li>
									<li>Finding the <code>user_role</code> cookie</li>
									<li>Changing its value from <code>regular_user</code> to <code>admin</code></li>
									<li>Refreshing the page to access any profile</li>
								</ol>
							</div>
						</div>

						<br />

						<h3>High Level</h3>
						<p><strong>Hint:</strong> The application uses session-based authentication. Look carefully at
							how the session is managed and validated.</p>

						<div class="vulnerable_code_area">
							<h3>Solution</h3>
							<button class="popup_button" onclick="toggle_visibility('high_solution')">Show
								Solution</button>
								<br>

							<div id="high_solution" style="display: none;">
								The high level requires more advanced techniques:
								<ol>
									<li>Examine the session management system</li>
									<li>Notice that session validation relies on user-controlled headers</li>
									<li>Try manipulating the session token or other headers</li>
									<li>Look for race conditions in the session checks</li>
								</ol>
							</div>
						</div>

						<br />

						<h3>Impossible Level</h3>
						<p>This level implements proper access controls with multiple security layers:</p>
						<ul>
							<li>Comprehensive session security with fingerprinting</li>
							<li>Rate limiting to prevent brute force attempts</li>
							<li>Proper RBAC implementation with granular permissions</li>
							<li>Prepared statements for all database queries</li>
							<li>Extensive logging and monitoring</li>
							<li>Session timeout and regeneration</li>
						</ul>

						<br />
						<hr /><br />

						<h3>Security Logs</h3>
						<p>All access attempts are logged, including the IP address of the request. The application uses
							the X-Forwarded-For header when available,
							which means the logs can be manipulated by setting this header.</p>

						<br />

						<h3>More Information</h3>
						<ul>
							<li><a href="https://owasp.org/Top10/A01_2021-Broken_Access_Control/" target="_blank">OWASP
									Top 10 2021 - Broken Access Control</a></li>
							<li><a href="https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/05-Authorization_Testing/02-Testing_for_Bypassing_Authorization_Schema"
									target="_blank">OWASP Testing Guide - Authorization Testing</a></li>
						</ul>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>

<script>
function toggle_visibility(id) {
    var e = document.getElementById(id);
    if (e.style.display === "block") {
        e.style.display = "none";
    } else {
        e.style.display = "block";
    }
}
</script>