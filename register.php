<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
include 'db_connect.php';
session_start();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $full_name = trim($data['full_name']);
    $email = trim($data['email']);
    $phone = trim($data['phone']);
    $vehicleno = trim($data['vehicleno']);
    $vehiclecol = trim($data['vehiclecol']);
    $vehicletype = trim($data['vehicletype']);
    $password = $data['password'];
    $confirm_password = $data['confirm_password'];

    if (empty($full_name) || empty($email) || empty($phone) || empty($vehicleno) || empty($vehiclecol) || empty($vehicletype) || empty($password) || empty($confirm_password)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit();
    }

    if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid phone number"]);
        exit();
    }

    if ($password !== $confirm_password) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Passwords do not match"]);
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email already registered."]);
        exit;
    }
    $stmt->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, vehicleno, vehiclecol, vehicletype, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $full_name, $email, $phone, $vehicleno, $vehiclecol, $vehicletype, $hashed_password);

    if ($stmt->execute()) {
        $user_id = $conn->insert_id;
        $token = bin2hex(random_bytes(16));
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['token'] = $token;

        echo json_encode([
        "success" => true,
        "message" => "Registration successful.",
        "token" => $token,
        "role" => "user"
]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
}
?>
