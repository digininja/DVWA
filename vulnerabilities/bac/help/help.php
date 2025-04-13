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
						<hr />

						<h3>Low Level</h3>
						<p><strong>Hint:</strong> The application uses a simple cookie-based verification system. Can you
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
									<li>Creating a new cookie named <code>user_id</code></li>
									<li>Setting its value to the ID of the user you want to view</li>
									<li>Accessing any user's profile by changing the user_id parameter in the URL</li>
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
									<li>Opening your browser's developer tools (F12)</li>
									<li>Going to the Application/Storage tab</li>
									<li>Finding the <code>user_role</code> cookie</li>
									<li>Changing its value from <code>regular_user</code> to <code>admin</code></li>
									<li>Refreshing the page to access any profile</li>
								</ol>
							</div>
						</div>

						<br />

						<h3>High Level</h3>
						<p><strong>Hint:</strong> The application uses session-based authentication and cookies to control access. Look carefully at how the session and cookies are validated.</p>

						<div class="vulnerable_code_area">
							<h3>Solution</h3>
							<button class="popup_button" onclick="toggle_visibility('high_solution')">Show Solution</button>
							<br>
							<div id="high_solution" style="display: none;">
								The high level can be bypassed using these steps:
								<ol>
									<li>Notice that the application uses a cookie named 'user_id' to determine which profiles you can access</li>
									<li>Open your browser's developer tools (F12) and go to the Application/Storage tab</li>
									<li>Find the 'user_id' cookie</li>
									<li>Change the cookie value to match the ID of the user whose profile you want to view</li>
									<li>For example, to view user ID 2's profile:
										<ul>
											<li>Set the 'user_id' cookie to '2'</li>
											<li>Access the URL: <code>vulnerabilities/bac/?user_id=2</code></li>
										</ul>
									</li>
									<li>The application will think you are the user with that ID and grant access</li>
								</ol>
								<p><strong>Why this works:</strong> The high level implementation trusts the user_id cookie without proper validation, assuming that users won't modify their cookies. This is a common security mistake where client-side data is trusted without server-side verification.</p>
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