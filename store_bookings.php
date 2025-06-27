<?php
include 'db_connect.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$user_id = $_POST['user_id'] ?? null;
$slot_number = $_POST['slot_id'] ?? null;
$vehicle_type = $_POST['vehicle_type'] ?? null;
$duration = $_POST['duration'] ?? null;
$amount = $_POST['amount'] ?? null;
$payment_reference = $_POST['payment_reference'] ?? null;

if (!$user_id || !$slot_number || !$vehicle_type || !$duration || !$amount || !$payment_reference) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit();
}

// Get the numeric slot_id from slot_number
$stmt = $conn->prepare("SELECT id FROM parking_slots WHERE slot_number = ?");
$stmt->bind_param("s", $slot_number);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $slot_id = $row['id'];
} else {
    echo json_encode(["success" => false, "message" => "Invalid slot number"]);
    exit();
}

// Insert into bookings table
$stmt = $conn->prepare("INSERT INTO bookings (user_id, slot_id, vehicle_type, duration, amount, payment_reference) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissis", $user_id, $slot_id, $vehicle_type, $duration, $amount, $payment_reference);

// Insert notification after successful booking
$notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
$notif_message = "You booked slot $slot_number for ₦$amount";
$notif_stmt->bind_param("is", $user_id, $notif_message);
$notif_stmt->execute();


if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Booking saved successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "DB error: " . $stmt->error]);
}
