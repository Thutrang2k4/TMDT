<?php
header('Content-Type: application/json');

require_once '../../db.php';
require_once '../../core/db_helper.php';

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, name, logo, website, email_support 
        FROM company 
        ORDER BY id DESC";

$stmt = db_query($conn, $sql);

$contacts = [];

if ($stmt) {
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $contacts[] = $result->fetch_assoc();
    }
}

echo json_encode($contacts);
$conn->close();