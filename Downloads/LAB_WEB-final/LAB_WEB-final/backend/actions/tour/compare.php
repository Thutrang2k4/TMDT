<?php
require_once '../../db.php';
require_once '../../core/db_helper.php';

$data = json_decode(file_get_contents("php://input"), true);
$ids = $data['tour_ids'] ?? [];

if (empty($ids)) {
    echo json_encode([]);
    exit;
}

$id_list = implode(',', array_map('intval', $ids));

$sql = "SELECT id, title, short_description, price, duration_days,
               place_start, vehicle, day_start, host, hotel
        FROM tours
        WHERE id IN ($id_list)";

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