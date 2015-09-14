<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]  .= $page[ 'title_separator' ].'Welcome';
$page[ 'page_id' ] = 'home';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Welcome to Damn Vulnerable Web App!</h1>
	<p>Damn Vulnerable Web App (DVWA) is a PHP/MySQL web application that is damn vulnerable. Its main goals are to be an aid for security professionals to test their skills and tools in a legal environment, help web developers better understand the processes of securing web applications and aid teachers/students to teach/learn web application security in a class room environment.</p>
	<hr />
	<br />

	<h2>WARNING!</h2>
	<p>Damn Vulnerable Web App is damn vulnerable! <em>Do not upload it to your hosting provider's public html folder or any Internet facing servers</em>, as they will be compromised. Its recommend using a virtual machine (such as ".dvwaExternalLinkUrlGet( 'https://www.virtualbox.org/','VirtualBox' )."), which is set to NAT networking mode. Inside a guest machine, you can downloading and install ".dvwaExternalLinkUrlGet( 'https://www.apachefriends.org/en/xampp.html','XAMPP' )." for the web server and database.</p>
	<p>Please note, there are <em>both documented and undocumented vulnerability</em> with this software. This is intentional. You are encouraged to try and discover as many issues as possible.</p>
	<hr />
	<br />

	<h2>Disclaimer</h2>
	<p>We do not take responsibility for the way in which any one uses this application. We have made the purposes of the application clear and it should not be used maliciously. We have given warnings and taken measures to prevent users from installing DVWA on to live web servers. If your web server is compromised via an installation of DVWA it is not our responsibility it is the responsibility of the person/s who uploaded and installed it.</p>
	<hr />
	<br />

	<h2>General Instructions</h2>
	<p>The help button allows you to view hits/tips for each vulnerability and for each security level on their respective page.</p>
</div>";

dvwaHtmlEcho( $page );

?>
