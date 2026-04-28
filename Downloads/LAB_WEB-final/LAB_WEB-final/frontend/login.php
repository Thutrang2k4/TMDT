<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập - TravelCo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header include -->
    <div id="header"></div>

    <!-- Main content -->
    <main class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm login-card" style="min-width: 350px;">
            <h2 class="card-title text-center mb-4">Đăng nhập</h2>
            <!-- <form action="../backend/login.php" method="POST"> -->
                <form id="loginForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required placeholder="Nhập tên đăng nhập">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Nhập mật khẩu">
                </div>
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                <div class="error-msg" id="errorMsg"></div>
            </form>
            <p class="text-center mt-3">
                Chưa có tài khoản? <a href="register.php">Đăng ký</a>
            </p>
        </div>
    </main>

    <!-- Footer include -->
    <div id="footer"></div>

    <!-- Bootstrap JS -->
     <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);

            const res = await fetch('../backend/auth/login.php', {
                method: 'POST',
                body: data
            });

            const result = await res.json();

            if (result.success) {
                // Redirect theo role
                window.location.href = result.redirect;
            } else {
                // Hiển thị lỗi
                document.getElementById('errorMsg').textContent = result.message;
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="logo.js"></script>
</body>
</html>