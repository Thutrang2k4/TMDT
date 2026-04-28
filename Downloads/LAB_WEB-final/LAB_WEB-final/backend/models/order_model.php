<?php
// Tệp: backend/models/order_model.php

/**
 * Lấy tất cả Đơn hàng (có JOIN với tours và users) cho trang Admin.
 */
function get_all_orders_admin($conn, $status_filter = null) {
    $sql = "SELECT 
                o.id, o.order_date, o.quantity, o.total_price, o.status,
                t.title AS tour_title,
                COALESCE(u.full_name, 'Khách vãng lai') AS user_name, u.email AS user_email
            FROM orders o
            JOIN tours t ON o.tour_id = t.id
            LEFT JOIN users u ON o.user_id = u.id"; 
            
    $params = [];
    $types = '';

    if ($status_filter && in_array($status_filter, ['pending', 'confirmed', 'cancelled'])) {
        $sql .= " WHERE o.status = ?";
        $params[] = $status_filter;
        $types .= 's';
    }
    
    $stmt = $conn->prepare($sql . " ORDER BY o.order_date DESC");

    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $orders;
}

/**
 * Cập nhật trạng thái Đơn hàng (Admin).
 */
function update_order_status($conn, $order_id, $new_status) {
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $order_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

/**
 * Lấy chi tiết một đơn hàng
 */
function get_order_by_id($conn, $order_id) {
    $sql = "SELECT 
                o.id, o.user_id, o.tour_id, o.order_date, o.quantity, o.total_price, o.status,
                t.title AS tour_title, t.price AS tour_price,
                u.full_name, u.email, u.id as user_id
            FROM orders o
            JOIN tours t ON o.tour_id = t.id
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
    return $order;
}

/**
 * Xóa đơn hàng
 */
function delete_order($conn, $order_id) {
    $sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

/**
 * Thống kê đơn hàng theo trạng thái
 */
function get_order_statistics($conn) {
    $sql = "SELECT 
                status,
                COUNT(*) as count,
                SUM(total_price) as total_amount
            FROM orders
            GROUP BY status";
    
    $result = $conn->query($sql);
    $stats = [];
    
    while ($row = $result->fetch_assoc()) {
        $stats[$row['status']] = $row;
    }
    
    return $stats;
}
?>