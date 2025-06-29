<?php
header("Access-Control-Allow-Origin: https://easy-park-frontend-aderinto-ayomides-projects.vercel.app");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
include 'db_connect.php';

$booking_id = $_POST['booking_id'] ?? null;

if (!$booking_id) {
    echo json_encode(["success" => false, "message" => "Missing booking ID"]);
    exit();
}

// Get the slot_id and user_id from the booking
$stmt = $conn->prepare("SELECT slot_id, user_id FROM bookings WHERE id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $slot_id = $row['slot_id'];
    $user_id = $row['user_id'];

    // Get the slot number (for notification message)
    $getSlot = $conn->prepare("SELECT slot_number FROM parking_slots WHERE id = ?");
    $getSlot->bind_param("i", $slot_id);
    $getSlot->execute();
    $slotResult = $getSlot->get_result();
    $slot = $slotResult->fetch_assoc();
    $slot_number = $slot['slot_number'] ?? 'Unknown';

    // 1. Update booking status
    $updateBooking = $conn->prepare("UPDATE bookings SET status = 'Cancelled' WHERE id = ?");
    $updateBooking->bind_param("i", $booking_id);
    $updateBooking->execute();

    // 2. Set slot to available
    $updateSlot = $conn->prepare("UPDATE parking_slots SET status = 'available' WHERE id = ?");
    $updateSlot->bind_param("i", $slot_id);
    $updateSlot->execute();

    // 3. Send notification
    $message = "You cancelled your booking for slot $slot_number.";
    $notif = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $notif->bind_param("is", $user_id, $message);
    $notif->execute();

    echo json_encode(["success" => true, "message" => "Booking cancelled successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Booking not found"]);
}
?>
