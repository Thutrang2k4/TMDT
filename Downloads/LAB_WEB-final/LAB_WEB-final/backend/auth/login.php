<?php
session_start();
header('Content-Type: application/json');

// Bật debug khi dev
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../db.php';  // db.php phải tạo $conn

if (!isset($conn) || $conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi kết nối CSDL: ' . ($conn->connect_error ?? 'Không tồn tại $conn')
    ]);
    exit;
}

// Lấy POST
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success'=>false, 'message'=>"Vui lòng nhập đầy đủ email và mật khẩu!"]);
    exit;
}

// Prepared statement
$stmt = $conn->prepare("SELECT id, email, full_name, password_hash, role_id FROM users WHERE email=?");
if (!$stmt) {
    echo json_encode(['success'=>false, 'message'=>"Lỗi SQL: ".$conn->error]);
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['role_name'] = ($user['role_id']==1)?'admin':'member';
        #Redirect
        $redirect = ($user['role_id']==1)?'../frontend/admin/dashboard.php':'../frontend/index.php';
        echo json_encode(['success'=>true, 'redirect'=>$redirect]);
    } else {
        echo json_encode(['success'=>false, 'message'=>"Sai email hoặc mật khẩu!"]);
    }
} else {
    echo json_encode(['success'=>false, 'message'=>"Sai email hoặc mật khẩu!"]);
}

$stmt->close();
$conn->close();
?>
