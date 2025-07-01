<?php
header("Access-Control-Allow-Origin: https://easy-park-frontend-aderinto-ayomides-projects.vercel.app");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
include 'db_connect.php';

$query = "SELECT slot_number, status FROM parking_slots";
$result = $conn->query($query);

$slots = [];
while ($row = $result->fetch_assoc()) {
    $slots[] = [
        'slot_number' => $row['slot_number'],
        'booked' => $row['status'] === 'booked'
    ];
}

echo json_encode([
    "success" => true,
    "slots" => $slots
]);
?>