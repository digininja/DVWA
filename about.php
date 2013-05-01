<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'About';
$page[ 'page_id' ] = 'about';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>About</h1>

	<p>
	Version ".dvwaVersionGet()." (Release date: ".dvwaReleaseDateGet().")
	<br /><br />
	DVWA is a RandomStorm OpenSource project. All material is copyright 2008-2011 RandomStorm & Ryan Dewhurst.
	</p>

	<h2>Links</h2>

	<ul>
		<li>Homepage: ".dvwaExternalLinkUrlGet( 'http://www.dvwa.co.uk/' )."</li>
		<li>Project Home: ".dvwaExternalLinkUrlGet( 'http://code.google.com/p/dvwa/' )."</li>
		<li>Issues: ".dvwaExternalLinkUrlGet( 'http://code.google.com/p/dvwa/issues/list' )."</li>
		<li>SVN: ".dvwaExternalLinkUrlGet( 'http://dvwa.googlecode.com/svn/trunk/' )."</li>

	</ul>

	<h2>Credits</h2>

	<ul>
		<li>Craig: ".dvwaExternalLinkUrlGet( 'http://www.youreadmyblog.info/','www.youreadmyblog.info' )."</li>
		<li>Jamesr: ".dvwaExternalLinkUrlGet( 'http://www.creativenucleus.com/','www.creativenucleus.com' )." / ".dvwaExternalLinkUrlGet( 'http://www.designnewcastle.co.uk/','www.designnewcastle.co.uk' )."</li>
		<li>Ryan Dewhurst: ".dvwaExternalLinkUrlGet( 'http://www.ethicalhack3r.co.uk/','www.ethicalhack3r.co.uk' )."</li>
		<li>Tedi Heriyanto: ".dvwaExternalLinkUrlGet( 'http://tedi.heriyanto.net/','http://tedi.heriyanto.net' )."</li>
		<li>Tom Mackenzie: ".dvwaExternalLinkUrlGet( 'http://www.tmacuk.co.uk/','www.tmacuk.co.uk' )."</li>
		<li>RandomStorm: ".dvwaExternalLinkUrlGet( 'http://www.randomstorm.com/','www.randomstorm.com' )."</li>
		<li>Jason Jones: ".dvwaExternalLinkUrlGet( 'http://www.linux-ninja.com/','www.linux-ninja.com' )."</li>
		<li>Brooks Garrett: ".dvwaExternalLinkUrlGet( 'http://brooksgarrett.com/','www.brooksgarrett.com' )."</li>
	</ul>

	<ul>
		<li>PHPIDS - Copyright (c) 2007 ".dvwaExternalLinkUrlGet( 'http://php-ids.org/', 'PHPIDS group' )."</li>
	</ul>

	<h2>License</h2>

	<p>Damn Vulnerable Web App (DVWA) is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.</p>

	<p>The PHPIDS library is included, in good faith, with this DVWA distribution. The operation of PHPIDS is provided without support from the DVWA team. It is licensed under <a href=\"".DVWA_WEB_PAGE_TO_ROOT."instructions.php?doc=PHPIDS-license\">separate terms</a> to the DVWA code.</p>

	<h2>Development</h2>

	<p>Everyone is welcome to contribute and help make DVWA as successful as it can be. All contributors can have their name and link (if they wish) placed in the credits section. To contribute pick an Issue from the Project Home to work on or submit a patch to the Issues list.</p>
	

</div>
";


dvwaHtmlEcho( $page );
exit;

?>
