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
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$role_id = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);

// Validate
if (!$user_id) {
    header("Location: ../../../frontend/admin/users.php?error=" . urlencode("ID người dùng không hợp lệ"));
    exit;
}

if (!$full_name || !$email || !$role_id) {
    header("Location: ../../../frontend/admin/user_detail.php?id=$user_id&error=" . urlencode("Vui lòng nhập đầy đủ thông tin"));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../../../frontend/admin/user_detail.php?id=$user_id&error=" . urlencode("Email không hợp lệ"));
    exit;
}

if (check_email_exists($conn, $email, $user_id)) {
    header("Location: ../../../frontend/admin/user_detail.php?id=$user_id&error=" . urlencode("Email này đã được sử dụng"));
    exit;
}

// Cập nhật
if (update_user_profile($conn, $user_id, $full_name, $email, $role_id)) {
    header("Location: ../../../frontend/admin/user_detail.php?id=$user_id&message=" . urlencode("Cập nhật thông tin thành công"));
} else {
    header("Location: ../../../frontend/admin/user_detail.php?id=$user_id&error=" . urlencode("Lỗi khi cập nhật thông tin"));
}
exit;
