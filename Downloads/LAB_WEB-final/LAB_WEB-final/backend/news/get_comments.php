<?php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    require_once "../models/news_model.php";

    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

    if ($post_id <= 0) {
        throw new Exception("ID bài viết không hợp lệ");
    }

    $comments = get_comments_by_post_id($post_id);

    echo json_encode(["success" => true, "comments" => $comments]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
