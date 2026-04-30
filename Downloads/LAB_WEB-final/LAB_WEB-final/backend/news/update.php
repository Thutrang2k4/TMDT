<?php
header('Content-Type: application/json');
require_once "../models/news_model.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid method"]);
    exit;
}

$id = $_POST["id"] ?? 0;
$title = $_POST["title"] ?? '';
$content = $_POST["content"] ?? '';

if (!$id || empty($title)) {
    echo json_encode(["success" => false, "message" => "Thiếu dữ liệu"]);
    exit;
}

// Handle Image Upload
$thumbnail = null;
if (!empty($_FILES["thumbnail"]["name"])) {
    $target_dir = "../../../uploads/news/";
    if (!file_exists($target_dir))
        mkdir($target_dir, 0777, true);

    $extension = strtolower(pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid("news_") . "." . $extension;

    if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_dir . $new_filename)) {
        $thumbnail = $new_filename;
    }
}

$result = update_news($id, $title, $content, $thumbnail);

echo json_encode([
    "success" => $result,
    "message" => $result ? "Cập nhật thành công" : "Cập nhật thất bại"
]);
