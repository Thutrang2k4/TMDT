<?php
session_start();
require_once __DIR__ . "/../../backend/db.php";
require_once __DIR__ . "/../../backend/models/user_model.php";
require_once __DIR__ . "/../../backend/auth/check_role.php";

requireAdmin();

$message = '';
$error = '';

if (isset($_GET['message'])) {
    $message = urldecode($_GET['message']);
}
if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    
    if ($user_id && $user_id != $_SESSION['user_id']) {
        if (delete_user($conn, $user_id)) {
            $message = "Xóa người dùng thành công";
        } else {
            $error = "Lỗi khi xóa người dùng";
        }
    } elseif ($user_id == $_SESSION['user_id']) {
        $error = "Không thể xóa tài khoản của chính bạn";
    } else {
        $error = "ID người dùng không hợp lệ";
    }
}

$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
$search = trim($_GET['search'] ?? '');
$role_filter = trim($_GET['role'] ?? '');
$limit = 10;

$data = get_users_pagination($conn, $page, $limit, $search, $role_filter);
$users = $data['users'];
$total = $data['total'];
$total_pages = $data['total_pages'];

$roles = get_all_roles($conn);
$stats = get_user_stats($conn);
$total_users = get_total_users($conn);
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Admin</title>
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
                        <h2 class="page-title">Quản lý người dùng</h2>
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
                            <div>
                                <i class="bi bi-check-circle-fill me-2"></i>
                            </div>
                            <div>
                                <?= htmlspecialchars($message) ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            </div>
                            <div>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <div class="row row-cards mb-3">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                            <i class="bi bi-people-fill"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium"><?php echo $total_users; ?></div>
                                        <div class="text-muted">Tổng người dùng</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php foreach ($stats as $stat): ?>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-<?php echo $stat['name'] === 'admin' ? 'danger' : 'success'; ?> text-white avatar">
                                                <i class="bi bi-person-badge"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium"><?php echo $stat['count']; ?></div>
                                            <div class="text-muted"><?php echo htmlspecialchars(ucfirst($stat['name'])); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Filter Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tìm kiếm</label>
                                <input type="text" name="search" class="form-control" placeholder="Tên hoặc email" value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Vai trò</label>
                                <select name="role" class="form-select">
                                    <option value="">-- Tất cả vai trò --</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo htmlspecialchars($role['name']); ?>" 
                                            <?php echo ($role_filter === $role['name']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars(ucfirst($role['name'])); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-5 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i>Tìm kiếm
                                </button>
                                <a href="users.php" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <?php if (empty($users)): ?>
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-3">Không có người dùng nào</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên người dùng</th>
                                        <th>Email</th>
                                        <th>Vai trò</th>
                                        <th>Ngày tạo</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="text-muted"><?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                            <td class="text-muted"><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo strtolower($user['role_name']) === 'admin' ? 'red' : 'green'; ?>">
                                                    <?php echo htmlspecialchars($user['role_name']); ?>
                                                </span>
                                            </td>
                                            <td class="text-muted"><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <a href="user_detail.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="card-footer d-flex align-items-center">
                        <p class="m-0 text-muted">Hiển thị <span><?php echo count($users); ?></span> trong <span><?php echo $total; ?></span> người dùng</p>
                        <ul class="pagination m-0 ms-auto">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=1<?php echo ($search ? "&search=" . urlencode($search) : '') ?><?php echo ($role_filter ? "&role=" . urlencode($role_filter) : '') ?>">
                                        <i class="bi bi-chevron-bar-left"></i>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo ($search ? "&search=" . urlencode($search) : '') ?><?php echo ($role_filter ? "&role=" . urlencode($role_filter) : '') ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                            $start = max(1, $page - 2);
                            $end = min($total_pages, $page + 2);

                            for ($i = $start; $i <= $end; $i++):
                                if ($i == $page): ?>
                                    <li class="page-item active"><span class="page-link"><?php echo $i; ?></span></li>
                                <?php else: ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?><?php echo ($search ? "&search=" . urlencode($search) : '') ?><?php echo ($role_filter ? "&role=" . urlencode($role_filter) : '') ?>"><?php echo $i; ?></a></li>
                                <?php endif;
                            endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo ($search ? "&search=" . urlencode($search) : '') ?><?php echo ($role_filter ? "&role=" . urlencode($role_filter) : '') ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $total_pages; ?><?php echo ($search ? "&search=" . urlencode($search) : '') ?><?php echo ($role_filter ? "&role=" . urlencode($role_filter) : '') ?>">
                                        <i class="bi bi-chevron-bar-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    fetch("partials/sidebar.html").then(r => r.text()).then(t => document.getElementById("sidebar").innerHTML = t);
    fetch("partials/topbar.html").then(r => r.text()).then(t => document.getElementById("topbar").innerHTML = t);
</script>
</body>
</html>