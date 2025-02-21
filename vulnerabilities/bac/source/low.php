<?php
if (!defined('DVWA_WEB_PAGE_TO_ROOT')) {
    define('DVWA_WEB_PAGE_TO_ROOT', '../../../');
}

// Get current user's ID
$query = "SELECT user_id FROM users WHERE user = '" . dvwaCurrentUser() . "';";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$current_user_id = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['user_id'] : 0;

// No access control checks at all
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    if (!preg_match('/^\d+$/', $_GET['user_id'])) {
        $html = "<p>Invalid user ID format. Please enter a number.</p>";
        exit;
    }
    
    $id = $_GET['user_id'];
    
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
            </div>";
    } else {
        $html = "<p>No user found.</p>";
    }
}
?>
