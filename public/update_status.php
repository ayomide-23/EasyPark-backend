<?php
header("Access-Control-Allow-Origin: https://easy-park-frontend-aderinto-ayomides-projects.vercel.app");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Authorization");
include '../db_connect.php';


$slot_number = $_POST['slot_number'] ?? '';

if (!$slot_number) {
    echo json_encode(["success" => false, "message" => "Missing slot number"]);
    exit();
}

$stmt = $conn->prepare("UPDATE parking_slots SET status = 'booked' WHERE slot_number = ?");
$stmt->bind_param('s', $slot_number);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Slot status updated"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update slot"]);
}
?>
 