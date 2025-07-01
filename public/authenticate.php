<?php
header("Access-Control-Allow-Origin: https://easy-park-frontend-aderinto-ayomides-projects.vercel.app");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Authorization");
include 'db_connect.php';

$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    echo json_encode(["success" => false, "message" => "Missing or invalid Authorization header"]);
    exit();
}
$token = $matches[1];

$stmt = $conn->prepare("SELECT id, full_name, email, vehicleno, vehicletype, vehcilecol FROM users WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $user['id'],
            "name" => $user['full_name'],
            "email" => $user['email'],
            "vehicleno" => $user['vehicleno'],
            "vehicletype" => $user['vehicletype'],
            "vehiclecol" => $user['vehiclecol']
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid token"]);
}
?>
