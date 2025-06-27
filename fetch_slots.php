<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

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