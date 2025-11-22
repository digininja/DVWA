<?php
/**
 * Secure Authorization Check (High Security Fix)
 * Only admin users with a valid session may access this page.
 */

session_start();

// --- SECURITY: Ensure DVWA core functions exist ---
if (!function_exists('dvwaCurrentUser')) {
    http_response_code(500);
    echo "Server error: DVWA functions missing.";
    exit;
}

// --- SECURITY: Strong current user validation ---
$current_user = dvwaCurrentUser();

// Ensure user is authenticated
if (!isset($_SESSION['username']) || $_SESSION['username'] !== $current_user) {
    http_response_code(403);
    echo "Unauthorised (invalid session)";
    exit;
}

// Ensure user has a valid role stored securely
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    http_response_code(403);
    echo "Unauthorised (admin role required)";
    exit;
}

// Optional: Prevent session fixation
if (!isset($_SESSION['session_token'])) {
    http_response_code(403);
    echo "Unauthorised (no session token)";
    exit;
}

if ($_SESSION['session_token'] !== hash_hmac("sha256", session_id(), DVWA_SECURITY_KEY)) {
    http_response_code(403);
    echo "Unauthorised (invalid session token)";
    exit;
}

// If all checks pass → allow access
?>


