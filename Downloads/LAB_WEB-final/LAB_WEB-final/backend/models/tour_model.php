<?php
// Tệp: LAB_WEB/backend/models/tour_model.php

// Hàm dùng cho trang Chi tiết Tour của Khách hàng
function get_tour_by_slug($conn, $slug) {
    $sql = "SELECT * FROM tours WHERE slug = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $tour = $result->fetch_assoc();
    $stmt->close();
    return $tour;
}

// Hàm dùng cho trang Quản lý Tour của Admin (có Phân trang)
function get_tours_pagination_admin($conn, $keyword = null, $page = 1, $limit = 10) {
    $offset = ($page - 1) * $limit;
    
    // Đếm tổng số record
    $count_sql = "SELECT COUNT(*) as total FROM tours WHERE 1=1";
    $data_sql = "SELECT * FROM tours WHERE 1=1";
    
    if ($keyword) {
        $keyword_filter = "%" . $keyword . "%";
        $count_sql .= " AND (title LIKE ? OR slug LIKE ?)";
        $data_sql .= " AND (title LIKE ? OR slug LIKE ?)";
    }
    
    // Đếm tổng record
    $count_stmt = $conn->prepare($count_sql);
    if ($count_stmt === false) {
        die("Error preparing count statement (admin): " . $conn->error);
    }
    if ($keyword) {
        $count_stmt->bind_param("ss", $keyword_filter, $keyword_filter);
    }
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_records = $count_result->fetch_assoc()['total'];
    $count_stmt->close();
    
    $total_pages = ceil($total_records / $limit);
    
    // Lấy dữ liệu với LIMIT và OFFSET
    $data_sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $data_stmt = $conn->prepare($data_sql);
    
    if ($data_stmt === false) {
        die("Error preparing data statement (admin): " . $conn->error);
    }
    
    if ($keyword) {
        $data_stmt->bind_param("ssii", $keyword_filter, $keyword_filter, $limit, $offset);
    } else {
        $data_stmt->bind_param("ii", $limit, $offset);
    }
    
    $data_stmt->execute();
    $data_result = $data_stmt->get_result();
    $tours = $data_result->fetch_all(MYSQLI_ASSOC);
    $data_stmt->close();
    
    return [
        'data' => $tours,
        'total_records' => $total_records,
        'total_pages' => $total_pages
    ];
}

// Hàm lấy danh sách Tour cho trang khách hàng (có tìm kiếm và phân trang)
function get_tours_pagination($conn, $keyword = null, $page = 1, $limit = 10) {
    $offset = ($page - 1) * $limit;
    
    // Đếm tổng số record (chỉ lấy tour active)
    $count_sql = "SELECT COUNT(*) as total FROM tours WHERE status = 'active'";
    $data_sql = "SELECT * FROM tours WHERE status = 'active'";
    
    if ($keyword) {
        $keyword_filter = "%" . $keyword . "%";
        $count_sql .= " AND (title LIKE ? OR slug LIKE ?)";
        $data_sql .= " AND (title LIKE ? OR slug LIKE ?)";
    }
    
    // Đếm tổng record
    $count_stmt = $conn->prepare($count_sql);
    if ($count_stmt === false) {
        die("Error preparing count statement: " . $conn->error);
    }
    if ($keyword) {
        $count_stmt->bind_param("ss", $keyword_filter, $keyword_filter);
    }
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_records = $count_result->fetch_assoc()['total'];
    $count_stmt->close();
    
    $total_pages = ceil($total_records / $limit);
    
    // Lấy dữ liệu với LIMIT và OFFSET
    $data_sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $data_stmt = $conn->prepare($data_sql);
    
    if ($data_stmt === false) {
        die("Error preparing data statement (public): " . $conn->error);
    }
    
    if ($keyword) {
        $data_stmt->bind_param("ssii", $keyword_filter, $keyword_filter, $limit, $offset);
    } else {
        $data_stmt->bind_param("ii", $limit, $offset);
    }
    
    $data_stmt->execute();
    $data_result = $data_stmt->get_result();
    $tours = $data_result->fetch_all(MYSQLI_ASSOC);
    $data_stmt->close();
    
    return [
        'data' => $tours,
        'total_records' => $total_records,
        'total_pages' => $total_pages
    ];
}

// Hàm lấy dữ liệu Tour theo ID (dùng cho trang Sửa)
function get_tour_by_id($conn, $id) {
    $sql = "SELECT * FROM tours WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tour = $result->fetch_assoc();
    $stmt->close();
    return $tour;
}

// Hàm Thêm Tour mới
function add_tour($conn, $data) {
    $sql = "INSERT INTO tours (title, slug, short_description, price, duration_days, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param(
        "sssidi",
        $data['title'],
        $data['slug'],
        $data['short_description'],
        $data['price'],
        $data['duration_days'],
        $data['status']
    );
    
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Hàm Cập nhật Tour
function update_tour($conn, $id, $data) {
    $sql = "UPDATE tours 
            SET title = ?, slug = ?, short_description = ?, price = ?, duration_days = ?, status = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param(
        "sssidsi",
        $data['title'],
        $data['slug'],
        $data['short_description'],
        $data['price'],
        $data['duration_days'],
        $data['status'],
        $id
    );
    
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Hàm Xóa Tour
function delete_tour($conn, $id) {
    // Xóa các đơn hàng liên quan trước
    $sql_delete_orders = "DELETE FROM orders WHERE tour_id = ?";
    $stmt = $conn->prepare($sql_delete_orders);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // Xóa bình luận liên quan
    $sql_delete_comments = "DELETE FROM tour_comments WHERE tour_id = ?";
    $stmt = $conn->prepare($sql_delete_comments);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // Cuối cùng xóa tour
    $sql = "DELETE FROM tours WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>