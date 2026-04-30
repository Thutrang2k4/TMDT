<?php
session_start();
require_once '../../db.php';
require_once '../../models/user_model.php';
require_once '../../auth/check_role.php';

// Kiểm tra quyền admin
protect_page('admin');

$message = '';
$error = '';

// Lấy user_id từ URL
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$user_id) {
    header("Location: ../../../frontend/admin/users.php?error=" . urlencode("ID người dùng không hợp lệ"));
    exit;
}

// Lấy thông tin người dùng
$user = get_user_by_id($conn, $user_id);

if (!$user) {
    header("Location: ../../../frontend/admin/users.php?error=" . urlencode("Người dùng không tồn tại"));
    exit;
}

// Xử lý POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role_id = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);

        // Validate
        if (!$full_name || !$email || !$role_id) {
            $error = "Vui lòng nhập đầy đủ thông tin";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email không hợp lệ";
        } elseif (check_email_exists($conn, $email, $user_id)) {
            $error = "Email này đã được sử dụng";
        } else {
            if (update_user_profile($conn, $user_id, $full_name, $email, $role_id)) {
                $message = "Cập nhật hồ sơ thành công";
                // Lấy lại dữ liệu
                $user = get_user_by_id($conn, $user_id);
            } else {
                $error = "Lỗi khi cập nhật hồ sơ";
            }
        }
    } elseif ($action === 'reset_password') {
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validate
        if (!$new_password || strlen($new_password) < 6) {
            $error = "Mật khẩu phải có ít nhất 6 ký tự";
        } elseif ($new_password !== $confirm_password) {
            $error = "Mật khẩu xác nhận không khớp";
        } else {
            if (reset_user_password($conn, $user_id, $new_password)) {
                // Redirect về danh sách người dùng với thông báo
                header("Location: ../../../frontend/admin/users.php?message=" . urlencode("Reset mật khẩu thành công cho người dùng " . htmlspecialchars($user['full_name'])));
                exit;
            } else {
                $error = "Lỗi khi reset mật khẩu";
            }
        }
    }
}

$roles = get_all_roles($conn);
