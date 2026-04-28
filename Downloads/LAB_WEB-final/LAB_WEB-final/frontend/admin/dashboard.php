<?php
// Gọi hàm bảo vệ trang
require_once __DIR__ . "/../../backend/auth/check_role.php";
requireAdmin();
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Admin</title>
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
                        <h2 class="page-title">Bảng điều khiển</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
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
                                        <div class="font-weight-medium">120</div>
                                        <div class="text-muted">Người dùng</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-success text-white avatar">
                                            <i class="bi bi-map-fill"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">24</div>
                                        <div class="text-muted">Tours</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-warning text-white avatar">
                                            <i class="bi bi-cart-fill"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">--</div>
                                        <div class="text-muted">Đơn hàng</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-info text-white avatar">
                                            <i class="bi bi-newspaper"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">--</div>
                                        <div class="text-muted">Tin tức</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Welcome Message -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Chào mừng quay trở lại!</h3>
                        <p class="text-muted">
                            Sử dụng menu bên trái để quản lý hệ thống. 
                            Bạn có thể quản lý người dùng, tour du lịch, tin tức và nhiều tính năng khác.
                        </p>
                    </div>
                </div>
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
