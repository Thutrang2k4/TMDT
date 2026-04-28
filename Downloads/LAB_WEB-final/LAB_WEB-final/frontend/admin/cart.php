<?php
require_once __DIR__ . "/../../backend/auth/check_role.php";
require_once __DIR__ . "/../../backend/db.php";
require_once __DIR__ . "/../../backend/models/order_model.php";

requireAdmin();

$action = $_GET['action'] ?? null;
$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$status_filter = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

$orders = [];
$order_detail = null;
$stats = [];
$limit = 15;

if (isset($conn)) {
    // Lấy thống kê đơn hàng
    $stats = get_order_statistics($conn);
    
    // Lấy danh sách đơn hàng
    if ($status_filter && in_array($status_filter, ['pending', 'confirmed', 'cancelled'])) {
        $orders = get_all_orders_admin($conn, $status_filter);
    } else {
        $orders = get_all_orders_admin($conn);
    }
    
    // Nếu xem chi tiết
    if ($action === 'view' && $order_id) {
        $order_detail = get_order_by_id($conn, $order_id);
    }
    
    $conn->close();
}

// Phân trang
$total_records = count($orders);
$total_pages = ceil($total_records / $limit);
$offset = ($page - 1) * $limit;
$orders_paginated = array_slice($orders, $offset, $limit);
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Đơn hàng - Admin</title>
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
                        <h2 class="page-title">Quản lý Đơn hàng</h2>
                    </div>
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
                            <div><?php echo htmlspecialchars($message); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div><i class="bi bi-exclamation-triangle-fill me-2"></i></div>
                            <div><?php echo htmlspecialchars($error); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
        
                <!-- Thống kê -->
                <div class="row row-cards mb-3">
                    <?php
                    $status_config = [
                        'pending' => ['label' => 'Chờ xác nhận', 'icon' => 'clock-history', 'color' => 'yellow'],
                        'confirmed' => ['label' => 'Đã xác nhận', 'icon' => 'check-circle', 'color' => 'green'],
                        'cancelled' => ['label' => 'Đã hủy', 'icon' => 'x-circle', 'color' => 'red']
                    ];
                    
                    foreach ($status_config as $status => $config):
                        $count = $stats[$status]['count'] ?? 0;
                        $amount = $stats[$status]['total_amount'] ?? 0;
                    ?>
                        <div class="col-md-4">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-<?php echo $config['color']; ?> text-white avatar">
                                                <i class="bi bi-<?php echo $config['icon']; ?>"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium"><?php echo $count; ?></div>
                                            <div class="text-muted"><?php echo $config['label']; ?></div>
                                            <div class="text-muted small"><?php echo number_format($amount, 0, ',', '.'); ?> VNĐ</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
        
                <!-- Lọc theo trạng thái -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Lọc theo trạng thái:</label>
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                    <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Đã xác nhận</option>
                                    <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                                </select>
                            </div>
                            <?php if ($status_filter): ?>
                                <div class="col-md-2 d-flex align-items-end">
                                    <a href="cart.php" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Reset
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
        
                <?php if (empty($orders)): ?>
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">Chưa có đơn hàng nào.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Khách hàng</th>
                                        <th>Tour</th>
                                        <th>SL</th>
                                        <th>Tổng tiền</th>
                                        <th>Ngày đặt</th>
                                        <th>Trạng thái</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $status_labels = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                    
                                    foreach ($orders_paginated as $order): 
                                    ?>
                                        <tr>
                                            <td class="text-muted"><strong>#<?php echo $order['id']; ?></strong></td>
                                            <td>
                                                <div><strong><?php echo htmlspecialchars($order['user_name']); ?></strong></div>
                                                <div class="text-muted small"><?php echo htmlspecialchars($order['user_email'] ?? 'N/A'); ?></div>
                                            </td>
                                            <td><?php echo htmlspecialchars($order['tour_title']); ?></td>
                                            <td><?php echo $order['quantity']; ?></td>
                                            <td><strong><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</strong></td>
                                            <td class="text-muted"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                            <td>
                                                <?php
                                                $badge_class = [
                                                    'pending' => 'bg-yellow',
                                                    'confirmed' => 'bg-green',
                                                    'cancelled' => 'bg-red'
                                                ];
                                                ?>
                                                <span class="badge <?php echo $badge_class[$order['status']] ?? 'bg-secondary'; ?>">
                                                    <?php echo $status_labels[$order['status']] ?? 'Không xác định'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <button class="btn btn-sm btn-info" onclick="openOrderModal(<?php echo htmlspecialchars(json_encode($order)); ?>)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    
                                                    <?php if ($order['status'] === 'pending'): ?>
                                                        <form method="POST" action="../../backend/actions/tour/admin_cart.php" class="d-inline">
                                                            <input type="hidden" name="action" value="confirm">
                                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                            <input type="hidden" name="status_filter" value="<?php echo htmlspecialchars($status_filter); ?>">
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="bi bi-check-lg"></i>
                                                            </button>
                                                        </form>
                                                    <?php elseif ($order['status'] === 'confirmed'): ?>
                                                        <form method="POST" action="../../backend/actions/tour/admin_cart.php" class="d-inline">
                                                            <input type="hidden" name="action" value="cancel">
                                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                            <input type="hidden" name="status_filter" value="<?php echo htmlspecialchars($status_filter); ?>">
                                                            <button type="submit" class="btn btn-sm btn-warning">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    
                                                    <form method="POST" action="../../backend/actions/tour/admin_cart.php" class="d-inline">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                        <input type="hidden" name="status_filter" value="<?php echo htmlspecialchars($status_filter); ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">
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
                                <p class="m-0 text-muted">Hiển thị <span><?php echo count($orders_paginated); ?></span> trong tổng số <span><?php echo $total_records; ?></span> đơn hàng</p>
                                <ul class="pagination m-0 ms-auto">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="cart.php?page=1<?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>">
                                                <i class="bi bi-chevron-bar-left"></i>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="cart.php?page=<?php echo $page - 1; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>">
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
                                            <a class="page-link" href="cart.php?page=<?php echo $i; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="cart.php?page=<?php echo $page + 1; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="cart.php?page=<?php echo $total_pages; ?><?php echo $status_filter ? '&status=' . urlencode($status_filter) : ''; ?>">
                                                <i class="bi bi-chevron-bar-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal xem chi tiết -->
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    fetch("partials/sidebar.html").then(r=>r.text()).then(t=>document.getElementById("sidebar").innerHTML=t);
    fetch("partials/topbar.html").then(r=>r.text()).then(t=>document.getElementById("topbar").innerHTML=t);
    
    function openOrderModal(order) {
        const statusLabels = {
            'pending': 'Chờ xác nhận',
            'confirmed': 'Đã xác nhận',
            'cancelled': 'Đã hủy'
        };
        
        const statusBadge = {
            'pending': 'bg-warning text-dark',
            'confirmed': 'bg-success',
            'cancelled': 'bg-danger'
        };
        
        const html = `
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Mã đơn hàng</label>
                    <p class="mb-0">#${order.id}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Ngày đặt</label>
                    <p class="mb-0">${new Date(order.order_date).toLocaleDateString('vi-VN', {year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Khách hàng</label>
                    <p class="mb-0">${order.user_name}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Email</label>
                    <p class="mb-0">${order.user_email || 'N/A'}</p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold text-muted small">Tour</label>
                    <p class="mb-0">${order.tour_title}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Số lượng</label>
                    <p class="mb-0">${order.quantity}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted small">Tổng tiền</label>
                    <p class="mb-0 fw-bold text-primary">${new Intl.NumberFormat('vi-VN', {style: 'currency', currency: 'VND'}).format(order.total_price)}</p>
                <div class="col-12">
                    <label class="form-label fw-bold text-muted small">Trạng thái</label>
                    <p class="mb-0">
                        <span class="badge ${statusBadge[order.status] || 'bg-secondary'}">
                            ${statusLabels[order.status] || 'Không xác định'}
                        </span>
                    </p>
                </div>
            </div>
        `;
        document.getElementById('orderModalBody').innerHTML = html;
        new bootstrap.Modal(document.getElementById('orderModal')).show();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</html>