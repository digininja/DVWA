<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../');
}

require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(array('authenticated', 'phpids'));
dvwaDatabaseConnect();

$page = dvwaPageNewGrab();
$page['title'] = 'Vulnerability: Broken Access Control' . $page['title_separator'] . $page['title'];
$page['page_id'] = 'bac';
$page['help_button'] = 'bac';
$page['source_button'] = 'bac';

// Check if required tables and columns exist
function checkRequiredTables() {
    $missing = array();
    
    // Check users table columns
    $result = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW COLUMNS FROM users LIKE 'role'");
    if (!$result || mysqli_num_rows($result) == 0) {
        $missing[] = "'role' column in users table";
    }
    
    $result = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW COLUMNS FROM users LIKE 'account_enabled'");
    if (!$result || mysqli_num_rows($result) == 0) {
        $missing[] = "'account_enabled' column in users table";
    }
    
    // Check if access_log table exists
    $result = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW TABLES LIKE 'access_log'");
    if (!$result || mysqli_num_rows($result) == 0) {
        $missing[] = "access_log table";
    }
    
    // Check if security_log table exists
    $result = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW TABLES LIKE 'security_log'");
    if (!$result || mysqli_num_rows($result) == 0) {
        $missing[] = "security_log table";
    }
    
    return $missing;
}

$missingTables = checkRequiredTables();

$html = '';
if (!empty($missingTables)) {
    $html .= "<div class=\"warning-message\" style=\"padding: 10px; margin: 10px; border: 2px solid #ff0000; background-color: #ffebee; color: #c62828;\">";
    $html .= "<h3 style=\"margin-top: 0;\">⚠️ Database Setup Required</h3>";
    $html .= "<p>The following database components are missing:</p>";
    $html .= "<ul style=\"margin-bottom: 10px;\">";
    foreach ($missingTables as $missing) {
        $html .= "<li>$missing</li>";
    }
    $html .= "</ul>";
    $html .= "<p>Please go to the <a href=\"" . DVWA_WEB_PAGE_TO_ROOT . "setup.php\" style=\"color: #c62828; font-weight: bold;\">DVWA Setup</a> page and click on \"Reset / Create Database\" to set up the required database structure.</p>";
    $html .= "</div>";
} else {
    // Only load the vulnerability content if database is properly set up
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

    require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/bac/source/{$vulnerabilityFile}";
}

$page['body'] .= "
<div class=\"body_padded\">
    <h1>Vulnerability: Broken Access Control</h1>

    <div class=\"vulnerable_code_area\">
        <h2>User Profile Access</h2>
        <form action=\"#\" method=\"GET\">
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
		<li>" . dvwaExternalLinkUrlGet('https://owasp.org/Top10/A01_2021-Broken_Access_Control/') . "</li>
		<li>" . dvwaExternalLinkUrlGet('https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/05-Authorization_Testing/02-Testing_for_Bypassing_Authorization_Schema') . "</li>
	</ul>

    
</div>";

dvwaHtmlEcho($page);
?>
