<?php
header('Content-Type: application/json');

require_once '../../db.php';
require_once '../../core/db_helper.php';

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, name, email, subject, message, status, created_at, handled_by 
        FROM contact_messages 
        ORDER BY id DESC";

$stmt = db_query($conn, $sql);

$contacts = [];

if ($stmt) {
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
    }
}

echo json_encode($contacts);
$conn->close();
