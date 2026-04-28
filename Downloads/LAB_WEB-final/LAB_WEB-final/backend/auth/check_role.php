<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAdmin() {
    if(!isset($_SESSION['role_name']) || $_SESSION['role_name'] != 'admin'){
        header("Location: /frontend/index.php");
        exit();
    }
}

function requireMember() {
    if(!isset($_SESSION['role_name']) || ($_SESSION['role_name'] != 'member' && $_SESSION['role_name'] != 'admin')){
        header("Location: /frontend/login.php");
        exit();
    }
}

// Bảo vệ trang với role kiểm tra
function protect_page($role = 'admin') {
    if (!isset($_SESSION['role_name'])) {
        header("Location: /frontend/login.php");
        exit();
    }
    
    if ($role === 'admin' && $_SESSION['role_name'] !== 'admin') {
        header("Location: /frontend/index.php");
        exit();
    }
    
    if ($role === 'member' && !in_array($_SESSION['role_name'], ['member', 'admin'])) {
        header("Location: /frontend/login.php");
        exit();
    }
}
?>