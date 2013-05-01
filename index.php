<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );

require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();

$page[ 'title' ] .= $page[ 'title_separator' ].'Welcome';

$page[ 'page_id' ] = 'home';

$page[ 'body' ] .= "

<div class=\"body_padded\">

	<h1>Welcome to Damn Vulnerable Web App!</h1>

	<p>Damn Vulnerable Web App (DVWA) is a PHP/MySQL web application that is damn vulnerable. Its main goals are to be an aid for security professionals to test their skills and tools in a legal environment, help web developers better understand the processes of securing web applications and aid teachers/students to teach/learn web application security in a class room environment.</p>

		<h2> WARNING! </h2>

		<p>Damn Vulnerable Web App is damn vulnerable! Do not upload it to your hosting provider's public html folder or any internet facing web server as it will be compromised. We recommend downloading and installing ".dvwaExternalLinkUrlGet( 'http://www.apachefriends.org/en/xampp.html','XAMPP' )." onto a local machine inside your LAN which is used solely for testing.</p>

	<h2>Disclaimer</h2>

	<p>We do not take responsibility for the way in which any one uses this application. We have made the purposes of the application clear and it should not be used maliciously. We have given warnings and taken measures to prevent users from installing DVWA on to live web servers. If your web server is compromised via an installation of DVWA it is not our responsibility it is the responsibility of the person/s who uploaded and installed it.</p>

	<h2>General Instructions</h2>

	<p>The help button allows you to view hits/tips for each vulnerability and for each security level on their respective page.</p>
</div>";


dvwaHtmlEcho( $page );

?>
