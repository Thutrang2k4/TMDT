<?php session_start(); ?>
<header class="site-header">
  <div class="container header-container">
    <div class="logo">
      <img id="company-logo" src="" alt="Company logo">
      <h1 id="company-name"></h1>
      

    </div>

    <nav class="nav-bar" aria-label="Main">
      <ul>

        <li><a href="index.php">Trang chủ</a></li>
        <li><a href="about.php">Giới thiệu</a></li>
        <li><a href="news.php">Tin tức</a></li>
        <li><a href="faq.php">Hỏi đáp</a></li>
        <li><a href="contact.php">Liên hệ</a></li>
        <li><a href="products.php">Tour</a></li>
        

        <?php if(isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'member'): ?>
          <!-- Dropdown User Menu -->
          <li class="user-dropdown">
            <a href="#" class="user-menu-toggle">
              <i class="bi bi-person-circle"></i> 
              <?= htmlspecialchars($_SESSION['full_name']) ?>
              <i class="bi bi-chevron-down ms-1" style="font-size: 0.75rem;"></i>
            </a>
            <ul class="user-dropdown-menu">
              <li><a href="profile.php"><i class="bi bi-person"></i> Thông tin cá nhân</a></li>
              <li><a href="cart.php"><i class="bi bi-cart"></i> Giỏ hàng</a></li>
              <li><a href="my-orders.php"><i class="bi bi-box-seam"></i> Đơn hàng</a></li>
              <li class="dropdown-divider"></li>
              <?php if(isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'admin'): ?>
                <li><a href="admin/dashboard.php"><i class="bi bi-speedometer2"></i> Admin</a></li>
                <li class="dropdown-divider"></li>
              <?php endif; ?>
              <li><a href="../backend/auth/logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
            </ul>
          </li>

        <?php else: ?>
          <li><a href="login.php">Đăng nhập</a></li>
        <?php endif; ?>

      </ul>
    </nav>
  </div>
</header>

