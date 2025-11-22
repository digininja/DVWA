<?php

// Check if redirect parameter exists and is not empty
if (isset($_GET['redirect']) && $_GET['redirect'] !== '') {

    // Validate destination
    if (strpos($_GET['redirect'], "info.php") !== false) {

        // Safe redirect
        header("Location: " . $_GET['redirect']);
        exit;

    } else {
        // Invalid redirect
        http_response_code(500);
        echo "<p>You can only redirect to the info page.</p>";
        exit;
    }
}

// Missing redirect parameter
http_response_code(500);
echo "<p>Missing redirect target.</p>";
exit;

?>


