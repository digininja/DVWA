<?php

$headerCSP = "Content-Security-Policy: script-src 'self';";

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
	<p>Unlike the high level, this does a JSONP call but does not use a callback, instead it hardcodes the function to call.</p><p>The CSP settings only allow external JavaScript on the local server and no inline code.</p>
	<p>1+2+3+4+5=<span id="answer"></span></p>
	<input type="button" id="solve" value="Solve the sum" />
</form>

<script src="source/impossible.js"></script>
';

