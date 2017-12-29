<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: JavaScript Attacks' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'javascript';
$page[ 'help_button' ]   = 'javascript';
$page[ 'source_button' ] = 'javascript';

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( $_COOKIE[ 'security' ] ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		break;
}

$message = "";
// Check whwat was sent in to see if it was what was expected
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (array_key_exists ("phrase", $_POST) && array_key_exists ("token", $_POST)) {

		$phrase = $_POST['phrase'];
		$token = $_POST['token'];

		if ($phrase == "success") {
			switch( $_COOKIE[ 'security' ] ) {
				case 'low':
					if ($token == md5(str_rot13("success"))) {
						$message = "<p style='color:red'>Well done!</p>";
					} else {
						$message = "<p>Invalid token.</p>";
					}
					break;
				case 'medium':
					if ($token == strrev("XXsuccessXX")) {
						$message = "<p style='color:red'>Well done!</p>";
					} else {
						$message = "<p>Invalid token.</p>";
					}
					break;
				case 'high':
					$vulnerabilityFile = 'high.php';
					break;
				default:
					$vulnerabilityFile = 'impossible.php';
					break;
			}
		} else {
			$message = "<p>You got the phrase wrong.</p>";
		}
	} else {
		$message = "<p>Missing phrase or token.</p>";
	}
}

$page[ 'body' ] = <<<EOF
<div class="body_padded">
	<h1>Vulnerability: JavaScript Attacks</h1>

	<div class="vulnerable_code_area">
	<p>
		Submit the word "success" to win.
	</p>

	$message

	<form name="low_js" method="post">
		<input type="hidden" name="token" value="" id="token" />
		<label for="phrase">Phrase</label> <input type="text" name="phrase" value="" id="phrase" />
		<input type="submit" name="send" value="Submit" />
	</form>
EOF;

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/javascript/source/{$vulnerabilityFile}";

$page[ 'body' ] .= <<<EOF
	</div>
EOF;

$page[ 'body' ] .= "
	<h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.w3schools.com/js/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://www.youtube.com/watch?v=cs7EQdWO5o0&index=17&list=WL' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://ponyfoo.com/articles/es6-proxies-in-depth' ) . "</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
