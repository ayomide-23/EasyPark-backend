<?php
header("Access-Control-Allow-Origin: https://easy-park-frontend-aderinto-ayomides-projects.vercel.app");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
include 'db_connect.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Missing notification ID"]);
    exit();
}

$stmt = $conn->prepare("UPDATE notifications SET is_read = 1, status = 'read' WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "DB error"]);
}
?>