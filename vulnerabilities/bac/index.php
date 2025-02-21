<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../');
}

require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(array('authenticated'));
dvwaDatabaseConnect();

$page = dvwaPageNewGrab();
$page['title'] = 'Vulnerability: Broken Access Control' . $page['title_separator'] . $page['title'];
$page['page_id'] = 'bac';
$page['help_button'] = 'bac';
$page[ 'source_button' ] = 'bac';

$method = 'GET';
$vulnerabilityFile = '';
$securityLevel = dvwaSecurityLevelGet();
switch ($securityLevel) {
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

$html = '';
require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/bac/source/{$vulnerabilityFile}";

$page['body'] .= "
<div class=\"body_padded\">
    <h1>Vulnerability: Broken Access Control</h1>

    <div class=\"vulnerable_code_area\">
        <h2>User Profile Access</h2>
        <form action=\"#\" method=\"{$method}\">
            <p>
                View user profile by ID: 
                <input type=\"text\" name=\"user_id\" value=\"1\">
                <input type=\"submit\" value=\"View Profile\" name=\"action\">
            </p>
        </form>
        {$html}
    </div>

    <br />

    <h2>More Information</h2>
	<ul>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/Top10/A01_2021-Broken_Access_Control/' ) . "</li>
		<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/05-Authorization_Testing/02-Testing_for_Bypassing_Authorization_Schema' ) . "</li>
	</ul>

    
</div>";

dvwaHtmlEcho($page);
?>
