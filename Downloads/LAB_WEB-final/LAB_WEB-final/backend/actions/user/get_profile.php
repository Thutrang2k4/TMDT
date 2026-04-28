<?php
/**
 * Get Profile - Lấy thông tin user hiện tại
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

$user_id = $_SESSION['user_id'];

// Lấy thông tin user
$user = getUserById($user_id);

if ($user) {
    // Xóa thông tin nhạy cảm
    unset($user['password_hash']);
    
    // Sửa đường dẫn avatar để load được từ frontend
    // Chuyển đường dẫn tuyệt đối thành tương đối
    if ($user['avatar']) {
        // Nếu avatar bắt đầu bằng /, chuyển thành đường dẫn tương đối
        if (strpos($user['avatar'], '/') === 0) {
            // Loại bỏ phần đầu đến /uploads/ và thêm ../
            $user['avatar'] = preg_replace('#^/[^/]+/#', '../', $user['avatar']);
        }
    }
    
    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Không tìm thấy thông tin người dùng!'
    ]);
}
?>
