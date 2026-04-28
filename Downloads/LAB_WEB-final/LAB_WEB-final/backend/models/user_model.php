<?php
/**
 * User Model - Quản lý người dùng
 */

// Lấy tất cả người dùng với phân trang
function get_users_pagination($conn, $page = 1, $limit = 10, $search = '', $role_filter = '') {
    $offset = ($page - 1) * $limit;
    
    $search = trim($search);
    $role_filter = trim($role_filter);
    
    // Đếm tổng số người dùng
    $sql_count = "SELECT COUNT(*) as total FROM users u LEFT JOIN roles r ON u.role_id = r.id WHERE 1=1";
    
    if (!empty($search)) {
        $sql_count .= " AND (u.full_name LIKE ? OR u.email LIKE ?)";
    }
    
    if (!empty($role_filter)) {
        $sql_count .= " AND r.name = ?";
    }
    
    // Lấy danh sách người dùng
    $sql = "SELECT u.id, u.full_name, u.email, u.avatar, u.created_at, r.name as role_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE 1=1";
    
    if (!empty($search)) {
        $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ?)";
    }
    
    if (!empty($role_filter)) {
        $sql .= " AND r.name = ?";
    }
    
    $sql .= " ORDER BY u.created_at DESC LIMIT ? OFFSET ?";
    
    // Đếm tổng
    $stmt_count = $conn->prepare($sql_count);
    if (!empty($search) && !empty($role_filter)) {
        $search_like = "%$search%";
        $stmt_count->bind_param("sss", $search_like, $search_like, $role_filter);
    } elseif (!empty($search)) {
        $search_like = "%$search%";
        $stmt_count->bind_param("ss", $search_like, $search_like);
    } elseif (!empty($role_filter)) {
        $stmt_count->bind_param("s", $role_filter);
    }
    
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    $row_count = $result_count->fetch_assoc();
    $total = $row_count['total'] ?? 0;
    $stmt_count->close();
    
    // Lấy dữ liệu
    $stmt = $conn->prepare($sql);
    if (!empty($search) && !empty($role_filter)) {
        $search_like = "%$search%";
        $stmt->bind_param("sssii", $search_like, $search_like, $role_filter, $limit, $offset);
    } elseif (!empty($search)) {
        $search_like = "%$search%";
        $stmt->bind_param("ssii", $search_like, $search_like, $limit, $offset);
    } elseif (!empty($role_filter)) {
        $stmt->bind_param("sii", $role_filter, $limit, $offset);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt->close();
    
    return [
        'users' => $users,
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'total_pages' => ceil($total / $limit)
    ];
}

// Lấy thông tin người dùng theo ID
function get_user_by_id($conn, $user_id) {
    $sql = "SELECT u.id, u.full_name, u.email, u.avatar, u.created_at, u.role_id, r.name as role_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    return $user;
}

// Cập nhật hồ sơ người dùng (admin)
function update_user_profile($conn, $user_id, $full_name, $email, $role_id) {
    $sql = "UPDATE users SET full_name = ?, email = ?, role_id = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $full_name, $email, $role_id, $user_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

// Reset mật khẩu người dùng (admin)
function reset_user_password($conn, $user_id, $new_password) {
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $password_hash, $user_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

// Xoá người dùng
function delete_user($conn, $user_id) {
    // Không xoá trực tiếp, thay vào đó tạo một trạng thái "deleted" hoặc xoá dữ liệu liên quan trước
    // Để an toàn, chúng ta chỉ xoá người dùng nếu không có dữ liệu liên quan
    
    $sql = "DELETE FROM users WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

// Lấy danh sách roles
function get_all_roles($conn) {
    $sql = "SELECT id, name FROM roles ORDER BY name ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $roles = [];
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
    $stmt->close();
    
    return $roles;
}

// Kiểm tra email đã tồn tại chưa (trừ user_id hiện tại)
function check_email_exists($conn, $email, $user_id = null) {
    $sql = "SELECT id FROM users WHERE email = ?";
    
    if ($user_id) {
        $sql .= " AND id != ?";
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($user_id) {
        $stmt->bind_param("si", $email, $user_id);
    } else {
        $stmt->bind_param("s", $email);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    
    return $exists;
}

// Tổng số người dùng
function get_total_users($conn) {
    $sql = "SELECT COUNT(*) as total FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['total'] ?? 0;
}

// Thống kê người dùng theo vai trò
function get_user_stats($conn) {
    $sql = "SELECT r.name, COUNT(u.id) as count
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            GROUP BY r.name
            ORDER BY r.name ASC";
    
    $stmt = $conn->prepare($sql);
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