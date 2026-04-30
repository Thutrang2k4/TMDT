<?php
// Tắt hiển thị lỗi HTML để tránh làm hỏng JSON
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    // 1. Kiểm tra file model
    if (!file_exists("../models/news_model.php")) {
        throw new Exception("Không tìm thấy file news_model.php");
    }
    require_once "../models/news_model.php";

    // 2. Kiểm tra hàm
    if (!function_exists('create_post')) {
        throw new Exception("Model chưa cập nhật hàm 'create_post'.");
    }

    // 3. Kiểm tra method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Method không hợp lệ");
    }

    // 4. Lấy dữ liệu
    $title = $_POST["title"] ?? '';
    $content = $_POST["content"] ?? '';

    if (empty($title) || empty($content)) {
        throw new Exception("Vui lòng nhập tiêu đề và nội dung");
    }

    // 5. Gọi hàm tạo bài viết
    $result = create_post($title, $content, 1);

    $response = [];
    if ($result) {
        $response = ["success" => true, "message" => "Đăng tin thành công!"];
    } else {
        $response = ["success" => false, "message" => "Lỗi Database: Không thể lưu."];
    }

    // 6. Trả về JSON an toàn
    $json = json_encode($response, JSON_UNESCAPED_UNICODE);

    // Nếu encode thất bại (thường do lỗi tiếng Việt), tự tạo chuỗi JSON lỗi
    if ($json === false) {
        echo '{"success": false, "message": "Lỗi mã hóa JSON (UTF-8): ' . json_last_error_msg() . '"}';
    } else {
        echo $json;
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Lỗi Server: " . $e->getMessage()
    ]);
}
