<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

// Get current user's ID
$query = "SELECT user_id, first_name FROM users WHERE user = '" . dvwaCurrentUser() . "';";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$user_info = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : ['user_id' => 0, 'first_name' => 'Unknown'];

// Show current user's role for context
$role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : 'regular_user';
$html = "<div class='info-banner'>Current Role: {$role}</div>";

if (isset($_GET['action']) && isset($_GET['user_id'])) {
    if (!preg_match('/^\d+$/', $_GET['user_id'])) {
        $html .= "<p>Invalid user ID format. Please enter a number.</p>";
        exit($html);
    }
    
    $id = $_GET['user_id'];
    
    // "Secure" role-based check (but cookie can be manipulated)
    if ($id == $user_info['user_id'] || $role === 'admin') {
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
                    <!-- Note: User roles are managed through browser cookies -->
                </div>";
        } else {
            $html .= "<p>No user found.</p>";
        }
    } else {
        $html .= "<p>Access denied. Regular users can only view their own profile.</p>";
        $html .= "<p><i>Hint: Check your browser's developer tools to see how roles are managed...</i></p>";
    }
}

// Log access attempts with X-Forwarded-For
if (isset($_GET['user_id'])) {
    $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $log_query = "INSERT INTO security_log (module, action, ip_address, details) VALUES 
                ('bac', 'profile_access', '$ip', 'Role: {$role}, Attempted access to user_id: {$_GET['user_id']}')";
    mysqli_query($GLOBALS["___mysqli_ston"], $log_query);
}

// Set initial role cookie if not exists
if (!isset($_COOKIE['user_role'])) {
    setcookie('user_role', 'regular_user', time() + 3600, '/');
}
?>
