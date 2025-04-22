<style>
	pre {
		overflow-x: auto;
		white-space: pre-wrap;
		word-wrap: break-word;
	}
</style>

<div class="body_padded">
	<h1>Help - SST Security</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>
Web templating systems are great at separating code and design and allow for simple reuse and easy updates. However, while generally helping to improve site security, when done badly, they can introduce their own variety of vulnerabilities.
		</p>
		<p>
This lab looks at three types of issue, accessing arbitrary templates, template injection, and bad configuration, through these you will access files you should not be able to, read sensitive content, and get a sneak view behind the scenes.
		</p>
		<p>
		All the examples here are based on <a href="https://www.smarty.net/">Smarty</a>, this is not because it is any better or worse than any other options, just that it was the easiest to get installed and working.
		</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Each level has its own objective but the general idea is to exploit the Server Side Template system to access files you should not be able to or to read secret information.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The system decides which template to use based on the <code>template</code> query string parameter, can you get it to use a template you control?</p>
		<p>
		<button id="low_button" onclick="show_answer('low')">Show Answer</button>
		</p>
		<div id="low_answer">
		<p>Use the file upload functionality of the site to upload a file containing the following contents:</p>
		<pre><code>{include file="/etc/passwd"}</code></pre>

		<p>You can then tell the system to use it using the <code>template</code>, for example:</p>
		<p><code>http://dvwa.test/vulnerabilities/sst/smarty/?template=../../../hackable/uploads/x.tpl</code></p>
		<p>Alternatively, as the system will load any file as a template, you could just specify <code>/etc/passwd</code> as the template and that will be loaded directly.
		<p><code>http://dvwa.test/vulnerabilities/sst/smarty/?template=/etc/passwd</code></p>
		</div>

		<h3>Medium Level</h3>
		<p>
			Notice that the description says that the engine has full access to the user object, think about what fields, beyond the ones specified in the list, could be available.
		</p>
		<p>To get some help, you can turn on the <a href="https://www.smarty.net/docsv2/en/chapter.debugging.console.tpl">Debugging Console</a>. You might even find some extra info hidden in there.</p>
		<p>
			<button id="medium_button" onclick="show_answer('medium')">Show Answer</button>
		</p>
		<div id="medium_answer">
		<p>As well as the listed fields, the user object also contains the <code>password</code> field. You can load this into the template by adding this parameter:

<pre><code>Password: {$password}</code></pre>

		</div>

		<h3>High Level</h3>
		<p>
			The developer who set this site up did it in a bit of a rush, they followed the <a href="https://www.smarty.net/quick_install">Quick Install</a> guide but ignored one important step, keeping the Smarty directory out of the web root. Try to guess, or brute force, the directory, and then use information from the guide to read key files.
		</p>
		<p>
			<button id="high_button" onclick="show_answer('high')">Show Answer</button>
		</p>

		<div id="high_answer">
		<p>
			The line you are looking for in the guide is in the Setup Directories section:
		</p>
		<blockquote>
			It is also recommended to place them outside of the web server document root.
		</blockquote>
		<p>
			When the setup directories are in the web root without any additional protections they are able to be accessed by users. In this instance, you are looking for the <code>smarty</code> directory which is under the <code>sst</code> directory. Based on the documention, in this directory you should then find the following subdirectories:
		</p>
<ul>
<li>cache</li>
<li>configs</li>
<li>templates</li>
<li>templates_c</li>
</ul>
<p>
If you browse to the templates directory, you will be able to see all the templates used by the site, as this is the high level, have a look in <code>high.tpl</code>. In here you will find the username and password used access the restricted web directory and the FTP server:
</p>
<ul>
<li>HTTP/Basic Auth: admin / secret</li>
<li>FTP: ftp:secretdvwaaccount@dvwa.com</li>
</ul>
<p>
The missing bit is now the database credentials. To find these, look at the top of the template and you'll see this line:
</p>
<pre><code>{config_load file="dvwa.conf"}</code></pre>
<p>
If you go back to the guide, you will see that the config files live in the <code>configs</code> directory, so have a browse to <code>smarty/configs/dvwa.conf</code> and you will see the credentials.
</p>
<pre><code># hidden section
[.Database]
host=dvwa.test
db=dvwa
user=mydvwauser
pass=Sup3rSecretP455w0rd</code></pre>
<p>
There are other directories, have a look in those and you may find some more interesting content.
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
