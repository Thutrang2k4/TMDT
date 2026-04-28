<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:  ../../../../frontend/login.php");
    exit;
}
require_once '../../db.php';
require_once '../../models/comment_model.php';

header('Content-Type: application/json');

$tour_id = $_POST['tour_id'] ?? null;
$user_id = $_POST['user_id'] ?? null;
$content = trim($_POST['content'] ?? '');
$rating = $_POST['rating'] ?? null;

if (!$tour_id || !$content) {
    echo json_encode([
        "success" => false,
        "message" => "Thiếu dữ liệu"
    ]);
    exit;
}

$result = add_comment($conn, $tour_id, $user_id, $content, $rating);

$slug = $_POST['slug'] ?? '';

if ($result) {
    header("Location: ../../../../frontend/product-detail.php?slug=" . $slug);
    exit;
} else {
    header("Location: ../../frontend/product-detail.php?slug=" . $slug . "&error=fail");
    exit;
}
$conn->close();
