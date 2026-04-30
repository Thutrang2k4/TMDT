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

    // 2. Kiểm tra method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Phương thức không hợp lệ");
    }

    $id = $_POST["id"] ?? 0;
    if (!$id) {
        throw new Exception("Thiếu ID bài viết");
    }

    // 3. Gọi hàm xóa (Kiểm tra hàm tồn tại để tránh crash)
    if (!function_exists('delete_post')) {
        throw new Exception("Hàm 'delete_post' chưa được định nghĩa trong Model");
    }

    // Gọi hàm xóa
    $result = delete_post($id);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Xóa thành công!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi Database: Không thể xóa bài viết."]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Lỗi Server: " . $e->getMessage()
    ]);
}

