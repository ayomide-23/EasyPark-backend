<?php
header("Access-Control-Allow-Origin: https://easy-park-frontend-aderinto-ayomides-projects.vercel.app");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Authorization");
include 'db_connect.php';

$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$authHeader = isset($headers['authorization']) ? $headers['authorization'] : '';

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    echo json_encode(["success" => false, "message" => "Missing or invalid Authorization header"]);
    exit();
}

$token = $matches[1];

$stmt = $conn->prepare("SELECT id, full_name, email, phone, vehiclecol, vehicletype, vehicleno, created_at FROM users WHERE token = ?");
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $user['id'],
            "full_name" => $user['full_name'],
            "email" => $user['email'],
            "phone" => $user['phone'],
            "vehicleno" => $user['vehicleno'],
            "vehicletype" => $user['vehicletype'],
            "vehiclecol" => $user['vehiclecol'],
            "created_at" => $user['created_at']
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid token"]);
}
?>
