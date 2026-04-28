<?php
session_start();
header('Content-Type: application/json');

// Debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kết nối DB
require_once '../db.php';
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['success'=>false, 'message'=>'Lỗi kết nối CSDL']);
    exit;
}

// Lấy dữ liệu POST
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$full_name || !$email || !$password) {
    echo json_encode(['success'=>false, 'message'=>'Vui lòng nhập đầy đủ thông tin!']);
    exit;
}

// Kiểm tra email đã tồn tại chưa
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    echo json_encode(['success'=>false, 'message'=>'Email đã được đăng ký!']);
    $stmt->close();
    exit;
}
$stmt->close();

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user mới
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash, role_id) VALUES (?, ?, ?, ?)");
$role_id = 2; // member
$stmt->bind_param("sssi", $full_name, $email, $password_hash, $role_id);

if ($stmt->execute()) {
    echo json_encode(['success'=>true, 'redirect'=>'login.php']);
} else {
    echo json_encode(['success'=>false, 'message'=>'Lỗi khi tạo tài khoản: '.$stmt->error]);
}

$stmt->close();
$conn->close();
?>
