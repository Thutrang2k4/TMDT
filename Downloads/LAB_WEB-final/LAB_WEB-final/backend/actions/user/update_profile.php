<?php
/**
 * Update Profile - Cập nhật thông tin user
 */

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../models/user_profile_model.php';

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
$full_name = trim($_POST['full_name'] ?? '');

// Validation
if (empty($full_name)) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng nhập họ tên!'
    ]);
    exit();
}

if (strlen($full_name) > 100) {
    echo json_encode([
        'success' => false,
        'message' => 'Họ tên không được vượt quá 100 ký tự!'
    ]);
    exit();
}

// Cập nhật thông tin
if (updateUserProfile($user_id, $full_name)) {
    // Cập nhật session
    $_SESSION['full_name'] = $full_name;
    
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật thông tin thành công!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra khi cập nhật thông tin!'
    ]);
}
?>
