<?php
// Tệp: frontend/cart.php - Giỏ hàng từ Database

session_start();

require_once '../backend/db.php';
require_once '../backend/auth/check_role.php';
require_once '../backend/models/tour_model.php';
requireMember();
require_once '../backend/models/order_model.php';

$tours_in_cart = [];
$total_amount = 0;
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

if (isset($conn)) {
    // Lấy user_id từ session (nếu đã đăng nhập)
    $user_id = $_SESSION['user_id'] ?? null;
    
    // Lấy danh sách đơn hàng (pending/confirmed)
    $sql = "SELECT o.id, o.quantity, o.total_price, o.status, t.id as tour_id, t.title, t.slug, t.price
            FROM orders o
            JOIN tours t ON o.tour_id = t.id
            WHERE o.status IN ('pending', 'confirmed')";
    
    // Nếu người dùng đã đăng nhập, chỉ lấy đơn của họ
    if ($user_id) {
        $sql .= " AND o.user_id = ?";
        $stmt = $conn->prepare($sql . " ORDER BY o.order_date DESC");
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt = $conn->prepare($sql . " ORDER BY o.order_date DESC");
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Lấy dữ liệu vào mảng
    while ($row = $result->fetch_assoc()) {
        $total_amount += $row['total_price'];
        $tours_in_cart[] = $row;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Giỏ hàng - GoTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .cart-container {
            padding: 40px 0;
        }
        .cart-header {
            margin-bottom: 40px;
        }
        .cart-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #000;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .cart-table thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .cart-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #000;
            font-size: 14px;
        }
        .cart-table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            color: #000;
        }
        .cart-table tbody tr:hover {
            background-color: #f9f9f9;
        }
        .cart-table a {
            color: #007bff;
            text-decoration: none;
        }
        .cart-table a:hover {
            text-decoration: underline;
        }
        .item-price {
            font-weight: 600;
            color: #000;
        }
        .item-subtotal {
            font-weight: 700;
            color: #e67e22;
            font-size: 16px;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-control input {
            width: 60px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        .btn-remove {
            display: inline-block;
            padding: 8px 12px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-remove:hover {
            background-color: #c82333;
        }
        .cart-summary {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: right;
            margin-bottom: 30px;
        }
        .cart-summary .total-label {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        .cart-summary .total-amount {
            font-size: 32px;
            font-weight: bold;
            color: #e67e22;
            margin-bottom: 25px;
        }
        .cart-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            display: inline-block;
        }
        .btn-checkout {
            background-color: #28a745;
            color: white;
        }
        .btn-checkout:hover {
            background-color: #218838;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-cart-icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ddd;
        }
        .empty-cart p {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .cart-table {
                font-size: 14px;
            }
            .cart-table th, .cart-table td {
                padding: 10px;
            }
            .cart-summary {
                text-align: center;
            }
            .cart-actions {
                justify-content: center;
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div id="header"></div>
    
    <main class="container cart-container">
        <div class="cart-header">
            <h1> Giỏ hàng của bạn</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                ✓ <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                ✗ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($tours_in_cart)): ?>
            <div class="empty-cart">
                <div class="empty-cart-icon">🎒</div>
                <p>Giỏ hàng của bạn hiện đang trống</p>
                <a href="products.php" class="btn btn-back">← Quay lại trang Tour</a>
            </div>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Tour</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tours_in_cart as $item): ?>
                    <tr>
                        <td>
                            <a href="product-detail.php?slug=<?php echo htmlspecialchars($item['slug']); ?>">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </a>
                        </td>
                        <td class="item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</td>
                        <td>
                            <div class="quantity-control">
                                <input type="number" value="<?php echo $item['quantity']; ?>" min="1" readonly>
                            </div>
                        </td>
                        <td class="item-subtotal"><?php echo number_format($item['total_price'], 0, ',', '.'); ?> VNĐ</td>
                        <td>
                            <form method="POST" action="../backend/actions/tour/delete_order.php" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn-remove" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <div class="total-label">Tổng tiền giỏ hàng:</div>
                <div class="total-amount"><?php echo number_format($total_amount, 0, ',', '.'); ?> VNĐ</div>
                <div class="cart-actions">
                    <a href="products.php" class="btn btn-back">← Tiếp tục mua</a>
                    <a href="checkout.php" class="btn btn-checkout">Thanh toán →</a>
                </div>
            </div>

        <?php endif; ?>
    </main>
    
    <div id="footer"></div>
    
    <script>
        fetch("partials/header.php").then(r=>r.text()).then(t=>document.getElementById("header").innerHTML=t);
        fetch("partials/footer.php").then(r=>r.text()).then(t=>document.getElementById("footer").innerHTML=t);
    </script>
    <script src="assets/js/main.js"></script>
    <script src="logo.js"></script>
</body>
</html>