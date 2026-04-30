<?php
// Tệp: backend/actions/tour/admin_order.php - Điều phối các hành động quản lý Đơn hàng

require_once '../../db.php';
require_once '../../models/order_model.php';
require_once '../../auth/check_role.php';

// Bảo vệ bằng hàm kiểm tra quyền
protect_page('admin');

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$redirect_to = '../../frontend/admin/orders.php';
$message = '';
$error = '';

if (!isset($conn)) {
    $error = "Lỗi kết nối CSDL.";
    header("Location: $redirect_to?error=" . urlencode($error));
    exit;
}

if ($action === 'update_status') {
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $new_status = filter_input(INPUT_POST, 'new_status', FILTER_SANITIZE_STRING);

    // Kiểm tra trạng thái hợp lệ
    if (!in_array($new_status, ['pending', 'confirmed', 'cancelled'])) {
        $error = "Trạng thái không hợp lệ.";
        header("Location: $redirect_to?error=" . urlencode($error));
        exit;
    }

    if ($order_id && $new_status) {
        $result = update_order_status($conn, $order_id, $new_status);

        if ($result) {
            $status_labels = [
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'cancelled' => 'Đã hủy'
            ];
            $message = "Cập nhật trạng thái đơn hàng thành '{$status_labels[$new_status]}' thành công.";
        } else {
            $error = "Lỗi khi cập nhật trạng thái đơn hàng.";
        }
    } else {
        $error = "Dữ liệu không hợp lệ.";
    }
} else {
    $error = "Hành động không hợp lệ.";
}

$conn->close();

if ($message) {
    header("Location: $redirect_to?message=" . urlencode($message));
} else {
    header("Location: $redirect_to?error=" . urlencode($error));
}
exit;
