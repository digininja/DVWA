<?php

$headerCSP = "Content-Security-Policy: script-src 'self' https://pastebin.com hastebin.com www.toptal.com example.com code.jquery.com https://ssl.google-analytics.com https://digi.ninja ;"; // allows js from self, pastebin.com, hastebin.com, jquery, digi.ninja, and google analytics.

header($headerCSP);

# These might work if you can't create your own for some reason
# https://pastebin.com/raw/R570EE00
# https://www.toptal.com/developers/hastebin/raw/cezaruzeka

?>
<?php
if (isset ($_POST['include'])) {
$page[ 'body' ] .= "
	<script src='" . $_POST['include'] . "'></script>
";
}
$page[ 'body' ] .= '
<form name="csp" method="POST">
	<p>You can include scripts from external sources, examine the Content Security Policy and enter a URL to include here:</p>
	<input size="50" type="text" name="include" value="" id="include" />
	<input type="submit" value="Include" />
</form>
<p>
	As Pastebin and Hastebin have stopped working, here are some scripts that may, or may not help.
</p>
<ul>
	<li>https://digi.ninja/dvwa/alert.js</li>
	<li>https://digi.ninja/dvwa/alert.txt</li>
	<li>https://digi.ninja/dvwa/cookie.js</li>
	<li>https://digi.ninja/dvwa/forced_download.js</li>
	<li>https://digi.ninja/dvwa/wrong_content_type.js</li>
</ul>
<p>
	Pretend these are on a server like Pastebin and try to work out why some work and some do not work. Check the help for an explanation if you get stuck.
</p>
';
