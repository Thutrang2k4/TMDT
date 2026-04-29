<?php
session_start();
require_once '../backend/db.php';

$user_id = $_SESSION['user_id'] ?? null;

$orders = [];
$order_detail = null;

if ($user_id && isset($conn)) {

    // ==========================
    // LIST ORDER FROM my_orders
    // ==========================
    $sql = "SELECT mo.*, t.title, t.slug
            FROM my_orders mo
            JOIN tours t ON mo.tour_id = t.id
            WHERE mo.user_id = ?
            ORDER BY mo.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $orders[] = $row;
    }

    $stmt->close();

    // ==========================
    // DETAIL ORDER
    // ==========================
    if (isset($_GET['order_id'])) {

        $order_id = (int)$_GET['order_id'];

        $sql2 = "SELECT mo.*, t.title
                 FROM my_orders mo
                 JOIN tours t ON mo.tour_id = t.id
                 WHERE mo.id = ? AND mo.user_id = ?";

        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("ii", $order_id, $user_id);
        $stmt2->execute();

        $order_detail = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Đơn hàng của tôi - GoTour</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .orders-container {
            padding: 40px 0;
        }
        .page-header {
            margin-bottom: 40px;
            text-align: center;
        }
        .page-header h1 {
            font-size: 36px;
            margin-bottom: 15px;
            color: #000;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .orders-table thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .orders-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #000;
            font-size: 14px;
        }
        .orders-table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            color: #000;
        }
        .orders-table tbody tr:hover {
            background-color: #f9f9f9;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            font-weight: 600;
        }
        .btn-view {
            background-color: #007bff;
            color: white;
        }
        .btn-view:hover {
            background-color: #0056b3;
        }
        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .no-orders {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 8px;
        }
        .no-orders-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ddd;
        }
        .no-orders p {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }
        .no-orders a {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }
        .no-orders a:hover {
            background-color: #5568d3;
        }
        .order-detail-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .order-detail-modal.show {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #dee2e6;
        }
        .modal-close {
            float: right;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }
        .modal-close:hover {
            color: #000;
        }
        .order-info {
            margin-bottom: 20px;
        }
        .order-info-label {
            font-weight: 600;
            color: #666;
            margin-bottom: 5px;
        }
        .order-info-value {
            color: #000;
            font-size: 16px;
        }
        @media (max-width: 768px) {
            .orders-table {
                font-size: 14px;
            }
            .orders-table th, .orders-table td {
                padding: 10px;
            }
            .page-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div id="header"></div>
    
    <main class="container orders-container">
        <div class="page-header">
            <h1>Đơn hàng của tôi</h1>
        </div>
        
        <?php
        if (!$user_id) {
            echo '<div class="alert alert-warning">⚠️ Vui lòng <a href="login.php" style="color: inherit; font-weight: bold;">đăng nhập</a> để xem đơn hàng của bạn.</div>';
        } elseif (!isset($conn) && $user_id) {
            echo '<div class="alert alert-warning">Lỗi: Không thể kết nối đến cơ sở dữ liệu.</div>';
        } else {
            if (!empty($message)) {
                echo '<div class="alert alert-success">✓ ' . htmlspecialchars($message) . '</div>';
            }
            if (!empty($error)) {
                echo '<div class="alert alert-warning">⚠️ ' . htmlspecialchars($error) . '</div>';
            }
            
            if (empty($orders)) {
                echo '<div class="no-orders">
                    <div class="no-orders-icon">📦</div>
                    <p>Bạn chưa có đơn hàng nào</p>
                    <a href="products.php">Khám phá các tour</a>
                </div>';
            } else {
                echo '<table class="orders-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Tour</th>
                            <th>Số lượng</th>
                            <th>Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
                
                $status_labels = [
                    'pending' => 'Chờ xác nhận',
                    'confirmed' => 'Đã xác nhận',
                    'cancelled' => 'Đã hủy'
                ];
                
                foreach ($orders as $order) {
                    $status_class = htmlspecialchars($order['status']);
                    $status_text = $status_labels[$order['status']] ?? 'Không xác định';
                    
                    echo '<tr>
                        <td><strong>#' . htmlspecialchars($order['id']) . '</strong></td>
                        <td><a href="product-detail.php?slug=' . htmlspecialchars($order['slug']) . '" style="color: #007bff; text-decoration: none;">' . htmlspecialchars($order['title']) . '</a></td>
                        <td>' . htmlspecialchars($order['total_quantity']) . '</td>
                        <td><strong>' . number_format($order['total_price'], 0, ',', '.') . ' VNĐ</strong></td>
                        <td>' . date('d/m/Y H:i', strtotime($order['created_at'])) . '</td>
                        <td><span class="status-badge status-' . $status_class . '">' . htmlspecialchars($status_text) . '</span></td>
                        <td>
                            <a href="my-orders.php?order_id=' . htmlspecialchars($order['id']) . '" class="btn btn-view" style="text-decoration: none;">Xem chi tiết</a>
                        </td>
                    </tr>';
                }
                
                echo '</tbody></table>';
            }
        }
        ?>
    </main>
    
    <!-- Modal chi tiết đơn hàng -->
    <?php
    if ($order_detail) {
        $status_labels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy'
        ];
        
        echo '<div class="order-detail-modal show">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-close" onclick="closeModal()">&times;</span>
                    Chi tiết đơn hàng
                </div>
                <div class="order-info">
                    <div class="order-info-label">Mã đơn hàng:</div>
                    <div class="order-info-value">#' . htmlspecialchars($order_detail['id']) . '</div>
                </div>
                <div class="order-info">
                    <div class="order-info-label">Tour:</div>
                    <div class="order-info-value">' . htmlspecialchars($order_detail['title']) . '</div>
                </div>
                <div class="order-info">
                    <div class="order-info-label">Số lượng:</div>
                    <div class="order-info-value">' . htmlspecialchars($order_detail['total_quantity']) . '</div>
                </div>
                <div class="order-info">
                    <div class="order-info-label">Ghi chú:</div>
                    <div class="order-info-value">' . nl2br(htmlspecialchars($order_detail['note'] ?? 'Không có ghi chú')) . '</div>
                </div>
                <div class="order-info">
                    <div class="order-info-label">Tổng tiền:</div>
                    <div class="order-info-value">' . number_format($order_detail['total_price'], 0, ',', '.') . ' VNĐ</div>
                </div>
                <div class="order-info">
                    <div class="order-info-label">Trạng thái:</div>
                    <div class="order-info-value"><span class="status-badge status-' . htmlspecialchars($order_detail['status']) . '">' . $status_labels[$order_detail['status']] . '</span></div>
                </div>
                <div class="order-info">
                    <div class="order-info-label">Ngày khởi hành:</div>
                    <div class="order-info-value"> ' . date('d/m/Y', strtotime($order_detail['depart_date'])) . ' </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="my-orders.php" class="btn btn-back" style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none;">Đóng</a>
                </div>
            </div>
        </div>';
    }
    ?>
    
    <div id="footer"></div>
    
    <script>
        fetch("partials/header.php").then(r=>r.text()).then(t=>document.getElementById("header").innerHTML=t);
        fetch("partials/footer.php").then(r=>r.text()).then(t=>document.getElementById("footer").innerHTML=t);
        
        function closeModal() {
            window.location.href = "my-orders.php";
        }
    </script>
    <script src="assets/js/main.js"></script>
    <script src="logo.js"></script>
</body>
</html>