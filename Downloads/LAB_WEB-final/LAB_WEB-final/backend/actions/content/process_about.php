<?php
/**
 * Xử lý cập nhật nội dung trang About (Giới thiệu)
 * Backend Action - Nhiệm vụ #2
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../auth/check_role.php';
require_once __DIR__ . '/../../models/content_model.php';

// Kiểm tra quyền admin
requireAdmin();

// Xử lý upload hình ảnh
function handleImageUpload($file, $old_image = '')
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return $old_image; // Không có file mới, giữ ảnh cũ
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Kiểm tra loại file
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        return false;
    }

    // Kiểm tra kích thước (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }

    // Tạo tên file unique
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'about_' . time() . '_' . uniqid() . '.' . $extension;
    $upload_dir = __DIR__ . '/../../../frontend/assets/images/';

    // Tạo thư mục nếu chưa có
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $target_path = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Xóa ảnh cũ nếu có
        if (!empty($old_image) && file_exists($upload_dir . basename($old_image))) {
            unlink($upload_dir . basename($old_image));
        }
        return 'assets/images/' . $filename;
    }

    return false;
}

// Xử lý POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $errors = array();

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $mission = trim($_POST['mission'] ?? '');
    $vision = trim($_POST['vision'] ?? '');

    // Validation
    if (empty($title)) {
        $errors[] = "Tiêu đề không được để trống";
    } elseif (strlen($title) > 255) {
        $errors[] = "Tiêu đề không được vượt quá 255 ký tự";
    }

    if (empty($content)) {
        $errors[] = "Nội dung không được để trống";
    }

    if (empty($mission)) {
        $errors[] = "Sứ mệnh không được để trống";
    }

    if (empty($vision)) {
        $errors[] = "Tầm nhìn không được để trống";
    }

    // Xử lý upload ảnh
    $current_content = getAboutContent();
    $image_url = $current_content['image_url'] ?? '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handleImageUpload($_FILES['image'], $image_url);
        if ($upload_result === false) {
            $errors[] = "Upload ảnh thất bại. Vui lòng kiểm tra định dạng và kích thước file (max 5MB)";
        } else {
            $image_url = $upload_result;
        }
    }

    if (empty($errors)) {
        // Lấy user ID từ session
        $user_id = $_SESSION['user_id'] ?? 0;

        // Cập nhật database
        if (updateAboutContent($title, $content, $mission, $vision, $image_url, $user_id)) {
            header("Location: ../../../frontend/admin/manage_about.php?success=updated");
            exit();
        } else {
            $errors[] = "Có lỗi xảy ra khi cập nhật. Vui lòng thử lại";
        }
    }

    // Có lỗi, redirect về với thông báo
    $_SESSION['about_errors'] = $errors;
    $_SESSION['about_form_data'] = $_POST;
    header("Location: ../../../frontend/admin/manage_about.php?error=validation");
    exit();
}

// Nếu không phải POST, redirect về trang quản lý
header("Location: ../../../frontend/admin/manage_about.php");
exit();

