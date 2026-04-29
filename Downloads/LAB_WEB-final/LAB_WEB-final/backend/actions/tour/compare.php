<?php
require_once '../../db.php';

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

$result = $conn->query($sql);

$tours = [];

while ($row = $result->fetch_assoc()) {
    $tours[] = $row;
}

echo json_encode($tours);