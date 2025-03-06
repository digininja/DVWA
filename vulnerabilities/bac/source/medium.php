<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

// Get current user's ID
$query = "SELECT user_id FROM users WHERE user = '" . dvwaCurrentUser() . "';";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$current_user_id = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['user_id'] : 0;

// Basic access control check but can be bypassed
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    if (!preg_match('/^\d+$/', $_GET['user_id'])) {
        $html = "<p>Invalid user ID format. Please enter a number.</p>";
        exit;
    }
    
    $id = $_GET['user_id'];
    
    // Attempt to check if user has permission (but implementation is flawed)
    if ($id == $current_user_id || isset($_COOKIE['admin'])) { // Vulnerable to cookie manipulation
        $query = "SELECT first_name, last_name, user_id, avatar FROM users WHERE user_id = $id;";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $html = "
                <div class=\"profile-info\">
                    <h3>User Profile</h3>
                    <p>User ID: {$row['user_id']}</p>
                    <p>Name: {$row['first_name']} {$row['last_name']}</p>
                    <p>Avatar: {$row['avatar']}</p>
                </div>";
        } else {
            $html = "<p>No user found.</p>";
        }
    } else {
        $html = "<p>Access denied. You can only view your own profile (Your ID: {$current_user_id}).</p>";
    }
}
?>
