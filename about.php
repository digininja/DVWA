<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'About' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'about';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h2>About</h2>
	<p>Damn Vulnerable Web Application (DVWA) is a PHP/MySQL web application that is damn vulnerable. Its main goals are to be an aid for security professionals to test their skills and tools in a legal environment, help web developers better understand the processes of securing web applications and aid teachers/students to teach/learn web application security in a class room environment</p>
	<p>Pre-August 2020, All material is copyright 2008-2015 RandomStorm & Ryan Dewhurst.</p>
	<p>Ongoing, All material is copyright Robin Wood and probably Ryan Dewhurst.</p>

	<h2>Links</h2>
	<ul>
		<li>Project Home: " . dvwaExternalLinkUrlGet( 'https://github.com/digininja/DVWA' ) . "</li>
		<li>Bug Tracker: " . dvwaExternalLinkUrlGet( 'https://github.com/digininja/DVWA/issues' ) . "</li>
		<li>Wiki: " . dvwaExternalLinkUrlGet( 'https://github.com/digininja/DVWA/wiki' ) . "</li>
	</ul>

	<h2>Credits</h2>
	<ul>
		<li>Brooks Garrett: " . dvwaExternalLinkUrlGet( 'http://brooksgarrett.com/','www.brooksgarrett.com' ) . "</li>
		<li>Craig</li>
		<li>g0tmi1k: " . dvwaExternalLinkUrlGet( 'https://blog.g0tmi1k.com/','g0tmi1k.com' ) . "</li>
		<li>Jamesr: " . dvwaExternalLinkUrlGet( 'https://www.creativenucleus.com/','www.creativenucleus.com' ) . "</li>
		<li>Jason Jones</li>
		<li>RandomStorm</li>
		<li>Ryan Dewhurst: " . dvwaExternalLinkUrlGet( 'https://wpscan.com/','wpscan.com' ) . "</li>
		<li>Shinkurt: " . dvwaExternalLinkUrlGet( 'http://www.paulosyibelo.com/','www.paulosyibelo.com' ) . "</li>
		<li>Tedi Heriyanto: " . dvwaExternalLinkUrlGet( 'http://tedi.heriyanto.net/','tedi.heriyanto.net' ) . "</li>
		<li>Tom Mackenzie</li>
		<li>Robin Wood: " . dvwaExternalLinkUrlGet( 'https://digi.ninja/','digi.ninja' ) . "</li>
		<li>Zhengyang Song: " . dvwaExternalLinkUrlGet( 'https://github.com/songzy12/','songzy12' ) . "</li>
	</ul>
	<ul>
		<li>PHPIDS - Copyright (c) 2007 " . dvwaExternalLinkUrlGet( 'http://github.com/PHPIDS/PHPIDS', 'PHPIDS group' ) . "</li>
	</ul>

	<h2>License</h2>
	<p>Damn Vulnerable Web Application (DVWA) is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.</p>
	<p>The PHPIDS library is included, in good faith, with this DVWA distribution. The operation of PHPIDS is provided without support from the DVWA team. It is licensed under <a href=\"" . DVWA_WEB_PAGE_TO_ROOT . "instructions.php?doc=PHPIDS-license\">separate terms</a> to the DVWA code.</p>

	<h2>Development</h2>
	<p>Everyone is welcome to contribute and help make DVWA as successful as it can be. All contributors can have their name and link (if they wish) placed in the credits section. To contribute pick an Issue from the Project Home to work on or submit a patch to the Issues list.</p>
</div>\n";

dvwaHtmlEcho( $page );

exit;

?>
