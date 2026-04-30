<?php
session_start();
require_once '../../db.php';
require_once '../../models/user_model.php';
require_once '../../auth/check_role.php';

// Kiểm tra quyền admin
protect_page('admin');

// Lấy user_id từ URL
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$user_id) {
    header("Location: ../../frontend/admin/users.php?error=" . urlencode("ID người dùng không hợp lệ"));
    exit;
}

// Lấy thông tin người dùng
$user = get_user_by_id($conn, $user_id);

if (!$user) {
    header("Location: ../../frontend/admin/users.php?error=" . urlencode("Người dùng không tồn tại"));
    exit;
}

// Kiểm tra không xoá chính mình
if ($user_id == $_SESSION['user_id']) {
    header("Location: ../../frontend/admin/users.php?error=" . urlencode("Không thể xoá tài khoản của chính bạn"));
    exit;
}

// Xoá người dùng
if (delete_user($conn, $user_id)) {
    header("Location: ../../frontend/admin/users.php?message=" . urlencode("Xoá người dùng thành công"));
    exit;
} else {
    header("Location: ../../frontend/admin/users.php?error=" . urlencode("Lỗi khi xoá người dùng"));
    exit;
}
