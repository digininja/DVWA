<?php
//CSP only works in modern browsers Chrome 25+, Firefox 23+, Safari 7+
$headerCSP = "Content-Security-Policy:".
"connect-src 'self' ;". // XMLHttpRequest (AJAX request), WebSocket or EventSource.
"default-src 'self';". // Default policy for loading html elements
"frame-ancestors 'self' ;". //allow parent framing - this one blocks click jacking and ui redress
"frame-src 'none';". // vaid sources for frames
"media-src 'self' *.example.com;". // vaid sources for media (audio and video html tags src)
"object-src 'none'; ". // valid object embed and applet tags src
"report-uri https://example.com/violationReportForCSP.php;". //A URL that will get raw json data in post that lets you know what was violated and blocked
"script-src 'self' 'unsafe-inline' https://pastebin.com  example.com code.jquery.com https://ssl.google-analytics.com ;". // allows js from self, jquery and google analytics.  Inline allows inline js
"style-src 'self' 'unsafe-inline';";// allows css from self and inline allows inline css
//Sends the Header in the HTTP response to instruct the Browser how it should handle content and what is whitelisted
//Its up to the browser to follow the policy which each browser has varying support
header($headerCSP);
?>

<script>
alert(1);
</script>

<script src="https://pastebin.com/raw/R570EE00"></script>

Hello

