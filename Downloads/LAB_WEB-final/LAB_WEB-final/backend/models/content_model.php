<?php
/**
 * Content Model
 * Quản lý nội dung trang public: About (Giới thiệu), FAQ (Hỏi đáp)
 */

require_once __DIR__ . '/../db.php';

// ==================== ABOUT CONTENT (Trang Giới thiệu) ====================

function getAboutContent()
{
    global $conn;
    $sql = "SELECT * FROM about_content WHERE id = 1";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function updateAboutContent($title, $content, $mission, $vision, $image_url, $user_id)
{
    global $conn;

    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $mission = $conn->real_escape_string($mission);
    $vision = $conn->real_escape_string($vision);
    $image_url = $conn->real_escape_string($image_url);
    $user_id = intval($user_id);

    $sql = "UPDATE about_content SET 
            title = '$title',
            content = '$content',
            mission = '$mission',
            vision = '$vision',
            image_url = '$image_url',
            updated_by = $user_id
            WHERE id = 1";

    return $conn->query($sql);
}

// ==================== FAQ (Hỏi đáp) ====================

function getAllFAQs()
{
    global $conn;
    $sql = "SELECT * FROM faqs WHERE is_active = 1 ORDER BY display_order ASC, id DESC";
    $result = $conn->query($sql);
    $faqs = array();
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
    return $faqs;
}

function getFAQsByCategory($category)
{
    global $conn;
    $category = $conn->real_escape_string($category);
    $sql = "SELECT * FROM faqs WHERE category = '$category' AND is_active = 1 ORDER BY display_order ASC, id DESC";
    $result = $conn->query($sql);
    $faqs = array();
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
    return $faqs;
}

function getAllFAQCategories()
{
    global $conn;
    $sql = "SELECT DISTINCT category FROM faqs WHERE is_active = 1 ORDER BY category ASC";
    $result = $conn->query($sql);
    $categories = array();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
    return $categories;
}

// ==================== ADMIN: Quản lý FAQ ====================

function getAllFAQsForAdmin($page = 1, $per_page = 10)
{
    global $conn;
    $offset = ($page - 1) * $per_page;

    $sql = "SELECT * FROM faqs ORDER BY display_order ASC, id DESC LIMIT $per_page OFFSET $offset";
    $result = $conn->query($sql);
    $faqs = array();
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
    return $faqs;
}

function countTotalFAQs()
{
    global $conn;
    $sql = "SELECT COUNT(*) as total FROM faqs";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getFAQById($id)
{
    global $conn;
    $id = intval($id);
    $sql = "SELECT * FROM faqs WHERE id = $id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function addFAQ($question, $answer, $category, $display_order, $is_active, $user_id)
{
    global $conn;

    $question = $conn->real_escape_string($question);
    $answer = $conn->real_escape_string($answer);
    $category = $conn->real_escape_string($category);
    $display_order = intval($display_order);
    $is_active = intval($is_active);
    $user_id = intval($user_id);

    $sql = "INSERT INTO faqs (question, answer, category, display_order, is_active, created_by) 
            VALUES ('$question', '$answer', '$category', $display_order, $is_active, $user_id)";

    return $conn->query($sql);
}

function updateFAQ($id, $question, $answer, $category, $display_order, $is_active)
{
    global $conn;

    $id = intval($id);
    $question = $conn->real_escape_string($question);
    $answer = $conn->real_escape_string($answer);
    $category = $conn->real_escape_string($category);
    $display_order = intval($display_order);
    $is_active = intval($is_active);

    $sql = "UPDATE faqs SET 
            question = '$question',
            answer = '$answer',
            category = '$category',
            display_order = $display_order,
            is_active = $is_active
            WHERE id = $id";

    return $conn->query($sql);
}

function deleteFAQ($id)
{
    global $conn;
    $id = intval($id);
    $sql = "DELETE FROM faqs WHERE id = $id";
    return $conn->query($sql);
}

function searchFAQs($keyword, $page = 1, $per_page = 10)
{
    global $conn;
    $keyword = $conn->real_escape_string($keyword);
    $offset = ($page - 1) * $per_page;

    $sql = "SELECT * FROM faqs 
            WHERE question LIKE '%$keyword%' OR answer LIKE '%$keyword%' OR category LIKE '%$keyword%'
            ORDER BY display_order ASC, id DESC 
            LIMIT $per_page OFFSET $offset";

    $result = $conn->query($sql);
    $faqs = array();
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
    return $faqs;
}

function countSearchFAQs($keyword)
{
    global $conn;
    $keyword = $conn->real_escape_string($keyword);

    $sql = "SELECT COUNT(*) as total FROM faqs 
            WHERE question LIKE '%$keyword%' OR answer LIKE '%$keyword%' OR category LIKE '%$keyword%'";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function toggleFAQStatus($id)
{
    global $conn;
    $id = intval($id);
    $sql = "UPDATE faqs SET is_active = NOT is_active WHERE id = $id";
    return $conn->query($sql);
}
