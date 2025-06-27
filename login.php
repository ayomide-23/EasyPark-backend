<?php
session_start();
include "db_connect.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['email'], $data['password'])) {
    echo json_encode(["success" => false, "message" => "Missing email or password"]);
    exit();
}

$email = trim($data['email']);
$password = $data['password'];

$stmt = $conn->prepare("SELECT email, password FROM admins WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($admin_email, $admin_password);
    $stmt->fetch();

    if ($password === $admin_password) {
        $_SESSION['email'] = $admin_email;
        $_SESSION['role'] = 'admin';
        $_SESSION['token'] = $token;
        $token = bin2hex(random_bytes(16));
        $update = $conn->prepare("UPDATE admins SET token = ? WHERE email = ?");
        $update->bind_param("ss", $token, $admin_email);
        $update->execute();
        echo json_encode([
        "success" => true,
        "message" => "Admin login successful",
        "role" => "admin",
        "token" => $token
    ]);

        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Incorrect admin password"]);
        exit();
    }
}
$stmt->close();

$stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $full_name, $user_email, $user_password_hash);
    $stmt->fetch();

    if (password_verify($password, $user_password_hash)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['full_name'] = $full_name;
        $_SESSION['email'] = $user_email;
        $_SESSION['role'] = 'user';
        $token = bin2hex(random_bytes(32));
        $update = $conn->prepare("UPDATE users SET token = ? WHERE id = ?");
        $update->bind_param("si", $token, $id);
        $update->execute();

    echo json_encode([
    "success" => true,
    "message" => "Login successful",
    "role" => "user",
    "token" => $token,  
    "user" => [
        "id" => $id,
        "name" => $full_name,
        "email" => $user_email
    ]
    ]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Incorrect email or password"]);
        exit();
    }
}
$stmt->close();

echo json_encode(["success" => false, "message" => "Account not found"]);
exit();
?>