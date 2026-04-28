<?php
session_start();
require_once __DIR__ . "/../../backend/db.php";
require_once __DIR__ . "/../../backend/models/user_model.php";
require_once __DIR__ . "/../../backend/auth/check_role.php";

requireAdmin();

$message = '';
$error = '';

// Lấy user_id từ URL
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$user_id) {
    header("Location: users.php?error=" . urlencode("ID người dùng không hợp lệ"));
    exit;
}

// Lấy thông tin người dùng
$user = get_user_by_id($conn, $user_id);

if (!$user) {
    header("Location: users.php?error=" . urlencode("Người dùng không tồn tại"));
    exit;
}

// Xử lý form submit từ backend action
if (isset($_GET['message'])) {
    $message = urldecode($_GET['message']);
}
if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}

$roles = get_all_roles($conn);
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết người dùng - Admin</title>
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
                        <h2 class="page-title">Chi tiết người dùng</h2>
                        <div class="text-muted mt-1">ID: <?php echo $user['id']; ?> - <?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                    <div class="col-auto">
                        <a href="users.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                        </a>
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
                                <?php echo htmlspecialchars($message); ?>
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
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Thông tin cơ bản -->
                    <div class="col-lg-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin cơ bản</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="../../backend/actions/user/update_user.php">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                                    <div class="mb-3">
                                        <label class="form-label required">Tên đầy đủ</label>
                                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Vai trò</label>
                                        <select name="role_id" class="form-select" required>
                                            <?php foreach ($roles as $role): ?>
                                                <option value="<?php echo $role['id']; ?>" 
                                                    <?php echo ($user['role_id'] == $role['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars(ucfirst($role['name'])); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ngày tạo</label>
                                        <input type="text" class="form-control" value="<?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?>" readonly>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i> Cập nhật thông tin
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Reset mật khẩu -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Đặt lại mật khẩu</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Nhập mật khẩu mới cho người dùng này. Mật khẩu phải có ít nhất 6 ký tự.
                                </p>
                                <form method="POST" action="../../backend/actions/user/reset_password.php">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                                    <div class="mb-3">
                                        <label class="form-label required">Mật khẩu mới</label>
                                        <input type="password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới" required minlength="6">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Xác nhận mật khẩu</label>
                                        <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required minlength="6">
                                    </div>

                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Bạn có chắc chắn muốn đặt lại mật khẩu cho người dùng này?')">
                                        <i class="bi bi-key-fill me-1"></i> Đặt lại mật khẩu
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar thông tin -->
                    <div class="col-lg-4">
                        <!-- Thông tin nhanh -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin nhanh</h3>
                            </div>
                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">ID</div>
                                        <div class="datagrid-content">#<?php echo $user['id']; ?></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Vai trò</div>
                                        <div class="datagrid-content">
                                            <span class="badge bg-<?php echo strtolower($user['role_name']) === 'admin' ? 'red' : 'green'; ?>">
                                                <?php echo htmlspecialchars($user['role_name']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Ngày tạo</div>
                                        <div class="datagrid-content"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Giờ tạo</div>
                                        <div class="datagrid-content"><?php echo date('H:i:s', strtotime($user['created_at'])); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tùy chọn nguy hiểm -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-danger">Vùng nguy hiểm</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Các hành động dưới đây không thể hoàn tác!
                                </p>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" action="../../backend/actions/user/delete_user.php" onsubmit="return confirm('Bạn có CHẮC CHẮN muốn xoá người dùng này?\n\nHành động này không thể hoàn tác!')">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-trash me-1"></i> Xoá người dùng
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Bạn không thể xoá tài khoản của chính mình
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
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
