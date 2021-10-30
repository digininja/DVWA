<?php

// Check if the right PHP functions are enabled
$WarningHtml = '';
if( !ini_get( 'allow_url_include' ) ) {
	$WarningHtml .= "<div class=\"warning\">The PHP function <em>allow_url_include</em> is not enabled.</div>";
}
if( !ini_get( 'allow_url_fopen' ) ) {
	$WarningHtml .= "<div class=\"warning\">The PHP function <em>allow_url_fopen</em> is not enabled.</div>";
}


$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: File Inclusion</h1>

	{$WarningHtml}

	<div class=\"vulnerable_code_area\">
		[<em><a href=\"?page=file1.php\">file1.php</a></em>] - [<em><a href=\"?page=file2.php\">file2.php</a></em>] - [<em><a href=\"?page=file3.php\">file3.php</a></em>]
	</div>

	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Remote_File_Inclusion', 'Wikipedia - File inclusion vulnerability' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/stable/4-Web_Application_Security_Testing/07-Input_Validation_Testing/11.1-Testing_for_Local_File_Inclusion', 'WSTG - Local File Inclusion' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/stable/4-Web_Application_Security_Testing/07-Input_Validation_Testing/11.2-Testing_for_Remote_File_Inclusion', 'WSTG - Remote File Inclusion' ) . "</li>
	</ul>
</div>\n";

?>
