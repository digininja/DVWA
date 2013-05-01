<?php
define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'Source';

$id = $_GET[ 'id' ];

$lowsrc = @file_get_contents("./{$id}/source/low.php");
$lowsrc = str_replace( array( '$html .=' ), array( 'echo' ), $lowsrc);
$lowsrc = highlight_string($lowsrc, true);

$medsrc = @file_get_contents("./{$id}/source/medium.php");
$medsrc = str_replace( array( '$html .=' ), array( 'echo' ), $medsrc);
$medsrc = highlight_string($medsrc, true);

$highsrc = @file_get_contents("./{$id}/source/high.php");
$highsrc = str_replace( array( '$html .=' ), array( 'echo' ), $highsrc);
$highsrc = highlight_string($highsrc, true);

if ($id == 'fi'){
	$vuln = 'File Inclusion';
}
elseif ($id == 'brute'){
	$vuln = 'Brute Force';
}
elseif ($id == 'csrf'){
	$vuln = 'CSRF';
}
elseif ($id == 'exec'){
	$vuln = 'Command Execution';
}
elseif ($id == 'sqli'){
	$vuln = 'SQL Injection';
}
elseif ($id == 'sqli_blind'){
	$vuln = 'SQL Injection (Blind)';
}
elseif ($id == 'upload'){
	$vuln = 'File Upload';
}
elseif ($id == 'xss_r'){
	$vuln = 'Reflected XSS';
}
elseif ($id == 'xss_s'){
	$vuln = 'Stored XSS';
}



$page[ 'body' ] .= " 

	<div class=\"body_padded\">
	<h1>".$vuln."</h1>
	
	<br />
	
	<h3>High ".$vuln." Source</h3>
	
	<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
	<tr>
	<td><div id=\"code\">".$highsrc."</div></td>
	</tr>
	</table>
	
	<br />
	
	<h3>Medium ".$vuln." Source</h3>
	
	<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
	<tr>
	<td><div id=\"code\">".$medsrc."</div></td>
	</tr>
	</table>
	
	<br />
	
	<h3>Low ".$vuln." Source</h3>
	
	<table width='100%' bgcolor='white' style=\"border:2px #C0C0C0 solid\">
	<tr>
	<td><div id=\"code\">".$lowsrc."</div></td>
	</tr>
	</table>
	
	<br />
	<br />
	
	<FORM><INPUT TYPE=\"button\" VALUE=\"<-- Back\" onClick=\"history.go(-1);return true;\"> </FORM> 
	
	</div>
	
";

dvwaSourceHtmlEcho( $page );
?>
