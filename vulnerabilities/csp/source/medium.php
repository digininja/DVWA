<?php
//CSP only works in modern browsers Chrome 25+, Firefox 23+, Safari 7+
$headerCSP = "Content-Security-Policy:".
"connect-src 'self' ;". // XMLHttpRequest (AJAX request), WebSocket or EventSource.
"default-src 'self';". // Default policy for loading html elements
"frame-ancestors 'self' ;". //allow parent framing - this one blocks click jacking and ui redress
"frame-src 'none';". // vaid sources for frames
"media-src 'self';". // vaid sources for media (audio and video html tags src)
"object-src 'none'; ". // valid object embed and applet tags src
"script-src 'self' 'unsafe-inline' 'nonce-TmV2ZXIgZ29pbmcgdG8gZ2l2ZSB5b3UgdXA=';" .
"style-src 'self' 'unsafe-inline';";// allows css from self and inline allows inline css
//Sends the Header in the HTTP response to instruct the Browser how it should handle content and what is whitelisted
//Its up to the browser to follow the policy which each browser has varying support
header($headerCSP);

# https://pastebin.com/raw/R570EE00

?>
<?php
if (isset ($_POST['include'])) {
$page[ 'body' ] .= "
	<script nonce='TmV2ZXIgZ29pbmcgdG8gZ2l2ZSB5b3UgdXA='>alert(1);</script>
";
}
$page[ 'body' ] .= '
<form name="csp" method="POST">
	<p>You can include scripts from external sources, examine the Content Security Policy and enter a URL to include here:</p>
	<input size="50" type="text" name="include" value="" id="include" />
	<input type="submit" value="Include" />
</form>
';
