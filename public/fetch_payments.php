<?php
header("Access-Control-Allow-Origin: https://easy-park-frontend-aderinto-ayomides-projects.vercel.app");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Authorization");
include 'db_connect.php';

// Get token from header
$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$authHeader = isset($headers['authorization']) ? $headers['authorization'] : '';

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    echo json_encode(["success" => false, "message" => "Missing or invalid Authorization header"]);
    exit();
}

$token = $matches[1];

// Get user by token
$stmt = $conn->prepare("SELECT id FROM users WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$userResult = $stmt->get_result();
if (!$userResult->num_rows) {
    echo json_encode(["success" => false, "message" => "Invalid token"]);
    exit();
}
$user = $userResult->fetch_assoc();
$user_id = $user['id'];

// Get payment history (join with slots for slot_number)
$query = "SELECT 
    bookings.vehicle_type, bookings.duration, bookings.amount, bookings.payment_reference, bookings.status, bookings.booking_time ,
    parking_slots.slot_number
    FROM bookings
    JOIN parking_slots ON bookings.slot_id = parking_slots.id
    WHERE bookings.user_id = ?
    ORDER BY bookings.booking_time DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$payments = [];
while ($row = $result->fetch_assoc()) {
    $payments[] = $row;
}

echo json_encode(["success" => true, "bookings" => $payments]);
?>
