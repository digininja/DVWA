<style>
	pre {
		overflow-x: auto;
		white-space: pre-wrap;
		word-wrap: break-word;
	}
</style>

<div class="body_padded">
	<h1>Help - API Security</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>
		Most modern web apps use some kind of API, either as Single Page Apps (SPAs) or to retrieve data to populate traditional apps. As these APIs are behind the scenes, developers sometimes feel they can cut corners in areas such as authentication, authorisation or data validation. As testers, we can get behind the curtains and directly access these seemingly hidden calls to take advantage of these weaknesses.
		</p>
		<p>
		This module will look at three weaknesses, versioning, mass assignment, and ..... 
		</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Each level has its own objective but the general idea is to exploit weak API implementations.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The call being made by the JavaScript is for version 2 of the endpoint, could there be other, earlier, versions available?</p>
		<p>
		<button id="low_button" onclick="show_answer('low')">Show Answer</button>
		</p>
		<div id="low_answer">
		<p>Either by looking at the JavaScript or watching network traffic, you should notice that there is a call being made to <code>/vulnerabilities/api/v2/user/</code> to retrieve the data used to generate the user table.</p>
		<p>
			As the call is being made against version two (<code>v2</code>) of the endpoint, the obvious thing to try is to see if version one is available, and what it offers. The easiest way to do this is to access it directly in the browser by visiting <a href="//<?=$_SERVER['SERVER_NAME']?>/vulnerabilities/api/v1/user/">/vulnerabilities/api/v1/user/</a>, but sometimes API calls require extra headers or authentication tokens which it is easier to let the site add rather than trying to do it manually. Two ways to do this are to modify the URL used in the JavaScript as the page loads by setting a breakpoint on it and changing it before the request is made, or to intercept the call in a proxy, such as BurpSuite.
		</p>
		<p>
			Whatever approach you try, by accessing version one of the endpoint, you should be able to see the password hashes as part of the data.
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
		<p>The system is using AES-128-CBC which means it is vulnerable to a padding oracle attack.</p>

		<p>
		<button id="high_button" onclick="show_answer('high')">Show Answer</button>
		</p>

		<div id="high_answer">
		<p>Rather than try to explain this here, go read this excelent write up on the attack by Eli Sohl.</p>
		<p><a target="_blank" href="https://www.nccgroup.com/uk/research-blog/cryptopals-exploiting-cbc-padding-oracles/">Cryptopals: Exploiting CBC Padding Oracles</a></p>
		<p>
		If you want to play with this some more, there is a script called <a href="cryptography/source/download_oracle_attack.php" download>oracle_attack.php</a> in the sources directory which runs through the full attack with debug. You can run this either against the DVWA site or it will run locally against its own pretend web server.
		</p>
		</div>

		<h3>Impossible Level</h3>
		<p>You can never say impossible in crypto as something that would take years today could take minutes in the future when a new attack is found or when processing power takes a giant leam forward.</p>
		<p>
		The current recommended alternative to AES-CBC is AES-GCM and so the system uses that here. 256 bit blocks rather than 128 bit blocks are used, and a unique IV used for every message. This may be secure today but who knows what tomorrow brings?
		</p>
	</div></td>
	</tr>
	</table>

	</div>
	
</div>
