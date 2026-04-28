<?php
require_once '../../db.php';

$id = $_GET['id'] ?? 0; // id của admin

$stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($full_name);
$stmt->fetch();

echo json_encode([
    'name' => $full_name ?: null
]);

$stmt->close();
$conn->close();
