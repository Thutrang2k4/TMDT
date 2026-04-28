<?php
// Tệp: backend/actions/tour/delete_order.php - Xóa đơn hàng từ giỏ hàng khách hàng

session_start();
require_once '../../db.php';
require_once '../../models/order_model.php';

$user_id = $_SESSION['user_id'] ?? null;
$order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);

// Kiểm tra dữ liệu
if (!$user_id || !$order_id) {
    header("Location: ../../frontend/cart.php?error=" . urlencode("Dữ liệu không hợp lệ."));
    exit;
}

if (!isset($conn)) {
    header("Location: ../../frontend/cart.php?error=" . urlencode("Lỗi kết nối CSDL."));
    exit;
}

// Kiểm tra xem đơn hàng có thuộc về người dùng này không
$sql_check = "SELECT id FROM orders WHERE id = ? AND user_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $order_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    $stmt_check->close();
    $conn->close();
    header("Location: ../../frontend/cart.php?error=" . urlencode("Đơn hàng không tồn tại."));
    exit;
}

$stmt_check->close();

// Xóa đơn hàng
$result = delete_order($conn, $order_id);

$conn->close();

if ($result) {
    header("Location: ../../../frontend/cart.php?message=" . urlencode("Đơn hàng đã được xóa thành công."));
    exit;
} else {
    header("Location: ../../../frontend/cart.php?error=" . urlencode("Lỗi khi xóa đơn hàng."));
    exit;
}
?>