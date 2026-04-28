<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kết nối DB
require_once '../../db.php';
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['success'=>false, 'message'=>'Lỗi kết nối CSDL']);
    exit;
}

// Lấy dữ liệu POST
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$subject   = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');


// Validate dữ liệu
if (!$name || !$email || !$subject) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng nhập đầy đủ'
    ]);
    exit;
}



// Chuẩn bị query Insert
$stmt = $conn->prepare("
    INSERT INTO contact_messages (name, email, subject, message)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

// Thực thi
if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Gửi liên hệ thành công!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi khi lưu liên hệ: '.$stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
