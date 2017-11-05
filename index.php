<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Welcome' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'home';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Welcome to Damn Vulnerable Web Application!</h1>
	<p>Damn Vulnerable Web Application (DVWA) is a PHP/MySQL web application that is damn vulnerable. Its main goal is to be an aid for security professionals to test their skills and tools in a legal environment, help web developers better understand the processes of securing web applications and to aid both students & teachers to learn about web application security in a controlled class room environment.</p>
	<p>The aim of DVWA is to <em>practice some of the most common web vulnerabilities</em>, with <em>various levels of difficultly</em>, with a simple straightforward interface.</p>
	<hr />
	<br />

	<h2>General Instructions</h2>
	<p>It is up to the user how they approach DVWA. Either by working through every module at a fixed level, or selecting any module and working up to reach the highest level they can before moving onto the next one. There is not a fixed object to complete a module; however users should feel that they have successfully exploited the system as best as they possible could by using that particular vulnerability.</p>
	<p>Please note, there are <em>both documented and undocumented vulnerability</em> with this software. This is intentional. You are encouraged to try and discover as many issues as possible.</p>
	<p>DVWA also includes a Web Application Firewall (WAF), PHPIDS, which can be enabled at any stage to further increase the difficulty. This will demonstrate how adding another layer of security may block certain malicious actions. Note, there are also various public methods at bypassing these protections (so this can be seen as an extension for more advanced users)!</p>
	<p>There is a help button at the bottom of each page, which allows you to view hints & tips for that vulnerability. There are also additional links for further background reading, which relates to that security issue.</p>
	<hr />
	<br />

	<h2>WARNING!</h2>
	<p>Damn Vulnerable Web Application is damn vulnerable! <em>Do not upload it to your hosting provider's public html folder or any Internet facing servers</em>, as they will be compromised. It is recommend using a virtual machine (such as " . dvwaExternalLinkUrlGet( 'https://www.virtualbox.org/','VirtualBox' ) . " or " . dvwaExternalLinkUrlGet( 'https://www.vmware.com/','VMware' ) . "), which is set to NAT networking mode. Inside a guest machine, you can downloading and install " . dvwaExternalLinkUrlGet( 'https://www.apachefriends.org/en/xampp.html','XAMPP' ) . " for the web server and database.</p>
	<br />
	<h3>Disclaimer</h3>
	<p>We do not take responsibility for the way in which any one uses this application (DVWA). We have made the purposes of the application clear and it should not be used maliciously. We have given warnings and taken measures to prevent users from installing DVWA on to live web servers. If your web server is compromised via an installation of DVWA it is not our responsibility it is the responsibility of the person/s who uploaded and installed it.</p>
	<hr />
	<br />

	<h2>More Training Resources</h2>
	<p>DVWA aims to cover the most commonly seen vulnerabilities found in today's web applications. However there are plenty of other issues with web applications. Should you wish to explore any additional attack vectors, or want more difficult challenges, you may wish to look into the following other projects:</p>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'http://www.itsecgames.com/', 'bWAPP') . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'http://sourceforge.net/projects/mutillidae/files/mutillidae-project/', 'NOWASP') . " (formerly known as " . dvwaExternalLinkUrlGet( 'http://www.irongeek.com/i.php?page=mutillidae/mutillidae-deliberately-vulnerable-php-owasp-top-10', 'Mutillidae' ) . ")</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/OWASP_Broken_Web_Applications_Project', 'OWASP Broken Web Applications Project
') . "</li>
	</ul>
	<hr />
	<br />
</div>";

dvwaHtmlEcho( $page );

?>
