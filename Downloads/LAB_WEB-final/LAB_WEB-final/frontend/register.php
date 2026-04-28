<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký - TravelCo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
          
    <link rel="stylesheet" href="assets/css/style.css"> 
</head>
<body>
    <div id="header"></div>
    <main class="container py-5">
        
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h2 class="text-center mb-4">Đăng ký tài khoản</h2> 
                
                <form id="registerForm">
                    
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Tên đầy đủ:</label>
                        <input type="text" id="full_name" name="full_name" required 
                               class="form-control" 
                               maxlength="100" placeholder="Nhập tên đầy đủ của bạn">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" required 
                               class="form-control" 
                               maxlength="150" placeholder="Nhập địa chỉ email">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu:</label>
                        <input type="password" id="password" name="password" required 
                               class="form-control" 
                               minlength="8" placeholder="Nhập mật khẩu (tối thiểu 8 ký tự)">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mt-3">Đăng ký</button>
                     <div id="registerError" class="text-danger mt-2"></div>

                    <p class="text-center mt-3">
                        Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
                    </p>
                </form>
            </div>
        </div>
        
    </main>
    <div id="footer"></div>
    
    <script>
        const form = document.getElementById('registerForm');
        const errorDiv = document.getElementById('registerError');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorDiv.textContent = '';

            const formData = new FormData(form);

            const res = await fetch('../backend/auth/register.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                window.location.href = data.redirect;
            } else {
                errorDiv.textContent = data.message;
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
    <script src="logo.js"></script>
</body>
</html>