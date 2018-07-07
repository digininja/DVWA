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

header($headerCSP);

// Disable XSS protections so that inline alert boxes will work
header ("X-XSS-Protection: 0");

# <script nonce="TmV2ZXIgZ29pbmcgdG8gZ2l2ZSB5b3UgdXA=">alert(1)</script>

?>
<?php
if (isset ($_POST['include'])) {
$page[ 'body' ] .= "
	" . $_POST['include'] . "
";
}
$page[ 'body' ] .= '
<form name="csp" method="POST">
	<p>Whatever you enter here gets dropped directly into the page, see if you can get an alert box to pop up.</p>
	<input size="50" type="text" name="include" value="" id="include" />
	<input type="submit" value="Include" />
</form>
';
