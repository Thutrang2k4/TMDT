<?php
header('Content-Type: application/json');

// Kết nối DB
require_once '../../db.php';

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Lấy limit từ query string, mặc định 5 nếu không có
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : "6";

// Lấy tour rẻ nhất theo limit
$sql = "SELECT id, title, slug, short_description, price, duration_days, status, created_at, updated_at, image
        FROM tours
        WHERE status = 'active'   -- chỉ lấy tour đang hiển thị
        ORDER BY price ASC
        LIMIT $limit";

$result = $conn->query($sql);

$tours = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tours[] = $row;
    }
}

echo json_encode($tours);
$conn->close();
?>
