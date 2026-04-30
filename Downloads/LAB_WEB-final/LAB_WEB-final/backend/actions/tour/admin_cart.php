<?php
// Tệp: backend/actions/tour/admin_cart.php - Quản lý giỏ hàng và đơn hàng cho admin

require_once '../../db.php';
require_once '../../models/order_model.php';
require_once '../../auth/check_role.php';

protect_page('admin');

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
$redirect_to = '../../../frontend/admin/cart.php';
$redirect_to_customer = '../../../frontend/cart.php';
$message = '';
$error = '';

// Debug
error_log("Action: " . $action . ", Order ID: " . $order_id);

if (!isset($conn)) {
    $error = "Lỗi kết nối CSDL.";
    header("Location: $redirect_to?error=" . urlencode($error));
    exit;
}

if ($action === 'confirm') {
    // Xác nhận đơn hàng
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);

    if (!$order_id) {
        $error = "Dữ liệu không hợp lệ.";
    } else {
        $result = update_order_status($conn, $order_id, 'confirmed');
        if ($result) {
            $message = "Đơn hàng đã được xác nhận thành công.";
        } else {
            $error = "Lỗi khi xác nhận đơn hàng.";
        }
    }
} elseif ($action === 'cancel') {
    // Hủy đơn hàng
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);

    if (!$order_id) {
        $error = "Dữ liệu không hợp lệ.";
    } else {
        $result = update_order_status($conn, $order_id, 'cancelled');
        if ($result) {
            $message = "Đơn hàng đã được hủy.";
        } else {
            $error = "Lỗi khi hủy đơn hàng.";
        }
    }
} elseif ($action === 'delete') {
    // Xóa đơn hàng từ database
    if (!$order_id) {
        $error = "Dữ liệu không hợp lệ.";
        error_log("Lỗi: Order ID không hợp lệ");
    } else {
        $result = delete_order($conn, $order_id);
        error_log("Kết quả delete_order: " . ($result ? "true" : "false"));
        if ($result) {
            $message = "Đơn hàng đã được xóa thành công.";
            // Chuyển hướng về trang giỏ hàng khách hàng
            $conn->close();
            header("Location: $redirect_to_customer?message=" . urlencode($message));
            exit;
        } else {
            $error = "Lỗi khi xóa đơn hàng.";
        }
    }
} else {
    $error = "Hành động không hợp lệ: " . $action;
    error_log("Hành động không hợp lệ: " . $action);
}

$conn->close();

// Chuyển hướng về trang giỏ hàng với thông báo
if (!empty($message)) {
    header("Location: $redirect_to?message=" . urlencode($message));
    exit;
}

if (!empty($error)) {
    header("Location: $redirect_to?error=" . urlencode($error));
    exit;
}

header("Location: $redirect_to");
exit;
