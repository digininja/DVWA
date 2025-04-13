<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

// Get current user's ID
$query = "SELECT user_id, role FROM users WHERE user = '" . dvwaCurrentUser() . "';";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$row = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : array('user_id' => 0, 'role' => '');
$current_user_id = intval($row['user_id']);
$role = $row['role'];

// Slightly better access control (but still vulnerable)
$html = "";
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    if (!preg_match('/^\d+$/', $_GET['user_id'])) {
        $html .= "<p>Invalid user ID format. Please enter a number.</p>";
    } else {
        $id = intval($_GET['user_id']);
        
        // Check if user exists first
        $check_query = "SELECT user_id FROM users WHERE user_id = $id";
        $check_result = mysqli_query($GLOBALS["___mysqli_ston"], $check_query);
        $user_exists = ($check_result && mysqli_num_rows($check_result) > 0);
        
        if (!$user_exists) {
            $html .= "<p>No user found with ID: {$id}</p>";
        } else {
            // "Secure" check that's still vulnerable
            if (isset($_COOKIE['user_id'])) {
                $cookie_id = intval($_COOKIE['user_id']);
                
                if ($id == $cookie_id) {
                    // Access granted
                    $query = "SELECT first_name, last_name, user_id, avatar FROM users WHERE user_id = $id;";
                    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $html .= "
                            <div class=\"profile-info\">
                                <h3>User Profile</h3>
                                <p>User ID: {$row['user_id']}</p>
                                <p>Name: {$row['first_name']} {$row['last_name']}</p>
                                <p>Avatar: {$row['avatar']}</p>
                                <!-- Hint: Cookies can be modified by users... -->
                            </div>";
                    }
                } else {
                    $html .= "<p>Access denied. You can only view your own profile.</p>";
                }
            } else {
                $html .= "<p>Access denied. No user_id cookie found.</p>";
            }
        }
        
        // Log access attempts
        try {
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
            
            // Log the access attempt
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $target_id = $user_exists ? $id : 0; // Use 0 for non-existent users
            $log_query = "INSERT INTO bac_log (user_id, target_id, ip_address) VALUES 
                        ({$current_user_id}, {$target_id}, '{$ip}')";
            mysqli_query($GLOBALS["___mysqli_ston"], $log_query);
        } catch (Exception $e) {
            // Silently fail if logging doesn't work
        }
    }
}

// Show current user's role for context
$role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : 'regular_user';
$html .= "<div class='info-banner'>Current Role: {$role}</div>";

// Set initial role cookie if not exists
if (!isset($_COOKIE['user_role'])) {
    setcookie('user_role', 'regular_user', time() + 3600, '/');
}
?>
