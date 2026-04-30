<?php
header('Content-Type: application/json');

require_once '../../db.php';
require_once '../../core/db_helper.php';


if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : "6";

$sql = "SELECT id, title, slug, short_description, price, duration_days, status, created_at, updated_at, image
        FROM tours
        WHERE status = 'active'   -- chỉ lấy tour đang hiển thị
        ORDER BY price ASC
        LIMIT $limit";

$stmt = db_query($conn, $sql);

$tours = [];

if ($stmt) {
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tours[] = $row;
        }
    }
}

echo json_encode($tours);
$conn->close();
