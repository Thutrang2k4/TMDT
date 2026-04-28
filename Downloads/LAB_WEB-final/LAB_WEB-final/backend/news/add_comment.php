<?php
// Tắt hiển thị lỗi HTML để đảm bảo JSON sạch
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    require_once "../models/news_model.php";

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Phương thức không hợp lệ");
    }

    // Lấy dữ liệu
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0; // Lấy từ form (hidden input)
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;

    // Validate cơ bản
    if ($post_id <= 0) throw new Exception("Bài viết không tồn tại.");
    if ($user_id <= 0) throw new Exception("Bạn cần đăng nhập để bình luận.");
    if (empty($content)) throw new Exception("Nội dung bình luận không được để trống.");

    // Gọi model
    $result = add_post_comment($post_id, $user_id, $content, $rating);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Đã gửi bình luận thành công!"]);
    } else {
        throw new Exception("Lỗi Database: Không thể lưu bình luận.");
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>