<?php
require_once __DIR__ . "/../../backend/auth/check_role.php";
require_once __DIR__ . "/../../backend/db.php";
require_once __DIR__ . "/../../backend/models/tour_model.php";

requireAdmin();

$action = $_GET['action'] ?? 'list';
$keyword = filter_input(INPUT_GET, 'keyword', FILTER_SANITIZE_STRING);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

$tours = [];
$total_pages = 0;
$tour = null;
$limit = 10;

if (isset($conn)) {
    if ($action === 'list') {
        $tours_data = get_tours_pagination_admin($conn, $keyword, $page, $limit);
        $tours = $tours_data['data'] ?? [];
        $total_pages = $tours_data['total_pages'] ?? 0;
    } elseif ($action === 'edit' || $action === 'add') {
        $tour_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($action === 'edit' && $tour_id) {
            $tour = get_tour_by_id($conn, $tour_id);
            if (!$tour) {
                $error = "Không tìm thấy tour.";
                $action = 'list';
            }
        }
    }
    
    if ($action !== 'list') {
        // Giữ lại connection cho form
    } else {
        $conn->close();
    }
}
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Tour - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="page">
    <!-- Sidebar -->
    <div id="sidebar"></div>

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Topbar -->
        <div id="topbar"></div>

        <!-- Page Header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            <?php echo $action === 'add' ? 'Thêm Tour Mới' : ($action === 'edit' ? 'Sửa Tour' : 'Quản lý Tour'); ?>
                        </h2>
                    </div>
                    <?php if ($action === 'list'): ?>
                        <div class="col-auto ms-auto">
                            <a href="products.php?action=add" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Thêm Tour
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="col-auto ms-auto">
                            <a href="products.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <!-- Alerts -->
                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div><i class="bi bi-check-circle-fill me-2"></i></div>
                            <div><?= htmlspecialchars($message) ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div><i class="bi bi-exclamation-triangle-fill me-2"></i></div>
                            <div><?= htmlspecialchars($error) ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
        
                <?php if ($action === 'list'): ?>
                    <!-- Danh sách tour -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <form class="row g-3" method="GET">
                                <div class="col-md-10">
                                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm tour..." 
                                           value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search me-1"></i> Tìm kiếm
                                    </button>
                                </div>
                                <?php if ($keyword): ?>
                                    <div class="col-12">
                                        <a href="products.php" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-x-circle me-1"></i> Xóa bộ lọc
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
            
                    <?php if (empty($tours)): ?>
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-0">Chưa có tour nào.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tiêu đề</th>
                                            <th>Slug</th>
                                            <th>Thời gian</th>
                                            <th>Giá</th>
                                            <th>Trạng thái</th>
                                            <th class="w-1"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tours as $t): ?>
                                            <tr>
                                                <td class="text-muted"><?php echo $t['id']; ?></td>
                                                <td><?php echo htmlspecialchars($t['title']); ?></td>
                                                <td><code class="small"><?php echo htmlspecialchars($t['slug']); ?></code></td>
                                                <td class="text-muted"><?php echo $t['duration_days']; ?> ngày</td>
                                                <td><strong><?php echo number_format($t['price'], 0, ',', '.'); ?>₫</strong></td>
                                                <td>
                                                    <?php if ($t['status'] === 'active'): ?>
                                                        <span class="badge bg-green">Hoạt động</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Tạm dừng</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-list flex-nowrap">
                                                        <a href="products.php?action=edit&id=<?php echo $t['id']; ?>" class="btn btn-sm btn-primary">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form method="POST" action="../../backend/actions/tour/admin_tour.php" class="d-inline">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="tour_id" value="<?php echo $t['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa tour này?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                    
                            <?php if ($total_pages > 1): ?>
                                <div class="card-footer d-flex align-items-center">
                                    <p class="m-0 text-muted">Hiển thị <span><?php echo count($tours); ?></span> trong tổng số <span><?php echo array_sum(array_column($tours_data['data'] ?? [], 'id')) ? 'nhiều' : count($tours); ?></span> tour</p>
                                    <ul class="pagination m-0 ms-auto">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="products.php?page=1<?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>">
                                                    <i class="bi bi-chevron-bar-left"></i>
                                                </a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="products.php?page=<?php echo $page - 1; ?><?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>">
                                                    <i class="bi bi-chevron-left"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php 
                                        $start = max(1, $page - 2);
                                        $end = min($total_pages, $page + 2);
                                        
                                        for ($i = $start; $i <= $end; $i++): 
                                        ?>
                                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                <a class="page-link" href="products.php?page=<?php echo $i; ?><?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>
                                        
                                        <?php if ($page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="products.php?page=<?php echo $page + 1; ?><?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>">
                                                    <i class="bi bi-chevron-right"></i>
                                                </a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="products.php?page=<?php echo $total_pages; ?><?php echo $keyword ? '&keyword=' . urlencode($keyword) : ''; ?>">
                                                    <i class="bi bi-chevron-bar-right"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
            
                <?php elseif ($action === 'add' || $action === 'edit'): ?>
                    <!-- Form thêm/sửa tour -->
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="../../backend/actions/tour/admin_tour.php">
                                <input type="hidden" name="action" value="<?php echo $action; ?>">
                                <?php if ($action === 'edit' && $tour): ?>
                                    <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control" required 
                                           value="<?php echo htmlspecialchars($tour['title'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Mô tả ngắn <span class="text-danger">*</span></label>
                                    <textarea id="short_description" name="short_description" class="form-control" rows="4" required
                                             ><?php echo htmlspecialchars($tour['short_description'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                                        <input type="number" id="price" name="price" class="form-control" step="0.01" required 
                                               value="<?php echo htmlspecialchars($tour['price'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="duration_days" class="form-label">Thời gian (ngày) <span class="text-danger">*</span></label>
                                        <input type="number" id="duration_days" name="duration_days" class="form-control" required 
                                               value="<?php echo htmlspecialchars($tour['duration_days'] ?? ''); ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select id="status" name="status" class="form-select" required>
                                        <option value="active" <?php echo ($tour['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                                        <option value="inactive" <?php echo ($tour['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Tạm dừng</option>
                                    </select>
                                </div>
                                
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="products.php" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Lưu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    fetch("partials/sidebar.html").then(r=>r.text()).then(t=>document.getElementById("sidebar").innerHTML=t);
    fetch("partials/topbar.html").then(r=>r.text()).then(t=>document.getElementById("topbar").innerHTML=t);
</script>
</body>
</html>