<?php
// Tệp: LAB_WEB/frontend/products.php - Danh sách Tour (Sản phẩm) với tìm kiếm

session_start();
require_once '../backend/db.php';
require_once '../backend/models/tour_model.php';

// Lấy từ khoá tìm kiếm từ URL
$keyword = filter_input(INPUT_GET, 'keyword', FILTER_SANITIZE_STRING);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
$limit = 10; // Số tour hiển thị trên 1 trang

$tours = [];
$total_pages = 0;
$error = $_GET['error'] ?? '';
$message = $_GET['message'] ?? '';

if (isset($conn)) {
    // Lấy danh sách tour với phân trang và tìm kiếm
    $tours_data = get_tours_pagination($conn, $keyword, $page, $limit);
    $tours = $tours_data['data'] ?? [];
    $total_pages = $tours_data['total_pages'] ?? 0;
    $conn->close();
}
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tour - GoTour</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .products-container {
            max-width: 1500px;
            margin: 2rem auto;
            padding: 0 1rem;
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
        .page-header p {
            font-size: 16px;
            color: #666;
        }
        .search-container {
            
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .search-form {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .search-form input {
            flex: 1;
            padding: 12px 18px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .search-form input::placeholder {
            color: #999;
        }
        .search-form button {
            padding: 12px 30px;
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            transition: background-color 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .search-form button:hover {
            background-color: #ff5252;
        }
        .search-form a {
            padding: 12px 20px;
            background-color: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .search-form a:hover {
            background-color: rgba(255,255,255,0.3);
        }
        .results-info {
            margin-bottom: 30px;
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
            color: #000;
            font-size: 14px;
        }
        .tours-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
            margin-bottom: 40px;
            margin-left: auto;
            margin-right: auto;
            max-width: 1400px;
        }
        .tour-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0;
            height: 100%;
        }
        .tour-card:hover {
            box-shadow: 0 12px 28px rgba(0,0,0,0.18);
            transform: translateY(-8px);
        }
        .tour-card-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 14px;
            position: relative;
            overflow: hidden;
        }
        .tour-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .tour-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 12px;
            background-color: #ff6b6b;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .tour-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
            text-align: center;
        }
        .tour-card-title {
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 16px;
            color: #000;
            line-height: 1.4;
            min-height: 54px;
        }
        .tour-card-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            flex: 1;
            line-height: 1.5;
        }
        .tour-card-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #666;
        }
        .tour-card-info span {
            display: block;
        }
        .tour-card-price {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b6b;
            margin-bottom: 20px;
            text-align: center;
        }
        .tour-card-price .currency {
            font-size: 14px;
            font-weight: normal;
        }
        .tour-card-actions {
            display: flex;
            gap: 12px;
            margin-top: auto;
        }
        .btn-detail {
            flex: 1;
            padding: 12px;
            background-color: orange;
            color: #333;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: 600;
        }
        .btn-detail:hover {
            background-color: #ffb300;
        }
        .btn-cart {
            flex: 1;
            padding: 12px;
            background-color: #28a745;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: 600;
        }
        .btn-cart:hover {
            background-color: #218838;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .pagination a, .pagination span {
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #667eea;
            font-weight: 500;
            transition: all 0.3s;
        }
        .pagination a:hover {
            background-color: #667eea;
            color: white;
            border-color: #667eea;
        }
        .pagination .active {
            background-color: #667eea;
            color: white;
            border-color: #667eea;
        }
        .alert {
            padding: 14px 18px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 14px;
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
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .no-results p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .no-results a {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }
        .no-results a:hover {
            background-color: #5568d3;
        }
        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            .search-form button, .search-form a {
                width: 100%;
            }
            .tours-grid {
                grid-template-columns: 1fr;
            }
            .page-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div id="header"></div>
    
    <main class="products-container">
        <div class="page-header">
            <h1>Khám Phá Tour Du Lịch</h1>
            <p>Chọn từ hàng nghìn tour tuyệt vời trên khắp thế giới</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <!-- Form tìm kiếm -->
        <div class="search-container">
            <form class="search-form" method="GET">
                <input type="text" name="keyword" placeholder="Tìm kiếm tour theo tên, thành phố, hoặc quốc gia..." 
                       value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
                <button type="submit">Tìm kiếm</button>
                <?php if ($keyword): ?>
                    <a href="products.php">✕ Xóa</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Thông tin kết quả tìm kiếm -->
        <?php if ($keyword || count($tours) > 0): ?>
            <div class="results-info">
                <?php if ($keyword): ?>
                    Kết quả tìm kiếm cho "<strong><?php echo htmlspecialchars($keyword); ?></strong>": 
                <?php endif; ?>
                <strong><?php echo count($tours); ?> tour</strong> được tìm thấy
                <?php if ($total_pages > 1): ?>
                    (Trang <?php echo $page; ?> của <?php echo $total_pages; ?>)
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Hiển thị danh sách tour -->
        <?php if (empty($tours)): ?>
            <div class="no-results">
                <p> Không tìm thấy tour nào</p>
                <?php if ($keyword): ?>
                    <p style="font-size: 14px; margin-bottom: 20px;">Thử tìm kiếm với từ khóa khác hoặc xem tất cả tour</p>
                <?php endif; ?>
                <a href="products.php">Xem tất cả tour</a>
            </div>
        <?php else: ?>
            <div class="tours-grid">
                <?php foreach ($tours as $tour): ?>
                    <div class="tour-card">
                        <div class="tour-card-image">
                            <?php if (!empty($tour['image'])): ?>
                                <img src="<?php echo htmlspecialchars($tour['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($tour['title']); ?>"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            <?php else: ?>
                                <span>Chưa có hình ảnh</span>
                            <?php endif; ?>
                            <?php if ($tour['status'] === 'inactive'): ?>
                                <div class="tour-badge">Hết hàng</div>
                            <?php endif; ?>
                        </div>
                        <div class="tour-card-body">
                            <h3 class="tour-card-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                            <div class="tour-card-info">
                                <span>Khởi hành: undefined</span>
                                <span>Thời gian: undefined</span>
                            </div>
                            <div class="tour-card-price">
                                <?php echo number_format($tour['price'], 0, ',', '.'); ?> <span class="currency">VNĐ</span>
                            </div>
                            <div class="tour-card-actions">
                                <a href="product-detail.php?slug=<?php echo htmlspecialchars($tour['slug']); ?>" 
                                   class="btn-detail">Chi tiết</a>
                                <form class="add-to-cart-form" method="POST" action="../backend/actions/tour/add_to_cart.php" style="flex: 1;">
                                    <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn-cart" <?php echo $tour['status'] !== 'active' ? 'disabled' : ''; ?>>
                                        Thêm giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Phân trang -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="products.php?page=1<?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>">« Đầu</a>
                        <a href="products.php?page=<?php echo $page - 1; ?><?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>">‹ Trước</a>
                    <?php endif; ?>
                    
                    <?php 
                    $start = max(1, $page - 2);
                    $end = min($total_pages, $page + 2);
                    
                    for ($i = $start; $i <= $end; $i++): 
                    ?>
                        <?php if ($i == $page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="products.php?page=<?php echo $i; ?><?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="products.php?page=<?php echo $page + 1; ?><?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>">Sau ›</a>
                        <a href="products.php?page=<?php echo $total_pages; ?><?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>">Cuối »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    
    <div id="footer"></div>
    <script>
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
    </script>
    <script src="assets/js/main.js"></script>
    <script src="logo.js"></script>
</body>
</html>