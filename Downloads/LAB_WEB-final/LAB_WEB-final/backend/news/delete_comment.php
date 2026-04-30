<?php
// Tắt hiển thị lỗi ra màn hình để tránh làm hỏng cấu trúc JSON trả về
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    // 1. Import Model
    // Đảm bảo đường dẫn này đúng với cấu trúc thư mục của bạn
    if (!file_exists("../models/news_model.php")) {
        throw new Exception("Không tìm thấy file news_model.php");
    }
    require_once "../models/news_model.php";

    // 2. Kiểm tra Method (Chỉ chấp nhận POST)
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Phương thức không hợp lệ (Phải là POST)");
    }

    // 3. Lấy ID từ dữ liệu gửi lên
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        throw new Exception("ID bình luận không hợp lệ");
    }

    // 4. Kiểm tra xem hàm xóa có tồn tại trong Model không
    if (!function_exists('delete_comment')) {
        throw new Exception("Hàm 'delete_comment' chưa được định nghĩa trong news_model.php");
    }

    // 5. Gọi hàm xóa trong Model
    $result = delete_comment($id);

    // 6. Trả về kết quả JSON
    if ($result) {
        echo json_encode([
            "success" => true,
            "message" => "Đã xóa bình luận thành công."
        ]);
    } else {
        throw new Exception("Lỗi Database: Không thể xóa bình luận này.");
    }

} catch (Exception $e) {
    // Trả về lỗi dưới dạng JSON để Frontend hiển thị alert
    echo json_encode([
        "success" => false,
        "message" => "Lỗi Server: " . $e->getMessage()
    ]);
}
