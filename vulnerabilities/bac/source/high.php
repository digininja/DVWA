<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

// Get current user's ID
$query = "SELECT user_id, role FROM users WHERE user = '" . dvwaCurrentUser() . "' LIMIT 1;";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

// Fetch user data safely
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $current_user_id = intval($row['user_id']);
    $user_role = isset($row['role']) ? $row['role'] : 'user'; // Default to 'user' if role missing
} else {
    $current_user_id = 0;
    $user_role = 'user';
}
// Proper access control implementation
if (isset($_GET['action']) && isset($_GET['user_id'])) {

    if (!preg_match('/^\d+$/', $_GET['user_id'])) {
        $html = "<p>Invalid user ID format. Please enter a number.</p>";
        return;
    }
    
    $id = intval($_GET['user_id']);
    
    // Proper role-based access control
    if ($id === $current_user_id || $user_role === 'admin') {
        // Use prepared statements to prevent SQL injection
        $query = "SELECT first_name, last_name, user_id, avatar FROM users WHERE user_id = ? AND (user_id = ? OR ? = 'admin')";
        $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
        mysqli_stmt_bind_param($stmt, "iis", $id, $current_user_id, $user_role);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Encode output to prevent XSS
            $html = "
                <div class=\"profile-info\">
                    <h3>User Profile</h3>
                    <p>User ID: " . htmlspecialchars($row['user_id']) . "</p>
                    <p>Name: " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</p>
                    <p>Avatar: " . htmlspecialchars($row['avatar']) . "</p>
                </div>";
            
            // Log access for audit
            logAccess($current_user_id, $id, 'view_profile');
        } else {
            $html = "<p>No user found or access denied.</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        $html = "<p>Access denied. Insufficient privileges.</p>" ;
        // Log unauthorized access attempt
        logAccessAttempt($current_user_id, $id, 'unauthorized_profile_access');
    }
}



function logAccess($user_id, $target_id, $action) {
    $query = "INSERT INTO access_log (user_id, target_id, action, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    mysqli_stmt_bind_param($stmt, "iis", $user_id, $target_id, $action);
    mysqli_stmt_execute($stmt);
}

function logAccessAttempt($user_id, $target_id, $action) {
    $query = "INSERT INTO security_log (user_id, target_id, action, timestamp, ip_address) VALUES (?, ?, ?, NOW(), ?)";
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
    $ip = $_SERVER['REMOTE_ADDR'];
    mysqli_stmt_bind_param($stmt, "iiss", $user_id, $target_id, $action, $ip);
    mysqli_stmt_execute($stmt);
}
?>
