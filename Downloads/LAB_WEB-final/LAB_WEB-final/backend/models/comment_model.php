<?php
/**
 * Comment Model - Quản lý bình luận
 */

// Lấy bình luận đã được duyệt cho một tour
function get_comments_by_tour($conn, $tour_id) {
    $sql = "SELECT tc.id, tc.content, tc.rating, tc.created_at, u.full_name as user_name
            FROM tour_comments tc
            LEFT JOIN users u ON tc.user_id = u.id
            WHERE tc.tour_id = ? AND tc.status = 'approved'
            ORDER BY tc.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("i", $tour_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    
    $stmt->close();
    return $comments;
}

// Thêm bình luận mới
function add_comment($conn, $tour_id, $user_id, $content, $rating = null) {
    $sql = "INSERT INTO tour_comments (tour_id, user_id, content, rating, status, created_at) 
            VALUES (?, ?, ?, ?, 'approved', NOW())";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("iisi", $tour_id, $user_id, $content, $rating);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

// Lấy tất cả bình luận của một tour (bao gồm chưa duyệt - dành cho admin)
function get_all_comments_by_tour($conn, $tour_id) {
    $sql = "SELECT tc.id, tc.content, tc.rating, tc.status, tc.created_at, u.full_name as user_name
            FROM tour_comments tc
            LEFT JOIN users u ON tc.user_id = u.id
            WHERE tc.tour_id = ?
            ORDER BY tc.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("i", $tour_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    
    $stmt->close();
    return $comments;
}

// Cập nhật trạng thái bình luận (approve/reject)
function update_comment_status($conn, $comment_id, $status) {
    if (!in_array($status, ['approved', 'rejected', 'pending'])) {
        return false;
    }
    
    $sql = "UPDATE tour_comments SET status = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("si", $status, $comment_id);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

// Xóa bình luận
function delete_comment($conn, $comment_id) {
    $sql = "DELETE FROM tour_comments WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("i", $comment_id);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

// Lấy số lượng bình luận theo trạng thái
function get_comment_stats($conn) {
    $sql = "SELECT status, COUNT(*) as count FROM tour_comments GROUP BY status";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stats = [];
    while ($row = $result->fetch_assoc()) {
        $stats[] = $row;
    }
    
    $stmt->close();
    return $stats;
}
?>