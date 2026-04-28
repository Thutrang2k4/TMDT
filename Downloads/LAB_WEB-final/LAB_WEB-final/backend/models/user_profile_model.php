<?php
/**
 * User Model
 * Xử lý các thao tác liên quan đến user
 */

require_once __DIR__ . '/../db.php';

/**
 * Lấy thông tin user theo ID
 */
function getUserById($user_id) {
    global $conn;
    
    $user_id = intval($user_id);
    $sql = "SELECT u.id, u.full_name, u.email, u.avatar, u.created_at, r.name as role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id 
            WHERE u.id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return false;
}

/**
 * Cập nhật thông tin user
 */
function updateUserProfile($user_id, $full_name) {
    global $conn;
    
    $user_id = intval($user_id);
    $full_name = $conn->real_escape_string(trim($full_name));
    
    if (empty($full_name)) {
        return false;
    }
    
    $sql = "UPDATE users SET full_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("si", $full_name, $user_id);
    
    if ($stmt->execute()) {
        return true;
    }
    
    return false;
}

/**
 * Cập nhật avatar của user
 */
function updateUserAvatar($user_id, $avatar_path) {
    global $conn;
    
    $user_id = intval($user_id);
    $avatar_path = $conn->real_escape_string($avatar_path);
    
    $sql = "UPDATE users SET avatar = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("si", $avatar_path, $user_id);
    
    if ($stmt->execute()) {
        return true;
    }
    
    return false;
}

/**
 * Kiểm tra email đã tồn tại chưa
 */
function isEmailExists($email, $exclude_user_id = null) {
    global $conn;
    
    $email = $conn->real_escape_string(trim($email));
    
    if ($exclude_user_id) {
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $exclude_user_id);
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

/**
 * Lấy danh sách tất cả users (cho admin)
 */
function getAllUsers($page = 1, $per_page = 10) {
    global $conn;
    
    $offset = ($page - 1) * $per_page;
    
    $sql = "SELECT u.id, u.full_name, u.email, u.avatar, u.created_at, r.name as role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id 
            ORDER BY u.created_at DESC 
            LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    return $users;
}

/**
 * Đếm tổng số users
 */
function countTotalUsers() {
    global $conn;
    
    $sql = "SELECT COUNT(*) as total FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    return $row['total'];
}
?>
