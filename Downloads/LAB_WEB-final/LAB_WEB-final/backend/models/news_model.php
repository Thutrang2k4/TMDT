<?php
require_once __DIR__ . '/../db.php';

function create_slug($string)
{
    $search = array('à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ', 'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ', 'ì', 'í', 'ị', 'ỉ', 'ĩ', 'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ', 'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ', 'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ', 'đ', 'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ', 'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ', 'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ', 'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ', 'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ', 'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ', 'Đ', ' ');
    $replace = array('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y', 'd', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'Y', 'Y', 'Y', 'Y', 'Y', 'D', '-');
    $string = str_replace($search, $replace, $string);
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\-]/', '', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

function get_all_posts($category_id = null, $keyword = '')
{
    global $conn;
    $sql = "SELECT p.*, c.name as category_name 
            FROM posts p 
            LEFT JOIN post_categories c ON p.category_id = c.id 
            WHERE 1=1";
    if ($category_id !== null)
        $sql .= " AND p.category_id = " . intval($category_id);
    if (!empty($keyword)) {
        $keyword = "%" . $conn->real_escape_string($keyword) . "%";
        $sql .= " AND (p.title LIKE '$keyword' OR p.summary LIKE '$keyword')";
    }
    $sql .= " ORDER BY p.created_at DESC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function get_all_news($keyword = '')
{
    return get_all_posts(null, $keyword);
}

function get_post_by_id($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT p.*, c.name as category_name FROM posts p LEFT JOIN post_categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function create_post($title, $content, $category_id = 1)
{
    global $conn;
    $slug = create_slug($title) . '-' . time();
    $author_id = 1;
    $status = 'published';
    $summary = substr(strip_tags($content), 0, 150) . '...';
    $stmt = $conn->prepare("INSERT INTO posts (category_id, author_id, title, slug, summary, content, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisssss", $category_id, $author_id, $title, $slug, $summary, $content, $status);
    return $stmt->execute();
}

function update_post($id, $title, $content)
{
    global $conn;
    $slug = create_slug($title);
    $summary = substr(strip_tags($content), 0, 150) . '...';
    $stmt = $conn->prepare("UPDATE posts SET title = ?, slug = ?, summary = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $slug, $summary, $content, $id);
    return $stmt->execute();
}

function delete_post($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function get_comments_by_post_id($post_id)
{
    global $conn;
    $sql = "SELECT c.*, u.full_name, u.avatar 
            FROM post_comments c 
            LEFT JOIN users u ON c.user_id = u.id 
            WHERE c.post_id = ? AND c.status = 'approved' 
            ORDER BY c.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function add_post_comment($post_id, $user_id, $content, $rating)
{
    global $conn;
    $status = 'approved';

    $stmt = $conn->prepare("INSERT INTO post_comments (post_id, user_id, content, rating, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisis", $post_id, $user_id, $content, $rating, $status);

    return $stmt->execute();
}

function delete_comment($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM post_comments WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
