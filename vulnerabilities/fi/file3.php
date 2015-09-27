<?php

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: File Inclusion</h1>
	<div class=\"vulnerable_code_area\">
		<h3>File 3</h3>
		<hr />
		Welcome back <em>" . dvwaCurrentUser() . "</em><br />
		Your IP address is: <em>";
if( array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ))
	$page[ 'body' ] .= $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
else
	$page[ 'body' ] .= "**Missing Header**";
$page[ 'body' ] .= "</em><br />
		Your user-agent address is: <em>{$_SERVER[ 'HTTP_USER_AGENT' ]}</em><br />
		You came form: <em>{$_SERVER[ 'HTTP_REFERER' ]}</em><br />
		I'm hosted at: <em>{$_SERVER[ 'HTTP_HOST' ]}</em><br /><br />
		[<em><a href=\"?page=include.php\">back</a></em>]
	</div>

	<h2>More info</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Remote_File_Inclusion' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Top_10_2007-A3' ) . "</li>
	</ul>
</div>\n";

?>
