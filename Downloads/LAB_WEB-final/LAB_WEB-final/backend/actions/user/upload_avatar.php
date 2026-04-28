<?php
/**
 * Upload Avatar - Cập nhật avatar của user
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

// Kiểm tra file upload
if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng chọn file ảnh!'
    ]);
    exit();
}

$file = $_FILES['avatar'];

// Validate file type
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowed_types)) {
    echo json_encode([
        'success' => false,
        'message' => 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)!'
    ]);
    exit();
}

// Validate file size (max 2MB)
if ($file['size'] > 2 * 1024 * 1024) {
    echo json_encode([
        'success' => false,
        'message' => 'Kích thước ảnh tối đa 2MB!'
    ]);
    exit();
}

// Create uploads directory if not exists
$upload_dir = __DIR__ . '/../../../uploads/avatars/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
$upload_path = $upload_dir . $filename;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra khi tải file lên!'
    ]);
    exit();
}

// Save to database
// Lấy tên thư mục gốc từ $_SERVER để tự động
$base_path = dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))); // Từ backend/actions/user lên 3 cấp
$avatar_path = $base_path . '/uploads/avatars/' . $filename;

if (updateUserAvatar($_SESSION['user_id'], $avatar_path)) {
    // Delete old avatar if exists
    $user = getUserById($_SESSION['user_id']);
    if ($user && $user['avatar'] && $user['avatar'] !== $avatar_path) {
        // Chuyển đường dẫn web thành đường dẫn file hệ thống
        $avatar_relative = preg_replace('#^/[^/]+/#', '', $user['avatar']); // Bỏ /project_name/
        $old_file = __DIR__ . '/../../../' . $avatar_relative;
        if (file_exists($old_file)) {
            @unlink($old_file);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật avatar thành công!',
        'avatar_url' => $avatar_path
    ]);
} else {
    // Delete uploaded file if database update fails
    @unlink($upload_path);
    
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra khi cập nhật avatar!'
    ]);
}
?>
