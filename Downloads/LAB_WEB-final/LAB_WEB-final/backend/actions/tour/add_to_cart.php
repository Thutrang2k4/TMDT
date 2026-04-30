<?php
// Tệp: LAB_WEB/backend/actions/tour/add_to_cart.php - Thêm Tour vào Giỏ hàng (Database)

session_start();
require_once '../../db.php';

$tour_id = filter_input(INPUT_POST, 'tour_id', FILTER_VALIDATE_INT);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) ?? 1;
$redirect_url = filter_input(INPUT_POST, 'redirect_url', FILTER_SANITIZE_URL) ?? '../../../frontend/products.php';

// Kiểm tra input
if ($tour_id === false || $quantity < 1 || $quantity > 99) {
    header("Location: $redirect_url?error=" . urlencode("Dữ liệu không hợp lệ."));
    exit;
}

if (!isset($conn)) {
    header("Location: $redirect_url?error=" . urlencode("Lỗi kết nối CSDL."));
    exit;
}

// Lấy thông tin tour
$sql = "SELECT id, price FROM tours WHERE id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tour_id);
$stmt->execute();
$result = $stmt->get_result();
$tour = $result->fetch_assoc();
$stmt->close();

if (!$tour) {
    $conn->close();
    header("Location: $redirect_url?error=" . urlencode("Tour không tồn tại hoặc đã ngừng hoạt động."));
    exit;
}

// Lấy user_id từ session (nếu có)
$user_id = $_SESSION['user_id'] ?? null;

// Tính tổng tiền
$total_price = $tour['price'] * $quantity;

// Lưu đơn hàng vào database
$sql_insert = "INSERT INTO orders (user_id, tour_id, quantity, total_price, status, order_date) 
               VALUES (?, ?, ?, ?, 'pending', NOW())";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("iiii", $user_id, $tour_id, $quantity, $total_price);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    echo json_encode([
        "success" => true,
        "message" => "Đã thêm vào giỏ"
    ]);
    exit;
} else {
    $stmt->close();
    $conn->close();
    header("Location: $redirect_url?error=" . urlencode("Lỗi khi thêm đơn hàng."));
    exit;
}
