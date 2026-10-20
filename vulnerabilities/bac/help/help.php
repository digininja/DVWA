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
						<p>Your goal is to bypass the access controls to view other users' profiles that you should not
							have access to. Each security level implements different types of access controls with
							varying degrees of effectiveness.</p>

						<br />
						<hr /><br />

						<h3>High Level</h3>
						<p><strong>Hint:</strong> The application uses session-based authentication to control access. Look carefully at how the session is validated.</p>

						<div class="vulnerable_code_area">
							<h3>Solution</h3>
							<button class="popup_button" onclick="toggle_visibility('high_solution')">Show Solution</button>
							<br>
							<div id="high_solution" style="display: none;">
								The high level can be bypassed using these steps:
								<ol>
									<li>Notice that the application uses a session variable named 'user_id' to determine which profiles you can access</li>
									<li>The vulnerability is that the session is vulnerable to session fixation attacks</li>
									<li>To exploit this vulnerability, you need to modify your session:</li>
									<li>Use a tool like Tamper Data, OWASP ZAP, or browser developer tools to intercept and modify requests</li>
									<li>Modify your PHP session by using PHP code like this in another PHP file:</li>
									<code>
									&lt;?php
									session_start();
									$_SESSION['user_id'] = 2; // Set to the ID of the user you want to view
									echo "Session modified!";
									?&gt;
									</code>
									<li>After modifying your session, access the URL: <code>vulnerabilities/bac/?user_id=2&action=View+Profile</code></li>
									<li>The application will think you are the user with that ID and grant access</li>
								</ol>
								<p><strong>Why this works:</strong> The high level implementation checks if the requested user_id matches the user_id in your session. By manipulating your session, you can make the application think you're authorized to view any user profile.</p>
							</div>
						</div>

						<br />

						<h3>Medium Level</h3>
						<p><strong>Hint:</strong> The application uses a token parameter for authorization. What happens if you add this token to your request?</p>

						<div class="vulnerable_code_area">
							<h3>Solution</h3>
							<button class="popup_button" onclick="toggle_visibility('medium_solution')">Show
								Solution</button>
								<br>
							<div id="medium_solution" style="display: none;">
								The medium level can be bypassed by:
								<ol>
									<li>Examining the error message when you try to access a profile</li>
									<li>Notice that it mentions "Valid token required"</li>
									<li>Look at the HTML comment in the error message which reveals the token value</li>
									<li>Add <code>?token=user_token</code> to your URL request</li>
									<li>For example, to view user ID 2's profile: <code>vulnerabilities/bac/?user_id=2&token=user_token&action=View+Profile</code></li>
									<li>With the token parameter added, you can now access any user's profile</li>
								</ol>
								<p><strong>Why this works:</strong> The medium level implementation uses a hardcoded token value for authorization. This is a weak security practice as the token is the same for all users and is easily discoverable.</p>
							</div>
						</div>

						<br />

						<h3>Low Level</h3>
						<p><strong>Hint:</strong> The application uses a cookie-based verification system. Can you
							spot how the access is being checked?</p>

						<div class="vulnerable_code_area">
							<h3>Solution</h3>
							<button class="popup_button" onclick="toggle_visibility('low_solution')">Show
								Solution</button>
								<br>

							<div id="low_solution" style="display: none;">
								The low level can be bypassed by:
								<ol>
									<li>Opening your browser's developer tools (F12)</li>
									<li>Going to the Application/Storage tab</li>
									<li>Finding the <code>user_id</code> cookie</li>
									<li>Changing its value to the ID of the user you want to view</li>
									<li>For example, to view user ID 2's profile, set the cookie value to '2'</li>
									<li>Access the URL: <code>vulnerabilities/bac/?user_id=2&action=View+Profile</code></li>
									<li>The application will check if the requested user_id matches your cookie value and grant access</li>
								</ol>
								<p><strong>Why this works:</strong> The low level implementation trusts the user_id cookie without proper validation. Since cookies are client-side data that can be easily modified, this is a serious security vulnerability.</p>
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
						<p>You can view the logs by clicking the "View Broken Access Control Logs" link on the security settings page.</p>

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