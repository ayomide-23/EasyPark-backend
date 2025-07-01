<?php
header("Access-Control-Allow-Origin: https://easy-park-frontend-aderinto-ayomides-projects.vercel.app");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Authorization");
include 'db_connect.php';

$email = $_POST['email'] ?? '';
$new_password = $_POST['new_password'] ?? '';

if (!$email || !$new_password) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit();
}

$hashed = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $hashed, $email);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Reset failed"]);
}
?>
