<?php

$message = "";

$html .= "
	<p>
		Rather than try to develop a perfect API, there is a different type of challenge for this level.
	</p>
	<p>
		The order system uses <a href='https://oauth.net/2/'>OAuth 2.0</a> for authentication. Being able to automate using this in your tools will greatly help with efficiency, removing the need to manually login and copy access tokens around. Use this level to practice setting up OAuth 2.0 in your testing tool of choice, for me this is <a href='https://www.postman.com/'>Postman</a> which is then proxied through <a href='https://portswigger.net/burp'>Burp</a>, but you can pick whatever tools are most appropriate for your testing environment.
	</p>
	<p>
		Here are some guides that might help:
</p>
<ul>
<li><a href='https://learning.postman.com/docs/sending-requests/authorization/oauth-20/'>Authenticate with OAuth 2.0 authentication in Postman</a></li>
<li><a href='https://blog.postman.com/what-is-oauth-2-0/'>What is OAuth 2.0?</a></li>
</ul>

";

?>
