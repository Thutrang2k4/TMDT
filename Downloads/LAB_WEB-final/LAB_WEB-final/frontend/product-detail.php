<?php
// Tệp: LAB_WEB/frontend/product-detail.php - Chi tiết Tour và Form Thêm vào Giỏ hàng

session_start();
require_once '../backend/db.php';
require_once '../backend/models/tour_model.php';
require_once '../backend/models/comment_model.php';

$slug = filter_input(INPUT_GET, 'slug', FILTER_SANITIZE_STRING);
$tour = null;
$comments = [];

$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

if ($slug && isset($conn)) {
    $tour = get_tour_by_slug($conn, $slug);
    
    if ($tour) {
        // Lấy comments cho tour này
        $comments = get_comments_by_tour($conn, $tour['id']) ?? [];
    }
    
    $conn->close();
}

if (!$tour) {
    header("Location: products.php?error=" . urlencode("Không tìm thấy Tour này."));
    exit;
}
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($tour['title']); ?> - GoTour</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .product-detail-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .breadcrumb {
            margin-bottom: 30px;
            font-size: 14px;
        }
        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .product-image {
            width: 100%;
            height: 400px;
            background-color: #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .product-info h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #000;
        }
        .product-meta {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .product-meta-item {
            display: flex;
            flex-direction: column;
        }
        .product-meta-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .product-meta-value {
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        .product-price {
            font-size: 32px;
            font-weight: bold;
            color: #e67e22;
            margin-bottom: 30px;
        }
        .product-description {
            font-size: 15px;
            line-height: 1.6;
            color: #000;
            margin-bottom: 30px;
        }
        .product-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        .quantity-input {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: fit-content;
        }
        .quantity-input button {
            background: none;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            color: #666;
            font-size: 16px;
        }
        .quantity-input input {
            width: 50px;
            border: none;
            text-align: center;
            padding: 8px 0;
        }
        .btn-add-cart {
            flex: 1;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-add-cart:hover {
            background-color: #218838;
        }
        .btn-back {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .comments-section {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid #f0f0f0;
        }
        .comments-section h2 {
            font-size: 24px;
            margin-bottom: 30px;
        }
        .comment {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .comment-author {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .comment-date {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }
        .comment-rating {
            color: #ffc107;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .comment-content {
            color: #000;
            line-height: 1.6;
        }
        .no-comments {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        @media (max-width: 768px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .product-actions {
                flex-direction: column;
            }
            .product-info h1 {
                font-size: 22px;
            }
        }
.day-title {
    font-weight: bold;
    font-size: 18px;
    color: #2c3e50;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* tam giác */
.arrow {
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 8px solid #333;
    transition: transform 0.3s;
}

/* xoay lên khi mở */
.day-title{
    color: #ffc107;
}
.day-title.active .arrow {
    transform: rotate(180deg);

}

.day-content {
    display: none;
    color: #000;
    margin-top: 10px;
    line-height: 1.7;
}

/* giờ */
.time {
    font-weight: bold;
    color: #e74c3c;
}

/* địa điểm nổi bật */
.place {
    font-weight: bold;
    color: #27ae60;
    font-size: 16px;
}
.news-detail-page {
            min-height: 100%;
            margin-bottom: 10% !important; /* Tránh footer che nội dung */
        }
        .news-container { max-width: 800px; margin: 0 auto; padding-bottom: 100px; }
        .comment-avatar { 
            width: 45px; height: 45px; 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            background-color: #e9ecef;
            color: #495057;
        }
        .comment-box { background-color: #f8f9fa; border-radius: 12px; padding: 15px; width: 100%; }
        .news-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 15px 0; }
        .product-meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-meta-table th,
        .product-meta-table td {
            padding: 10px;
        
            text-align: left;
        }

        .product-meta-table th {
            
            width: 30%;
        }
        .tour-tabs {
    width: 100%;
    margin-top: 20px;
}

/* thanh tab */
.tab-header {
    display: flex;
    justify-content: center;
    gap: 10px;
    border-bottom: 5px solid #2e7d32; /* xanh giống hình */
    margin-bottom: 15px;
}

/* nút tab */
.tab-item {
    background: transparent;
    border: 1px solid transparent;
    padding: 8px 18px;
    font-size: 16px;
    cursor: pointer;
    position: relative;
}

/* hover */
.tab-item:hover {
    border: 1px solid #000;
}

/* tab đang chọn */
.tab-item.active {
    border: 1px solid #000;
    border-bottom: 5px solid #fff; /* tạo hiệu ứng nổi lên */
    font-weight: bold;
    background: #fff;
}

/* content tab */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* note box */
.note-box {
    padding: 10px;
    line-height: 1.6;
}

/* highlight thời gian */
.time {
    color: #e67e22;
    font-weight: bold;
}
.note-title {
    font-weight: bold;
    color: #d35400;
    margin-top: 10px;
    margin-bottom: 5px;
    font-size: 16px;
}

.note-list {
    padding-left: 20px;
    margin: 5px 0 15px;
}

.note-list li {
    margin-bottom: 5px;
    line-height: 1.5;
}
    </style>
</head>
<body>
    <div id="header"></div>
    
    <main class="product-detail-container">
        <div class="breadcrumb">
            <a href="products.php">Tour</a> / <?php echo htmlspecialchars($tour['title']); ?>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="product-detail-grid">
            <!-- Hình ảnh -->
            <div>
                <div class="product-image">
                    <?php if (!empty($tour['image'])): ?>
                        <img src="<?php echo htmlspecialchars($tour['image']); ?>" 
                             alt="<?php echo htmlspecialchars($tour['title']); ?>"
                             onerror="this.src='assets/images/placeholder.jpg'">
                    <?php else: ?>
                        <span style="color: #999;">Chưa có hình ảnh</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Thông tin sản phẩm -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($tour['title']); ?></h1>
                
                <div class="product-meta">
                    <table class="product-meta-table">
                        <tr>
                            <th>Thời gian</th>
                            <td><?php echo htmlspecialchars($tour['duration_days']); ?> ngày</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td><?php echo $tour['status'] === 'active' ? 'Còn hàng' : 'Hết hàng'; ?></td>
                        </tr>
                        <tr>
                            <th>Khởi hành</th>
                            <td><?php echo htmlspecialchars($tour['day_start']); ?></td>
                        </tr>
                        <tr>
                            <th>Phương tiện</th>
                            <td><?php echo htmlspecialchars($tour['vehicle']); ?></td>
                        </tr>
                        <tr>
                            <th>Nơi khởi hành</th>
                            <td><?php echo htmlspecialchars($tour['place_start']); ?></td>
                        </tr>
                        <tr>
                            <th>Khách sạn</th>
                            <td><?php echo htmlspecialchars($tour['hotel']); ?></td>
                        </tr>
                        <tr>
                            <th>Nhà cung cấp</th>
                            <td><?php echo htmlspecialchars($tour['host']); ?></td>
                        </tr>

                    </table>
                </div>
                
                <div class="product-price">
                    <?php echo number_format($tour['price'], 0, ',', '.'); ?> VNĐ
                </div>
                
                <div class="product-description">
                    <?php echo htmlspecialchars($tour['short_description']); ?>
                </div>
                
                <form class="add-to-cart-form" method="POST" action="../backend/actions/tour/add_to_cart.php" style="flex: 1;">
                    <div class="product-actions">
                        <div class="quantity-input">
                            <button type="button" onclick="decreaseQuantity()">−</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="99" readonly>
                            <button type="button" onclick="increaseQuantity()">+</button>
                        </div>
                        <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                        
                        <button type="submit" class="btn-add-cart" >Thêm vào Giỏ hàng</button>
                    </div>
                </form>
                
                <a href="products.php" class="btn-back">← Quay lại</a>
            </div>
        </div>
        <!-- Phần mô tả dài -->
 <!-- TAB BUTTONS -->
    <div class="tab-header">
        <button class="tab-item active" onclick="switchTab('detail', this)">
            CHI TIẾT TOUR
        </button>

        <button class="tab-item" onclick="switchTab('note', this)">
            LƯU Ý
        </button>
    </div>

    <!-- TAB CONTENT: CHI TIẾT TOUR -->
    <div id="detail" class="tab-content active">
        <div class="long-description">
            <?php
            $contentText = htmlspecialchars($tour['long_description']);

            $days = preg_split('/((?:NGÀY|ĐÊM)\s\d+\s\|[^\n]+)/u', $contentText, -1, PREG_SPLIT_DELIM_CAPTURE);
            $output = '';

            for ($i = 1; $i < count($days); $i += 2) {
                $title = $days[$i];
                $content = $days[$i + 1] ?? '';

                $content = preg_replace('/(\d{2}h\d{2}:)/', '<span class="time">$1</span>', $content);
                $content = preg_replace('/(Sáng|Trưa|Chiều|Tối)/', '<span class="time">$1</span>', $content);

                $content = nl2br($content);

                $id = 'day_' . $i;

                $output .= "
                <div class='day-block'>
                    <div class='day-title' onclick=\"toggleDay('$id', this)\">
                        <span>$title</span>
                        <span class='arrow'></span>
                    </div>
                    <div class='day-content' id='$id'>
                        $content
                    </div>
                </div>
                ";
            }

            echo $output;
            ?>
        </div>
    </div>

    <!-- TAB CONTENT: LƯU Ý -->
    <div id="note" class="tab-content">
        <div class="note-box">
            <?php
$note = htmlspecialchars($tour['note'] ?? '');

// 1. Tách dòng
$lines = explode("\n", $note);

$output = '';

foreach ($lines as $line) {
    $line = trim($line);
    if ($line == '') continue;

    // 2. Nếu là dòng IN HOA (tiêu đề)
    if (mb_strtoupper($line, 'UTF-8') === $line) {
        $line = "<div class='note-title'>$line</div>";
    }
    // 3. Nếu là nội dung bình thường → bullet
    else {
        $line = "<li>$line</li>";
    }

    $output .= $line;
}

// bọc ul cho list
$output = preg_replace('/(<li>.*<\/li>)/s', '<ul class="note-list">$1</ul>', $output, 1);

echo "<div class='note-box'>$output</div>";
?>
        </div>
    </div>

</div>
        <!-- Phần bình luận -->
        <div class="comments-section">
            <h2>Đánh giá & Bình luận (<?php echo count($comments); ?>)</h2>
            
            <?php if (empty($comments)): ?>
                <div class="no-comments">
                    <p>Chưa có bình luận nào cho tour này.</p>
                </div>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comment-author">
                            <?php echo htmlspecialchars($comment['user_name'] ?? 'Ẩn danh'); ?>
                        </div>
                        <div class="comment-date">
                            <?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?>
                        </div>
                        <?php if (!empty($comment['rating'])): ?>
                            <div class="comment-rating">
                                <?php echo str_repeat('★', (int)$comment['rating']) . str_repeat('☆', 5 - (int)$comment['rating']); ?> 
                                (<?php echo htmlspecialchars($comment['rating']); ?>/5)
                            </div>
                        <?php endif; ?>
                        <div class="comment-content">
                            <?php echo htmlspecialchars($comment['content']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- Form thêm bình luận -->
            <div class="add-comment mt-4">
                <div class="card-body p-4">
                <form id="commentForm" method="POST" action="../backend/actions/tour/add_comment.php">   
                    <h6 class="card-title fw-bold mb-3">Để lại ý kiến của bạn</h6>
                    <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?? 0; ?>"> 
                    <input type="hidden" name="slug" value="<?php echo $slug; ?>">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="3" placeholder="Chia sẻ suy nghĩ của bạn..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="me-2 fw-semibold">Đánh giá:</span>
                                <select name="rating" class="form-select form-select-sm w-auto border-warning text-warning fw-bold">
                                    <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                                    <option value="4">⭐⭐⭐⭐ (Tốt)</option>
                                    <option value="3">⭐⭐⭐ (Bình thường)</option>
                                    <option value="2">⭐⭐ (Tệ)</option>
                                    <option value="1">⭐ (Rất tệ)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                Gửi
                            </button>
                        </div>
                    </form>
                </form>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer"></div>
    
    <script>
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            const current = parseInt(input.value) || 1;
            if (current < 99) {
                input.value = current + 1;
            }
        }
        
        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            const current = parseInt(input.value) || 1;
            if (current > 1) {
                input.value = current - 1;
            }
        }
        document.querySelectorAll(".add-to-cart-form").forEach(form => {
    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("../backend/actions/tour/add_to_cart.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Đã thêm vào giỏ hàng!");
            } else {
                alert("Lỗi khi thêm giỏ!");
            }
        })
        .catch(err => console.error(err));
    });
});

        fetch("partials/header.php").then(r=>r.text()).then(t=>document.getElementById("header").innerHTML=t);
        fetch("partials/footer.php").then(r=>r.text()).then(t=>document.getElementById("footer").innerHTML=t);
    </script>
    <script src="assets/js/main.js"></script>
    <script src="logo.js"></script>
    <script>
        function switchTab(id, el) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.tab-item').forEach(b => b.classList.remove('active'));

    document.getElementById(id).classList.add('active');
    el.classList.add('active');
}
function toggleDay(id, el) {
    const content = document.getElementById(id);

    const isOpen = content.style.display === "block";

    // đóng tất cả nếu muốn (accordion)
    document.querySelectorAll('.day-content').forEach(d => d.style.display = 'none');
    document.querySelectorAll('.day-title').forEach(t => t.classList.remove('active'));

    if (!isOpen) {
        content.style.display = "block";
        el.classList.add("active");
    }
    // hàm tải bình luận
    
    // --- 3. XỬ LÝ GỬI FORM ---
    document.getElementById("commentForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("../backend/actions/tour/add_comment.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Đã lưu comment");
            location.reload(); // reload trang
        } else {
            alert(data.message);
        }
    })
    .catch(err => console.log("ERR:", err));
});
                        }

</script>
</body>
</html>