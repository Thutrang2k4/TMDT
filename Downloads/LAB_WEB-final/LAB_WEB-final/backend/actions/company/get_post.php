<?php
header('Content-Type: application/json');
require_once '../../db.php';

// Nếu truyền limit thì lấy số đó, nếu không truyền thì không giới hạn
$limitClause = isset($_GET['limit']) ? "LIMIT " . intval($_GET['limit']) : "";

// Lấy bài viết mới nhất, chỉ status = published
$sql = "SELECT id, category_id, author_id, title, slug, summary, content, status, published_at, created_at
        FROM posts
        WHERE status = 'published'
        ORDER BY published_at DESC
        $limitClause";

$result = $conn->query($sql);

$articles = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}

echo json_encode($articles);
$conn->close();
?>
