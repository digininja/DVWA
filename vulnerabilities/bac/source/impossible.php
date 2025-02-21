<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

// Initialize variables
$html = "";
$id = 0;
$current_user_id = 0;

// Most secure implementation with multiple layers of security
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    // 1. Input Validation
    if (!preg_match('/^\d+$/', $_GET['user_id'])) {
        $html = "<p>Invalid user ID format. Please enter a number.</p>";
        return;
    }

    $id = intval($_GET['user_id']);

    // 2. Get Current User with Prepared Statement
    $query = "SELECT user_id, role FROM users WHERE user = ? LIMIT 1";
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    mysqli_stmt_bind_param($stmt, "s", dvwaCurrentUser());
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $current_user_id = intval($row['user_id']);
        $user_role = $row['role'];
    } else {
        $html = "<p>Authentication error.</p>";
        exit;
    }
    mysqli_stmt_close($stmt);

    // 3. Rate Limiting Check
    if (isRateLimitExceeded($current_user_id)) {
        $html = "<p>Too many requests. Please try again later.</p>";
        exit;
    }

    // 4. Access Control Check
    $can_access = false;
    if ($current_user_id === $id) {
        $can_access = true; // Users can always view their own profile
    } elseif ($user_role === 'admin') {
        $can_access = true; // Admins can view all profiles
    }


    if ($can_access) {
        try {
            // Secure Data Retrieval
            $query = "SELECT first_name, last_name, user_id, avatar 
                        FROM users 
                        WHERE user_id = ? 
                        AND (user_id = ? OR ? = 'admin')
                        AND account_enabled = 1
                        LIMIT 1";

            $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);

            if (!$stmt) {
                die("Prepare failed: " . mysqli_error($GLOBALS["___mysqli_ston"]));
            }

            $bind_success = mysqli_stmt_bind_param($stmt, "iis", $id, $current_user_id, $user_role);

            if (!$bind_success) {
                die("Bind failed: " . mysqli_stmt_error($stmt));
            }

            $execute_success = mysqli_stmt_execute($stmt);

            if (!$execute_success) {
                die("Execute failed: " . mysqli_stmt_error($stmt));
            }

            $result = mysqli_stmt_get_result($stmt);

            if (!$result) {
                die("Get result failed: " . mysqli_stmt_error($stmt));
            }

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                // Output Encoding
                $html = "
<div class=\"profile-info\">
  <h3>User Profile</h3>
  <p>User ID: " . htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8') . "</p>
  <p>Name: " . htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') . " " .
                    htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') . "</p>
  <p>Avatar: " . htmlspecialchars($row['avatar'], ENT_QUOTES, 'UTF-8') . "</p>
</div>";

                // Log Successful Access
                logAccess($current_user_id, $id, 'view_profile_success');
            } else {
                $html = "<p>No user found or access denied.</p>";
                logSecurityEvent('access_denied', 'No matching user found', $current_user_id);
            }

            mysqli_stmt_close($stmt);


        } catch (Exception $e) {
            $html = "<p>An error occurred. Please try again later.</p>";
            logSecurityEvent('system_error', $e->getMessage(), $current_user_id);
        }
    } else {
        $html = "<p>Access denied. Insufficient privileges.</p>";
        logSecurityEvent('unauthorized_access', $id, $current_user_id);

        // 8. Security Monitoring
        checkForSuspiciousActivity($current_user_id, $id);
    }
}

// Helper Functions
function isRateLimitExceeded($user_id)
{
    $timeframe = 300; // 5 minutes
    $max_attempts = 10;

    $query = "SELECT COUNT(*) as attempt_count 
              FROM access_log 
              WHERE user_id = ? 
              AND timestamp > DATE_SUB(NOW(), INTERVAL ? SECOND)";

    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $timeframe);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row['attempt_count'] >= $max_attempts;
}

function logAccess($user_id, $target_id, $action)
{
    error_reporting(E_ALL);
ini_set('display_errors', 1);

    $query = "INSERT INTO access_log (user_id, target_id, action, timestamp) 
              VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    mysqli_stmt_bind_param($stmt, "iis", $user_id, $target_id, $action);
    mysqli_stmt_execute($stmt);
}

function logSecurityEvent($action, $target_id, $user_id)
{
    $query = "INSERT INTO security_log (user_id, action, target_id, timestamp, ip_address) 
              VALUES (?, ?, ?, NOW(), ?)";

    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);

    if (!$stmt) {
        die("MySQL prepare error: " . mysqli_error($GLOBALS["___mysqli_ston"]));
    }

    $ip = $_SERVER['REMOTE_ADDR'];
    if (!mysqli_stmt_bind_param($stmt, "isss", $user_id, $action, $target_id, $ip)) {
        die("MySQL bind param error: " . mysqli_stmt_error($stmt));
    }

    if (!mysqli_stmt_execute($stmt)) {
        die("MySQL execute error: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
}


function checkForSuspiciousActivity($user_id, $target_id)
{
    $timeframe = 3600; // 1 hour
    $threshold = 5;

    $query = "SELECT COUNT(*) as attempt_count 
              FROM security_log 
              WHERE user_id = ? 
              AND timestamp > DATE_SUB(NOW(), INTERVAL ? SECOND) 
              AND action = 'unauthorized_access'";

    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $timeframe);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row['attempt_count'] >= $threshold) {
        // Log high-severity security event
        logSecurityEvent(
            'suspicious_activity',
            $target_id,
            $user_id
        );
    }
}
?>