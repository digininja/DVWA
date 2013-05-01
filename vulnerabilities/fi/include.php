<?php

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: File Inclusion</h1>

	<div class=\"vulnerable_code_area\">

		To include a file edit the ?page=index.php in the URL to determine which file is included.

	</div>

	<h2>More info</h2>
	<ul>
		<li>".dvwaExternalLinkUrlGet( 'http://en.wikipedia.org/wiki/Remote_File_Inclusion')."</li>
		<li>".dvwaExternalLinkUrlGet( 'http://www.owasp.org/index.php/Top_10_2007-A3')."</li>
	</ul>
</div>
";

?>
