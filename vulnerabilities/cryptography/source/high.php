<?php
header("Content-Type: application/json");

// Enable debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

define("SECRET_KEY", "STRONG_SECRET_2025_HMAC_KEY");

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['token']) || empty($input['token'])) {
    echo json_encode([
        "status" => 400,
        "message" => "No token provided."
    ]);
    exit;
}

$token = $input['token'];
$decoded = base64_decode($token);

if (!$decoded || strpos($decoded, ".") === false) {
    echo json_encode([
        "status" => 400,
        "message" => "Invalid token format."
    ]);
    exit;
}

list($payload, $sig) = explode(".", $decoded, 2);

// Verify signature
if (hash_hmac("sha256", $payload, SECRET_KEY) !== $sig) {
    echo json_encode([
        "status" => 403,
        "message" => "Token tampering detected!"
    ]);
    exit;
}

$data = json_decode($payload, true);

if (!$data) {
    echo json_encode([
        "status" => 400,
        "message" => "Invalid token payload."
    ]);
    exit;
}

// Prevent privilege escalation
$level = intval($data["level"]);
if ($level < 1) $level = 1;

echo json_encode([
    "status" => 200,
    "user"   => $data["user"],
    "level"  => ($level == 1 ? "user" : "admin"),
    "message" => "Token validated."
]);
exit;
?>
