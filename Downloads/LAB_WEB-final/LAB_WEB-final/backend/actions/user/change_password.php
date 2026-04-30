<?php
/**
 * Change Password - Đổi mật khẩu user
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Bạn chưa đăng nhập!'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Method không hợp lệ!'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng nhập đầy đủ thông tin!'
    ]);
    exit();
}

if (strlen($new_password) < 8) {
    echo json_encode([
        'success' => false,
        'message' => 'Mật khẩu mới phải có ít nhất 8 ký tự!'
    ]);
    exit();
}

if ($new_password !== $confirm_password) {
    echo json_encode([
        'success' => false,
        'message' => 'Mật khẩu xác nhận không khớp!'
    ]);
    exit();
}

// Kiểm tra mật khẩu hiện tại
$sql = "SELECT password_hash FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Người dùng không tồn tại!'
    ]);
    exit();
}

$user = $result->fetch_assoc();

// Verify current password
if (!password_verify($current_password, $user['password_hash'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Mật khẩu hiện tại không đúng!'
    ]);
    exit();
}

// Hash new password
$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Update password
$sql = "UPDATE users SET password_hash = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $new_password_hash, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Đổi mật khẩu thành công!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra khi đổi mật khẩu!'
    ]);
}

$stmt->close();
$conn->close();

