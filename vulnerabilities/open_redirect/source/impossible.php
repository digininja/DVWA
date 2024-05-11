<?php

$redirects = [
    1 => "info.php?id=1",
    2 => "info.php?id=2",
    99 => "https://digi.ninja",
];

if (!empty($_GET['redirect'])) {
    $redirectId = $_GET['redirect'];
    $target = $redirects[$redirectId] ?? "";
    if ($target !== "") {
        header("Location: " . $target);
        exit;
    } else {
        echo "Unknown redirect target.";
        exit;
    }
}

echo "Missing redirect target.";
exit;
?>
