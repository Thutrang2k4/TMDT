<?php
// Tệp: backend/actions/tour/process_admin_tour.php - Điều phối các hành động CRUD Tour

require_once '../../db.php';
require_once '../../models/tour_model.php';
require_once '../../auth/check_role.php'; // Đảm bảo Admin đã đăng nhập

// Bảo vệ bằng hàm kiểm tra quyền
protect_page('admin');

// 1. Lấy hành động được yêu cầu (add, edit, delete, update_status)
$action = $_POST['action'] ?? $_GET['action'] ?? null;
$redirect_to = '../../../frontend/admin/products.php';
$message = '';
$error = '';

if (!isset($conn)) {
    $error = "Lỗi kết nối CSDL.";
    header("Location: $redirect_to?error=" . urlencode($error));
    exit;
}

/**
 * Chuyển đổi chuỗi tiếng Việt có dấu thành không dấu.
 * Dùng cho mục đích tạo slug.
 */
function remove_vietnamese_diacritics($string)
{
    // Bảng chuyển đổi từ ký tự có dấu sang không dấu
    $search = array(
        'á',
        'à',
        'ả',
        'ã',
        'ạ',
        'ă',
        'ắ',
        'ằ',
        'ẳ',
        'ẵ',
        'ặ',
        'â',
        'ấ',
        'ầ',
        'ẩ',
        'ẫ',
        'ậ',
        'é',
        'è',
        'ẻ',
        'ẽ',
        'ẹ',
        'ê',
        'ế',
        'ề',
        'ể',
        'ễ',
        'ệ',
        'í',
        'ì',
        'ỉ',
        'ĩ',
        'ị',
        'ó',
        'ò',
        'ỏ',
        'õ',
        'ọ',
        'ô',
        'ố',
        'ồ',
        'ổ',
        'ỗ',
        'ộ',
        'ơ',
        'ớ',
        'ờ',
        'ở',
        'ỡ',
        'ợ',
        'ú',
        'ù',
        'ủ',
        'ũ',
        'ụ',
        'ư',
        'ứ',
        'ừ',
        'ử',
        'ữ',
        'ự',
        'ý',
        'ỳ',
        'ỷ',
        'ỹ',
        'ỵ',
        'đ',
        'Á',
        'À',
        'Ả',
        'Ã',
        'Ạ',
        'Ă',
        'Ắ',
        'Ằ',
        'Ẳ',
        'Ẵ',
        'Ặ',
        'Â',
        'Ấ',
        'Ầ',
        'Ẩ',
        'Ẫ',
        'Ậ',
        'É',
        'È',
        'Ẻ',
        'Ẽ',
        'Ẹ',
        'Ê',
        'Ế',
        'Ề',
        'Ể',
        'Ễ',
        'Ệ',
        'Í',
        'Ì',
        'Ỉ',
        'Ĩ',
        'Ị',
        'Ó',
        'Ò',
        'Ỏ',
        'Õ',
        'Ọ',
        'Ô',
        'Ố',
        'Ồ',
        'Ổ',
        'Ỗ',
        'Ộ',
        'Ơ',
        'Ớ',
        'Ờ',
        'Ở',
        'Ỡ',
        'Ợ',
        'Ú',
        'Ù',
        'Ủ',
        'Ũ',
        'Ụ',
        'Ư',
        'Ứ',
        'Ừ',
        'Ử',
        'Ữ',
        'Ự',
        'Ý',
        'Ỳ',
        'Ỷ',
        'Ỹ',
        'Ỵ',
        'Đ'
    );

    $replace = array(
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'e',
        'i',
        'i',
        'i',
        'i',
        'i',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'u',
        'y',
        'y',
        'y',
        'y',
        'y',
        'd',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'E',
        'E',
        'E',
        'E',
        'E',
        'E',
        'E',
        'E',
        'E',
        'E',
        'E',
        'I',
        'I',
        'I',
        'I',
        'I',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'U',
        'U',
        'U',
        'U',
        'U',
        'U',
        'U',
        'U',
        'U',
        'U',
        'U',
        'Y',
        'Y',
        'Y',
        'Y',
        'Y',
        'D'
    );

    return str_replace($search, $replace, $string);
}

if ($action === 'add' || $action === 'edit') {
    // 2. Lấy dữ liệu từ POST và làm sạch
    $data = [
        'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
        'short_description' => filter_input(INPUT_POST, 'short_description', FILTER_SANITIZE_STRING),
        'price' => filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT),
        'duration_days' => filter_input(INPUT_POST, 'duration_days', FILTER_VALIDATE_INT),
        'status' => filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING),
    ];

    // Kiểm tra dữ liệu bắt buộc
    if (empty($data['title']) || empty($data['price']) || empty($data['duration_days'])) {
        $error = "Vui lòng điền đầy đủ thông tin.";
        header("Location: $redirect_to?error=" . urlencode($error));
        exit;
    }

    // Kiểm tra status hợp lệ
    if (empty($data['status']) || !in_array($data['status'], ['active', 'inactive'])) {
        $data['status'] = 'active';
    }

    // Tạo slug 
    // 1. Lấy dữ liệu
    $title = $data['title'];

    // 1. Loại bỏ dấu tiếng Việt
    $slug = remove_vietnamese_diacritics($title);

    // 2. Chuyển sang chữ thường
    $slug = strtolower($slug);

    // 3. Thay thế các ký tự không hợp lệ bằng dấu gạch nối
    // Bây giờ slug chỉ chứa ký tự không dấu
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

    // 4. Loại bỏ dấu gạch nối thừa ở đầu và cuối
    $slug = trim($slug, '-');

    // Gán slug vào data array
    $data['slug'] = $slug;

    if ($action === 'add') {
        $result = add_tour($conn, $data);
        if ($result) {
            $message = "Thêm tour thành công.";
        } else {
            $error = "Lỗi khi thêm tour.";
        }
    } elseif ($action === 'edit') {
        $id = filter_input(INPUT_POST, 'tour_id', FILTER_VALIDATE_INT);
        if (!$id) {
            $error = "Dữ liệu không hợp lệ.";
        } else {
            $result = update_tour($conn, $id, $data);
            if ($result) {
                $message = "Cập nhật tour thành công.";
            } else {
                $error = "Lỗi khi cập nhật tour.";
            }
        }
    }
} elseif ($action === 'delete') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        $error = "Dữ liệu không hợp lệ.";
    } else {
        $result = delete_tour($conn, $id);
        if ($result) {
            $message = "Xóa tour thành công.";
        } else {
            $error = "Lỗi khi xóa tour.";
        }
    }
}

$conn->close();

// Chuyển hướng về trang Tour (products.php) với thông báo
if (!empty($message)) {
    header("Location: $redirect_to?message=" . urlencode($message));
    exit;
}

if (!empty($error)) {
    header("Location: $redirect_to?error=" . urlencode($error));
    exit;
}

// Nếu không có action hoặc action không hợp lệ
header("Location: $redirect_to");
exit;
