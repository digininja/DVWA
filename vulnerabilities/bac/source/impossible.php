<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$html = "";
$id = 0;
$current_user_id = 0;

// Most secure implementation with multiple layers of security
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    // 1. Input Validation
    if (!preg_match('/^\d+$/', $_GET['user_id'])) {
        $html .= "<p>Invalid user ID format. Please enter a number.</p>";
    } else {
        $id = intval($_GET['user_id']);

        // 2. Get Current User with Prepared Statement
        $query = "SELECT user_id, role FROM users WHERE user = ? LIMIT 1";
        $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
        if ($stmt) {
            $current_user = dvwaCurrentUser();
            mysqli_stmt_bind_param($stmt, "s", $current_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $current_user_id = intval($row['user_id']);
                $user_role = $row['role'];
                mysqli_stmt_close($stmt);
                
                // 3. Check if target user exists
                $user_exists = false;
                $check_query = "SELECT user_id FROM users WHERE user_id = ? LIMIT 1";
                $check_stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $check_query);
                if ($check_stmt) {
                    mysqli_stmt_bind_param($check_stmt, "i", $id);
                    mysqli_stmt_execute($check_stmt);
                    mysqli_stmt_store_result($check_stmt);
                    $user_exists = (mysqli_stmt_num_rows($check_stmt) > 0);
                    mysqli_stmt_close($check_stmt);
                    
                    if (!$user_exists) {
                        $html .= "<p>No user found with ID: {$id}</p>";
                        // Log the attempt to access non-existent user
                        logAccessAttempt($current_user_id, $id, 'non_existent_user_access');
                    } else if (isRateLimitExceeded($current_user_id)) {
                        // 4. Rate Limiting Check
                        $html .= "<p>Too many requests. Please try again later.</p>";
                    } else {
                        // 5. Access Control Check
                        $can_access = false;
                        if ($current_user_id === $id) {
                            $can_access = true; // Users can always view their own profile
                        } 
                        // elseif ($user_role === 'admin') {
                        //     $can_access = true; // Admins can view all profiles
                        // }

                        if ($can_access) {
                            try {
                                // Secure Data Retrieval
                                $query = "SELECT first_name, last_name, user_id, avatar 
                                            FROM users 
                                            WHERE user_id = ? 
                                            AND (user_id = ? OR ? = 'admin')
                                            LIMIT 1";

                                $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);

                                if (!$stmt) {
                                    $html .= "<p>Database error: " . mysqli_error($GLOBALS["___mysqli_ston"]) . "</p>";
                                } else {
                                    $bind_success = mysqli_stmt_bind_param($stmt, "iis", $id, $current_user_id, $user_role);

                                    if (!$bind_success) {
                                        $html .= "<p>Database error: " . mysqli_stmt_error($stmt) . "</p>";
                                    } else {
                                        $execute_success = mysqli_stmt_execute($stmt);

                                        if (!$execute_success) {
                                            $html .= "<p>Database error: " . mysqli_stmt_error($stmt) . "</p>";
                                        } else {
                                            $result = mysqli_stmt_get_result($stmt);

                                            if (!$result) {
                                                $html .= "<p>Database error: " . mysqli_stmt_error($stmt) . "</p>";
                                            } else if (mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);

                                                // Output Encoding
                                                $html .= "
                                                <div class=\"profile-info\">
                                                  <h3>User Profile</h3>
                                                  <p>User ID: " . htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8') . "</p>
                                                  <p>Name: " . htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') . " " .
                                                                htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') . "</p>
                                                  <p>Avatar: " . htmlspecialchars($row['avatar'], ENT_QUOTES, 'UTF-8') . "</p>
                                                </div>";

                                                // Log Successful Access
                                                logAccessAttempt($current_user_id, $id, 'view_profile_success');
                                            } else {
                                                $html .= "<p>Access denied. Insufficient privileges.</p>";
                                                logSecurityEvent('access_denied', $id, $current_user_id);
                                            }
                                        }
                                    }
                                    mysqli_stmt_close($stmt);
                                }
                            } catch (Exception $e) {
                                $html .= "<p>An error occurred. Please try again later.</p>";
                                logSecurityEvent('system_error', $id, $current_user_id, $e->getMessage());
                            }
                        } else {
                            $html .= "<p>Access denied. Insufficient privileges.</p>";
                            logSecurityEvent('unauthorized_access', $id, $current_user_id);

                            // Security Monitoring
                            checkForSuspiciousActivity($current_user_id, $id);
                        }
                    }
                } else {
                    $html .= "<p>Database error: " . mysqli_error($GLOBALS["___mysqli_ston"]) . "</p>";
                }
            } else {
                $html .= "<p>Authentication error.</p>";
                if (isset($stmt)) {
                    mysqli_stmt_close($stmt);
                }
            }
        } else {
            $html .= "<p>Database error: " . mysqli_error($GLOBALS["___mysqli_ston"]) . "</p>";
        }
    }
}

// Helper Functions
function isRateLimitExceeded($user_id)
{
    $timeframe = 300; // 5 minutes
    $max_attempts = 10;

    // First check if the bac_log table exists
    $check_table = "SHOW TABLES LIKE 'bac_log'";
    $table_exists = mysqli_query($GLOBALS["___mysqli_ston"], $check_table);
    
    if ($table_exists && mysqli_num_rows($table_exists) == 0) {
        // Create the table if it doesn't exist
        $create_table = "CREATE TABLE IF NOT EXISTS bac_log (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) NULL,
            target_id INT(6) NULL,
            ip_address VARCHAR(50) NULL,
            action VARCHAR(50) NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        mysqli_query($GLOBALS["___mysqli_ston"], $create_table);
    }

    $query = "SELECT COUNT(*) as attempt_count 
              FROM bac_log 
              WHERE user_id = ? 
              AND timestamp > DATE_SUB(NOW(), INTERVAL ? SECOND)";

    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $timeframe);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row['attempt_count'] >= $max_attempts;
    }
    return false; // If there was an error, don't rate limit
}

function logAccessAttempt($user_id, $target_id, $action)
{
    // Ensure target_id is a valid integer
    $target_id = intval($target_id);
    
    // Get IP address
    $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    
    // First check if the bac_log table exists
    $check_table = "SHOW TABLES LIKE 'bac_log'";
    $table_exists = mysqli_query($GLOBALS["___mysqli_ston"], $check_table);
    
    if ($table_exists && mysqli_num_rows($table_exists) == 0) {
        // Create the table if it doesn't exist
        $create_table = "CREATE TABLE IF NOT EXISTS bac_log (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6),
            target_id INT(6),
            ip_address VARCHAR(50),
            action VARCHAR(50),
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        mysqli_query($GLOBALS["___mysqli_ston"], $create_table);
    }
    
    $query = "INSERT INTO bac_log (user_id, target_id, ip_address, action) 
              VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiss", $user_id, $target_id, $ip, $action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function logSecurityEvent($action, $target_id, $user_id, $details = '')
{
    // Ensure target_id is a valid integer
    $target_id = intval($target_id);
    
    // Get IP address
    $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    
    // First check if the bac_log table exists
    $check_table = "SHOW TABLES LIKE 'bac_log'";
    $table_exists = mysqli_query($GLOBALS["___mysqli_ston"], $check_table);
    
    if ($table_exists && mysqli_num_rows($table_exists) == 0) {
        // Create the table if it doesn't exist
        $create_table = "CREATE TABLE IF NOT EXISTS bac_log (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6),
            target_id INT(6),
            ip_address VARCHAR(50),
            action VARCHAR(50),
            details TEXT,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        mysqli_query($GLOBALS["___mysqli_ston"], $create_table);
    }
    
    // Check if details column exists
    $check_column = "SHOW COLUMNS FROM bac_log LIKE 'details'";
    $column_exists = mysqli_query($GLOBALS["___mysqli_ston"], $check_column);
    
    if ($column_exists && mysqli_num_rows($column_exists) == 0) {
        // Add details column if it doesn't exist
        $add_column = "ALTER TABLE bac_log ADD COLUMN details TEXT";
        mysqli_query($GLOBALS["___mysqli_ston"], $add_column);
    }
    
    $query = "INSERT INTO bac_log (user_id, target_id, ip_address, action, details) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iisss", $user_id, $target_id, $ip, $action, $details);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function checkForSuspiciousActivity($user_id, $target_id)
{
    $timeframe = 3600; // 1 hour
    $threshold = 5;

    $query = "SELECT COUNT(*) as attempt_count 
              FROM bac_log 
              WHERE user_id = ? 
              AND timestamp > DATE_SUB(NOW(), INTERVAL ? SECOND) 
              AND action = 'unauthorized_access'";

    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $timeframe);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($row['attempt_count'] >= $threshold) {
            // Log high-severity security event
            logSecurityEvent(
                'suspicious_activity',
                $target_id,
                $user_id,
                'Multiple unauthorized access attempts detected'
            );
        }
    }
}
?>