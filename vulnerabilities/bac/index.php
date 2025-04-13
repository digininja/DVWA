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

// Create required tables if they don't exist
function setupRequiredTables()
{
    // Create bac_log table if it doesn't exist
    $query = "CREATE TABLE IF NOT EXISTS bac_log (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) NULL,
        target_id INT(6) NULL,
        ip_address VARCHAR(50) NULL,
        action VARCHAR(50) NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($GLOBALS["___mysqli_ston"], $query);

    // Add role column to users table if it doesn't exist
    $result = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW COLUMNS FROM users LIKE 'role'");
    if (!$result || mysqli_num_rows($result) == 0) {
        mysqli_query($GLOBALS["___mysqli_ston"], "ALTER TABLE users ADD COLUMN role VARCHAR(10) DEFAULT 'user'");
        // Set admin role for admin user
        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET role = 'admin' WHERE user = 'admin'");
    }
}

// Setup tables
setupRequiredTables();

// Handle log viewing
$html = '';

// Add log viewing functionality
$html .= "<div class='log-container'>";
$html .= "<h3>Access Log</h3>";

// Get logs from the bac_log table
$log_query = "SELECT l.id, l.user_id, l.target_id, l.ip_address, l.timestamp, 
                 u1.user as accessor_user, u2.user as target_user 
                 FROM bac_log l 
                 LEFT JOIN users u1 ON l.user_id = u1.user_id 
                 LEFT JOIN users u2 ON l.target_id = u2.user_id 
                 ORDER BY l.timestamp DESC LIMIT 50";

$log_result = mysqli_query($GLOBALS["___mysqli_ston"], $log_query);

if ($log_result && mysqli_num_rows($log_result) > 0) {
    $html .= "<table class='log-table'>";
    $html .= "<tr><th>ID</th><th>Accessor</th><th>Target</th><th>IP Address</th><th>Timestamp</th></tr>";

    while ($log = mysqli_fetch_assoc($log_result)) {
        $target_user = $log['target_user'] ? $log['target_user'] : 'Non-existent User (ID: ' . $log['target_id'] . ')';

        $html .= "<tr>";
        $html .= "<td>{$log['id']}</td>";
        $html .= "<td>{$log['accessor_user']} (ID: {$log['user_id']})</td>";
        $html .= "<td>{$target_user}</td>";
        $html .= "<td>{$log['ip_address']}</td>";
        $html .= "<td>{$log['timestamp']}</td>";
        $html .= "</tr>";
    }

    $html .= "</table>";
} else {
    $html .= "<p>No access logs found.</p>";
}

$html .= "</div>";

// Load the vulnerability content
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

// Add CSS for logs
$page['body'] .= "
<style>
    .log-container {
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 20px;
    }
    .log-table {
        width: 100%;
        border-collapse: collapse;
    }
    .log-table th, .log-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .log-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .log-table th {
        padding-top: 12px;
        padding-bottom: 12px;
        background-color: #4a4a4a;
        color: white;
    }
    .info-banner {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        padding: 8px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
</style>";

$page['body'] .= "
<div class=\"body_padded\">
    <h1>Vulnerability: Broken Access Control</h1>

    <div class=\"vulnerable_code_area\">
        ";

$page['body'] .= "
        <h2>User Profile Access</h2>
        <form action=\"#\" method=\"GET\">
            <p>
                View user profile by ID: 
                <input type=\"text\" name=\"user_id\" value=\"1\">
                <input type=\"submit\" value=\"View Profile\" name=\"action\">
            </p>
        </form>
        
       
        
        {$html}
       ";


$page['body'] .= "
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