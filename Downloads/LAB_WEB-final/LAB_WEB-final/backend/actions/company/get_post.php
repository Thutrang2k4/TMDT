<?php
header('Content-Type: application/json');
require_once '../../db.php';
require_once '../../core/db_helper.php';

$limitClause = isset($_GET['limit']) ? "LIMIT " . intval($_GET['limit']) : "";

$sql = "SELECT id, category_id, author_id, title, slug, summary, content, status, published_at, created_at
        FROM posts
        WHERE status = 'published'
        ORDER BY published_at DESC
        $limitClause";

$stmt = db_query($conn, $sql);

$articles = [];

if ($stmt) {
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
    }
}

echo json_encode($articles);
$conn->close();
