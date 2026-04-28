<?php
header('Content-Type: application/json');
require_once '../../db.php'; // đường dẫn đến file kết nối DB

// Nhận ID từ AJAX
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Kiểm tra ID
if ($id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "ID chi nhánh không hợp lệ!"
    ]);
    exit;
}

// SQL DELETE
$sql = "DELETE FROM branches WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Đã xóa thành công chi nhánh!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Không thể xóa chi nhánh!"
    ]);
}

$stmt->close();
$conn->close();
?>
