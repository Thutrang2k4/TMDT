<?php
header('Content-Type: application/json');

// Kết nối DB
require_once '../../db.php';

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, name, logo, website, email_support 
        FROM company 
        ORDER BY id DESC";

$result = $conn->query($sql);

$contacts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
}

echo json_encode($contacts);
$conn->close();
?>
