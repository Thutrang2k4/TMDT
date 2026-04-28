<?php
session_start();
require_once '../../db.php';
require_once '../../models/user_model.php';
require_once '../../auth/check_role.php';

protect_page('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../../frontend/admin/users.php");
    exit;
}

$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate
if (!$user_id) {
    header("Location: ../../../frontend/admin/users.php?error=" . urlencode("ID người dùng không hợp lệ"));
    exit;
}

if (!$new_password || strlen($new_password) < 6) {
    header("Location: ../../../frontend/admin/user_detail.php?id=$user_id&error=" . urlencode("Mật khẩu phải có ít nhất 6 ký tự"));
    exit;
}

if ($new_password !== $confirm_password) {
    header("Location: ../../../frontend/admin/user_detail.php?id=$user_id&error=" . urlencode("Mật khẩu xác nhận không khớp"));
    exit;
}

// Reset password
if (reset_user_password($conn, $user_id, $new_password)) {
    header("Location: ../../../frontend/admin/user_detail.php?id=$user_id&message=" . urlencode("Đặt lại mật khẩu thành công"));
} else {
    header("Location: ../../../frontend/admin/user_detail.php?id=$user_id&error=" . urlencode("Lỗi khi đặt lại mật khẩu"));
}
exit;
