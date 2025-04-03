<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../');
}

require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(array('authenticated', 'phpids'));
dvwaDatabaseConnect();

$page = dvwaPageNewGrab();
$page['title'] = 'Broken Access Control Logs' . $page['title_separator'] . $page['title'];
$page['page_id'] = 'bac';
$page['help_button'] = 'bac';
$page['source_button'] = 'bac';

$page['body'] .= "
<style>
    .log-container {
        max-height: 600px;
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
    .security-notice {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
</style>";

// Get logs from the bac_log table with pagination
$page_num = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 25;
$offset = ($page_num - 1) * $per_page;

// Count total logs
$count_query = "SELECT COUNT(*) as total FROM bac_log";
$count_result = mysqli_query($GLOBALS["___mysqli_ston"], $count_query);
$total_logs = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_logs / $per_page);

// Get logs with pagination
$log_query = "SELECT l.id, l.user_id, l.target_id, l.ip_address, l.timestamp, 
             u1.user as accessor_user, u2.user as target_user 
             FROM bac_log l 
             LEFT JOIN users u1 ON l.user_id = u1.user_id 
             LEFT JOIN users u2 ON l.target_id = u2.user_id 
             ORDER BY l.timestamp DESC LIMIT $offset, $per_page";

$log_result = mysqli_query($GLOBALS["___mysqli_ston"], $log_query);

$html = "<div class='body_padded'>";
$html .= "<h1>Broken Access Control Logs</h1>";
$html .= "<p><a href='" . DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/bac/'>Return to Broken Access Control</a> | ";
$html .= "<a href='" . DVWA_WEB_PAGE_TO_ROOT . "security.php'>Return to Security Settings</a></p>";

$html .= "<div class='security-notice'>";
$html .= "<p><strong>Security Notice:</strong> These logs display the raw IP address from the X-Forwarded-For header without sanitization. ";
$html .= "This is intentionally vulnerable to XSS attacks through header manipulation. In a real application, this would be a serious security issue.</p>";
$html .= "<p>Try setting a malicious X-Forwarded-For header when accessing the Broken Access Control page to see the XSS in action.</p>";
$html .= "</div>";

$html .= "<div class='log-container'>";

if ($log_result && mysqli_num_rows($log_result) > 0) {
    $html .= "<table class='log-table'>";
    $html .= "<tr><th>ID</th><th>Accessor</th><th>Target</th><th>IP Address</th><th>Timestamp</th></tr>";

    while ($log = mysqli_fetch_assoc($log_result)) {
        $target_user = $log['target_user'] ? $log['target_user'] : 'Non-existent User (ID: ' . $log['target_id'] . ')';

        $html .= "<tr>";
        $html .= "<td>{$log['id']}</td>";
        $html .= "<td>{$log['accessor_user']} (ID: {$log['user_id']})</td>";
        $html .= "<td>{$target_user}</td>";
        // Intentionally output the raw IP address without sanitization to demonstrate XSS vulnerability
        $html .= "<td>{$log['ip_address']}</td>";
        $html .= "<td>{$log['timestamp']}</td>";
        $html .= "</tr>";
    }

    $html .= "</table>";

    // Pagination
    if ($total_pages > 1) {
        $html .= "<div class='pagination' style='margin-top: 15px; text-align: center;'>";
        $html .= "<p>Page $page_num of $total_pages</p>";
        $html .= "<p>";
        
        // Previous link
        if ($page_num > 1) {
            $html .= "<a href='?page=" . ($page_num - 1) . "' style='margin: 0 5px;'>Previous</a>";
        }
        
        // Page numbers
        $start_page = max(1, $page_num - 2);
        $end_page = min($total_pages, $page_num + 2);
        
        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $page_num) {
                $html .= "<span style='margin: 0 5px; font-weight: bold;'>$i</span>";
            } else {
                $html .= "<a href='?page=$i' style='margin: 0 5px;'>$i</a>";
            }
        }
        
        // Next link
        if ($page_num < $total_pages) {
            $html .= "<a href='?page=" . ($page_num + 1) . "' style='margin: 0 5px;'>Next</a>";
        }
        
        $html .= "</p>";
        $html .= "</div>";
    }
} else {
    $html .= "<p>No access logs found.</p>";
    $html .= "<p>Try accessing the <a href='" . DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/bac/'>Broken Access Control</a> page first to generate some logs.</p>";
}

$html .= "</div>";

// $html .= "<h2>About the Vulnerability</h2>";
// $html .= "<p>This page demonstrates a common vulnerability where user input (in this case, the X-Forwarded-For header) is displayed without proper sanitization.</p>";
// $html .= "<p>To exploit this vulnerability:</p>";
// $html .= "<ol>";
// $html .= "<li>Use a proxy tool like Burp Suite or browser extensions that can modify headers</li>";
// $html .= "<li>Set the X-Forwarded-For header to contain JavaScript code, for example: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>";
// $html .= "<li>Access the Broken Access Control page</li>";
// $html .= "<li>Return to this log viewer to see the XSS payload execute</li>";
// $html .= "</ol>";
// $html .= "<p>This vulnerability exists because the application directly outputs the IP address value from the database without proper HTML encoding.</p>";

$html .= "</div>";

$page['body'] .= $html;

dvwaHtmlEcho($page);
?>
