<?php
//CSP only works in modern browsers Chrome 25+, Firefox 23+, Safari 7+
$headerCSP = "Content-Security-Policy:".
"connect-src 'self' ;". // XMLHttpRequest (AJAX request), WebSocket or EventSource.
"default-src 'self';". // Default policy for loading html elements
"frame-ancestors 'self' ;". //allow parent framing - this one blocks click jacking and ui redress
"frame-src 'none';". // vaid sources for frames
"media-src 'self';". // vaid sources for media (audio and video html tags src)
"object-src 'none'; ". // valid object embed and applet tags src
"script-src 'self';" .
"style-src 'self' 'unsafe-inline';";// allows css from self and inline allows inline css

header($headerCSP);

?>
<?php
if (isset ($_POST['include'])) {
$page[ 'body' ] .= "
	" . $_POST['include'] . "
";
}
$page[ 'body' ] .= '
<form name="csp" method="POST">
	<p>The page makes a call to ' . DVWA_WEB_PAGE_TO_ROOT . '/vulnerabilities/csp/source/jsonp.php to load some code. Modify that page to run your own code.</p>
	<p>1+2+3+4+5=<span id="answer"></span></p>
	<input type="button" id="solve" value="Solve the sum" />
</form>

<script src="source/high.js"></script>
';

