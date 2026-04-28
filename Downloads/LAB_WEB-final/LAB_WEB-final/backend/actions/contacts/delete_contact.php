<?php
header('Content-Type: application/json');
require_once '../../db.php'; // đường dẫn đến file kết nối DB

$id = $_GET['id'] ?? 0;
$id = (int)$id; // ép kiểu an toàn

if ($id <= 0) {
    echo json_encode(["success" => false, "msg" => "ID không hợp lệ"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "msg" => $stmt->error]);
}
