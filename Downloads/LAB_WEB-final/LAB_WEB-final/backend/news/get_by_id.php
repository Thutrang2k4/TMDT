<?php
// Tắt hiển thị lỗi HTML để tránh lỗi JSON ở Frontend
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    // 1. Import Model
    if (!file_exists("../models/news_model.php")) {
        throw new Exception("Không tìm thấy file model news_model.php");
    }
    require_once "../models/news_model.php";

    // 2. Lấy ID
    $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

    if ($id <= 0) {
        throw new Exception("ID không hợp lệ");
    }

    // 3. Gọi hàm lấy dữ liệu (Kiểm tra tên hàm để tránh crash)
    $news = null;

    if (function_exists('get_post_by_id')) {
        // Gọi hàm tên MỚI (ưu tiên)
        $news = get_post_by_id($id);
    } elseif (function_exists('get_news_by_id')) {
        // Gọi hàm tên CŨ (fallback)
        $news = get_news_by_id($id);
    } else {
        throw new Exception("Lỗi Model: Không tìm thấy hàm get_post_by_id trong news_model.php");
    }

    // 4. Trả về kết quả
    if ($news) {
        echo json_encode(["success" => true, "news" => $news]);
    } else {
        echo json_encode(["success" => false, "message" => "Không tìm thấy bài viết"]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Lỗi Server: " . $e->getMessage()
    ]);
}
?>