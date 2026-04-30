<?php
// Tệp: frontend/checkout.php - Trang thanh toán (đã refactor)

session_start();
require_once '../backend/db.php';
require_once '../backend/auth/check_role.php';
requireMember();

$user_id = $_SESSION['user_id'] ?? null;
$cart_items = [];

// ============================
// LẤY DỮ LIỆU GIỎ HÀNG TỪ DB
// ============================
if (isset($conn) && $user_id) {

    $sql = "SELECT o.*, t.title, t.slug, t.price, t.hotel, t.day_start
            FROM orders o
            JOIN tours t ON o.tour_id = t.id
            WHERE o.user_id = ? AND o.status IN ('pending','confirmed')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc())
        $cart_items[] = $r;

    $stmt->close();
}
// ============================
// XỬ LÝ GIÁ VÉ THEO LOẠI KHÁCH
// ============================
function calcPriceByType($basePrice, $type)
{
    switch ($type) {
        case 'child':
            return max(0, $basePrice - 300000);
        case 'infant':
            return max(0, $basePrice - 600000);
        default:
            return $basePrice;
    }
}
// ============================
// API THANH TOÁN GIẢ LẬP
// ============================
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['fake_payment'])) {

    header('Content-Type: application/json');

    $user_id = $_SESSION['user_id'] ?? 0;

    if (!$user_id) {
        echo json_encode(['success' => false, 'msg' => 'No user']);
        exit;
    }

    if (!isset($_POST['cart'])) {
        echo json_encode(['success' => false, 'msg' => 'No cart']);
        exit;
    }

    $cart = json_decode($_POST['cart'], true);

    if (!$cart) {
        echo json_encode(['success' => false, 'msg' => 'Cart lỗi']);
        exit;
    }

    // ======================
    // LẤY DATA
    // ======================
    $adult = (int) $cart['adult'];
    $child = (int) $cart['child'];
    $infant = (int) $cart['infant'];
    $date = $cart['date'];
    $base = (int) $cart['base'];

    $total_qty = $adult + $child + $infant;
    $total = $total_qty * $base;

    // ======================
    // LẤY ORDER
    // ======================
    $res = $conn->query("
        SELECT id, tour_id 
        FROM orders 
        WHERE user_id = $user_id 
        AND status = 'pending'
        LIMIT 1
    ");

    if (!$res || $res->num_rows == 0) {
        echo json_encode(['success' => false, 'msg' => 'Không có order']);
        exit;
    }

    $order = $res->fetch_assoc();

    $order_id = $order['id'];
    $tour_id = $order['tour_id'];

    // ======================
    // UPDATE STATUS
    // ======================
    $conn->query("
        UPDATE orders 
        SET status = 'canceled' 
        WHERE id = $order_id
    ");

    // ======================
    // NOTE
    // ======================
    $note = "Người lớn: $adult | Trẻ em: $child | Em bé: $infant";

    // ======================
    // INSERT my_orders
    // ======================
    $stmt = $conn->prepare("
        INSERT INTO my_orders 
        (user_id, order_id, tour_id, total_quantity, adult_qty, child_qty, infant_qty, depart_date, total_price, status, note)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', ?)
    ");

    if (!$stmt) {
        echo json_encode(['success' => false, 'msg' => $conn->error]);
        exit;
    }

    $stmt->bind_param(
        "iiiiiiisis",
        $user_id,
        $order_id,
        $tour_id,
        $total_qty,
        $adult,
        $child,
        $infant,
        $date,
        $total,
        $note
    );

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'msg' => $stmt->error]);
        exit;
    }

    echo json_encode(['success' => true]);
    exit;
}

// ============================
// XỬ LÝ KHÁCH SẠN THEO DB
// ============================
function getHotelOptions($hotelStr)
{
    $hotelStr = strtolower($hotelStr ?? '');

    // dạng 3-5 sao
    if (preg_match('/3\s*-\s*5/', $hotelStr)) {
        return ['3 sao', '4 sao', '5 sao'];
    }

    if (preg_match('/3\s*[-]?\s*4/', $hotelStr)) {
        return ['3 sao', '4 sao'];
    }

    if (str_contains($hotelStr, '3')) {
        return ['3 sao'];
    }

    if (str_contains($hotelStr, '4')) {
        return ['4 sao'];
    }

    if (str_contains($hotelStr, '5')) {
        return ['5 sao'];
    }

    return ['3 sao'];
}
// ============================
// PARSE NGÀY KHỞI HÀNH
// ============================
function parseAllowedDays($text)
{
    $map = [
        'thứ 2' => 1,
        'thứ 3' => 2,
        'thứ 4' => 3,
        'thứ 5' => 4,
        'thứ 6' => 5,
        'thứ 7' => 6,
        'chủ nhật' => 0,
    ];

    $text = strtolower($text ?? '');
    $days = [];

    foreach ($map as $k => $v) {
        if (str_contains($text, $k)) {
            $days[] = $v;
        }
    }

    return !empty($days) ? $days : [4]; // default Thứ 5
}
// lấy khách sạn từ tour đầu tiên (dùng cho form)
$tour = $cart_items[0] ?? null;
$hotelOptions = $tour ? getHotelOptions($tour['hotel']) : ['3 sao'];
$allowedDays = $tour ? parseAllowedDays($tour['day_start']) : [4];
?>

<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanh toán - GoTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .checkout-container {
            padding: 40px 0;
        }

        .info-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 25px;
        }

        .tour-info {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
        }

        .gender-radio {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .add-guest-btn {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
        }

        .total-box {
            font-size: 20px;
            font-weight: bold;
            color: #e67e22;
            margin-top: 15px
        }
    </style>
</head>

<body>

    <div id="header"></div>

    <main>
        <div class="container checkout-container">

            <!-- STEP -->
            <div class="text-center mb-4">
                <span class="badge bg-success p-2">1. Thông tin hành khách</span>
                <span class="mx-2">→</span>
                <span class="badge bg-light text-dark p-2">2. Thanh toán</span>
            </div>

            <div class="row">

                <!-- LEFT -->
                <div class="col-lg-7">
                    <div class="info-box">

                        <h4>Thông tin hành khách</h4>

                        <!-- GIỚI TÍNH -->
                        <div class="gender-radio">
                            <label><input type="radio" name="gender" checked> Anh</label>
                            <label><input type="radio" name="gender"> Chị</label>
                        </div>

                        <hr>

                        <!-- BẢNG HÀNH KHÁCH (THEO YÊU CẦU CỦA BẠN) -->
                        <h5>Danh sách hành khách</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="guestTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Số lượng khách</th>
                                        <th>Loại khách</th>
                                        <th>Quốc tịch</th>
                                        <th>Khách sạn</th>
                                        <th>Ngày khởi hành</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-base="<?php echo $tour['price'] ?? 0; ?>">
                                        <td><input type="number" class="form-control qty" value="1" min="1"></td>
                                        <td>
                                            <select class="form-select type">
                                                <option value="adult">Người lớn</option>
                                                <option value="child">Trẻ em (-300k)</option>
                                                <option value="infant">Em bé (-600k)</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select">
                                                <option>Việt Nam</option>
                                                <option>Nước ngoài</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select">
                                                <?php foreach ($hotelOptions as $h): ?>
                                                    <option><?php echo $h; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control depart-date"
                                                onchange="checkDate(this)">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Ghi chú">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="add-guest-btn mt-2" onclick="addGuestRow()">
                            + Thêm hành khách
                        </button>
                        <div class="text-end mt-4">
                            <div class="total-box">

                                Tổng tiền: <span id="totalPrice">0</span> VNĐ
                            </div>
                        </div>
                        <div class="text-end mt-4">

                            <a href="cart.php" class="btn btn-secondary">Quay lại</a>
                            <button class="btn btn-success" onclick="showQR()">
                                Tiếp tục
                            </button>
                        </div>

                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-lg-5">
                    <div class="info-box tour-info">
                        <h5>Thông tin tour</h5>

                        <?php foreach ($cart_items as $item): ?>
                            <div class="mb-3">
                                <strong>Tour:</strong> <?php echo htmlspecialchars($item['title']); ?><br>
                                <strong>Khách sạn:</strong> <?php echo htmlspecialchars($item['hotel'] ?? '3-5 sao'); ?><br>
                                <strong>Ngày khởi hành:</strong>
                                <?php echo htmlspecialchars($item['day_start'] ?? '-'); ?><br>
                                <strong>Giá:</strong> <?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ<br>
                            </div>
                            <hr>
                        <?php endforeach; ?>

                    </div>
                </div>

            </div>

        </div>
        <!-- QR MODAL -->
        <div class="modal fade" id="qrModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">

                    <h5>Thanh toán QR</h5>

                    <img src="assets/images/qr-payment.png" style="width:250px;margin:auto">

                    <p class="text-muted mt-2">
                        Quét mã để thanh toán
                    </p>

                    <button class="btn btn-success mt-2" onclick="fakePaid()">
                        Tôi đã chuyển tiền
                    </button>

                    <button class="btn btn-secondary mt-2" data-bs-dismiss="modal">
                        Đóng
                    </button>

                </div>
            </div>
        </div>
    </main>

    <div id="footer"></div>

    <script>

        const allowedDays = <?php echo json_encode($allowedDays); ?>;

        // ============================
        // THÊM KHÁCH
        // ============================
        function addGuestRow() {
            let row = document.querySelector('#guestTable tbody tr').outerHTML;
            document.querySelector('#guestTable tbody').insertAdjacentHTML('beforeend', row);
            calcTotal();
        }

        // ============================
        // CHẶN NGÀY KHÔNG HỢP LỆ
        // ============================
        function checkDate(el) {
            let d = new Date(el.value);
            let day = d.getDay();

            if (!allowedDays.includes(day)) {
                alert("Ngày này không được phép chọn theo tour!");
                el.value = "";
            }
        }

        // ============================
        // TÍNH TIỀN
        // ============================
        function calcTotal() {
            let total = 0;

            document.querySelectorAll('#guestTable tbody tr').forEach(tr => {
                let qty = parseInt(tr.querySelector('.qty').value) || 1;
                let type = tr.querySelector('.type').value;
                let base = parseInt(tr.dataset.base) || 0;

                let price = base;
                if (type === 'child') price -= 300000;
                if (type === 'infant') price -= 600000;

                if (price < 0) price = 0;

                total += price * qty;
            });

            document.getElementById('totalPrice').innerText = total.toLocaleString('vi-VN');
        }

        setInterval(calcTotal, 500);
        function showQR() {
            const modal = new bootstrap.Modal(document.getElementById('qrModal'));
            modal.show();
        }

        function collectCart() {

            let adult = 0, child = 0, infant = 0;
            let base = 0;
            let date = null;

            document.querySelectorAll('#guestTable tbody tr').forEach(tr => {

                let qty = parseInt(tr.querySelector('.qty').value) || 1;
                let type = tr.querySelector('.type').value;

                base = parseInt(tr.dataset.base);

                if (type === 'adult') adult += qty;
                if (type === 'child') child += qty;
                if (type === 'infant') infant += qty;

                if (!date) {
                    date = tr.querySelector('.depart-date').value;
                }
            });

            return { adult, child, infant, base, date };
        }
        // ============================
        // HIỆN QR
        // ============================
        function showQR() {
            const modal = new bootstrap.Modal(document.getElementById('qrModal'));
            modal.show();
        }

        // ============================
        // FAKE PAYMENT
        // ============================
        function fakePaid() {

            let btn = event.target;
            btn.innerHTML = "Đang xử lý...";
            btn.disabled = true;

            let formData = new FormData();
            formData.append("fake_payment", 1);
            formData.append("cart", JSON.stringify(collectCart()));

            fetch("checkout.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {
                        window.location.href = "my-orders.php";
                    } else {
                        alert("Lỗi: " + data.msg);
                        btn.innerHTML = "Tôi đã chuyển tiền";
                        btn.disabled = false;
                    }

                })
                .catch(() => {
                    alert("Server lỗi");
                    btn.innerHTML = "Tôi đã chuyển tiền";
                    btn.disabled = false;
                });
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/index.js"></script>
    <script src="logo.js"></script>
</body>

</html>