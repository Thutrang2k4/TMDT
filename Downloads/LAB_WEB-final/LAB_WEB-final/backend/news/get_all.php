<?php
header('Content-Type: application/json');
require_once "../models/news_model.php";

try {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $news = get_all_news($search);

    echo json_encode([
        "success" => true,
        "news" => $news
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Lỗi Server: " . $e->getMessage()
    ]);
}