<style>
	pre {
		overflow-x: auto;
		white-space: pre-wrap;
		word-wrap: break-word;
	}
</style>

<div class="body_padded">
	<h1>Help - SSTI Security</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>
xxx
		</p>
		<p>
xxx
		</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Each level has its own objective but the general idea is to exploit Server Side Template Injection implementations.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The system decides which template to use based on the <code>template</code> query string parameter, can you get it to use a template you control?</p>
		<p>
		<button id="low_button" onclick="show_answer('low')">Show Answer</button>
		</p>
		<div id="low_answer">
		<p>Use the file upload functionality of the site to upload a file containing the following contents:</p>
		<pre><code>{include file="/etc/passwd"}</code></pre>

<p>
You can then tell the system to use it using the <code>template</code> 
</p>
<p>
http://dvwa.test/vulnerabilities/ssti/smarty/?template=../../../hackable/uploads/x.tpl
</p>
		</div>

		<h3>Medium Level</h3>
		<p>
			Look at the call made by the site, but also look at the swagger docs and see if there are any other parameters you might be able to add that are not currently passed.
		</p>
		<p>
		<button id="medium_button" onclick="show_answer('medium')">Show Answer</button>
		</p>
		<div id="medium_answer">
		<p>When you update your name, a PUT request is made to <code>/vulnerabilities/api/v2/user/2</code> with the following content:</p>

<pre><code>{
  "name":"morph"
}</code></pre>

		<p>
			If you look at the swagger docs, the definition for <code>UserUpdate</code> is:
		</p>

<pre><code>UserUpdate:
  required:
    - name
  properties:
    name:
      type: string
      example: fred
    type: object</code></pre>

		<p>
			Which is what you are currently passing, but if you have a look at <code>UserAdd</code> you will see an extra parameter:
		</p>

<pre><code>UserAdd:
  required:
    - level
    - name
  properties:
    name:
      type: string
      example: fred
    level:
      type: integer
      example: user
  type: object</code></pre>

		<p>
			Notice the extra <code>level</code> parameter?
		</p>
		<p>
			In situations like this, it is always worth testing to see if extra parameters which exist on similar calls will also work on the one you are working on.
		</p>

		<p>
			To try this, you can either intercept the request in a proxy, or you can modify the JSON before the request is sent to the server. To modify it in the page, you can set a breakpoint in the <code>update_name</code> function, right after the <code>data</code> variable has been created, and modify the variable by using the following in the console:
		</p>

<pre><code>data = JSON.stringify({name: name, level: 0})</code></pre>

		<p>
			If you do this and then check the JSON sent in the PUT request, you should see:
		</p>

<pre><code>{
  name: "hacked",
  level: 0
}</code></pre>

		<p>
			And hopefully a congratulations message.
		</p>
		</div>

		<h3>High Level</h3>
		<p>Import the four health calls into your testing tool of choice and make sure they are running properly. When they are all working, test them for vulnerabilities.</p>

		<p>
		<button id="high_button" onclick="show_answer('high')">Show Answer</button>
		</p>

		<div id="high_answer">
		<p>The connectivity call takes a target parameter and pings it to check for a connection, this is done by calling the OS ping command and is vulnerable to command injection.</p>
		<p>
		For more information on how to exploit this type of issue, see the command injection module.
		</p>
		</div>

		<h3>Impossible Level</h3>
		<p>
			The challenge here is just to get the login process automated in Postman or your tool of choice. Read the documentation and experiment. To help get things working I piped everything through Burp and watched each call as it was made to see if it matched what I expected.
		</p>
		<p>
			When the flow works correctly, the initial login will return an access token and a refresh token along with an <code>expires_in</code> value to say how long the access token is valid for. Once the access token has expired, the refresh token will be sent to the refresh endpoint to generate a new access/refresh token pair.
		</p>
		<p>
			It should be noted that as well as the access token having a fixed lifespan, the refresh token also has a fixed lifespan, once it has expired, the login process has to begin again from scratch.
		</p>
	</div></td>
	</tr>
	</table>

	</div>
	
</div>
