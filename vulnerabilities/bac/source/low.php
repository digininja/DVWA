<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

// Get current user's ID
$query = "SELECT user_id FROM users WHERE user = '" . dvwaCurrentUser() . "';";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$current_user_id = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['user_id'] : 0;

// Basic attempt at access control (but easily bypassed)
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    if (!preg_match('/^\d+$/', $_GET['user_id'])) {
        $html = "<p>Invalid user ID format. Please enter a number.</p>";
        exit($html);  // Fixed to actually show the error
    }
    
    $id = $_GET['user_id'];
    
    // "Secure" check that's easily bypassed
    if (isset($_GET['token']) && $_GET['token'] == 'user_token') {
        $query = "SELECT first_name, last_name, user_id, avatar FROM users WHERE user_id = '$id';";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $html = "
                <div class=\"profile-info\">
                    <h3>User Profile</h3>
                    <p>User ID: {$row['user_id']}</p>
                    <p>Name: {$row['first_name']} {$row['last_name']}</p>
                    <p>Avatar: {$row['avatar']}</p>
                    <!-- Hint: This token check isn't very secure... -->
                </div>";
        } else {
            $html = "<p>No user found.</p>";
        }
    } else {
        $html = "<p>Access denied. Valid token required. <!-- Try using token=user_token --></p>";
    }
}

// Log access attempts
if (isset($_GET['user_id'])) {
    $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $log_query = "INSERT INTO security_log (module, action, ip_address, details) VALUES 
                ('bac', 'profile_access', '$ip', 'Attempted access to user_id: {$_GET['user_id']}')";
    mysqli_query($GLOBALS["___mysqli_ston"], $log_query);
}
?>
