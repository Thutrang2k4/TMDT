<?php
session_start();
require_once '../../db.php';

$id = $_GET["id"] ?? 0;
$admin_id = $_SESSION['user_id'] ?? null;



$stmt = $conn->prepare("UPDATE contact_messages 
                        SET status = 'read', handled_by = ? 
                        WHERE id = ?");
$stmt->bind_param("ii", $admin_id, $id);

$success = $stmt->execute();

echo json_encode(["success" => $success]);

$stmt->close();
$conn->close();

?>