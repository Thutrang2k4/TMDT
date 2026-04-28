<?php
// logout.php

// Bắt đầu session
session_start();

// Xóa tất cả dữ liệu trong session
$_SESSION = [];

// Hủy session trên server
session_destroy();

// Xóa cookie session trên trình duyệt (nếu có)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000, // thời gian đã hết hạn
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Chuyển hướng về trang login hoặc trang chủ
header("Location: ../../frontend/login.php");
exit;
