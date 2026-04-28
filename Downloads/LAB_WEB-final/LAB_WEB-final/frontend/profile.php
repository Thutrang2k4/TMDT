<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông tin cá nhân - GoTour</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css"> 
</head>
<body>

<div id="header"></div>

<main class=" py-5">
    <h2 class="text-center mb-4">Thông tin cá nhân</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card p-4 shadow-sm">

                <div class="row">
                    <!-- Avatar -->
                    <div class="col-md-4 d-flex flex-column align-items-center">
                        <div id="avatarPreview" 
                             class="rounded-circle mb-3 d-flex align-items-center justify-content-center"
                             style="width: 140px; height: 140px; background: #e9ecef; overflow: hidden;">
                            <i class="bi bi-person-fill" style="font-size: 4rem; color: #6c757d;"></i>
                        </div>

                        <input type="file" id="avatarInput" accept="image/*" style="display: none;">
                        <button type="button" class="btn w-100 mt-2" id="changeAvatarBtn">Thay đổi avatar</button>
                        <p id="avatarMessage" class="mt-2 text-center small"></p>
                    </div>

                    <!-- Thông tin -->
                    <div class="col-md-8">
                        <form id="profileForm">

                            <div class="mb-3">
                                <label class="form-label">Họ tên</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" disabled>
                            </div>

                            <button type="submit" class="btn w-100 mt-2">Thay đổi thông tin</button>

                            <p id="message" class="mt-3 text-center"></p>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Đổi mật khẩu -->
            <div class="card p-4 shadow-sm mt-4">
                <h4 class="mb-3">Đổi mật khẩu</h4>
                
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        <small class="text-muted">Tối thiểu 8 ký tự</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8">
                    </div>

                    <button type="submit" class="btn w-100">Đổi mật khẩu</button>

                    <p id="passwordMessage" class="mt-3 text-center"></p>
                </form>
            </div>

        </div>
    </div>
</main>

<div id="footer"></div>

<script src="assets/js/main.js"></script>

<script>
    const avatarInput = document.getElementById('avatarInput');
    const changeAvatarBtn = document.getElementById('changeAvatarBtn');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarMessage = document.getElementById('avatarMessage');
    const changePasswordForm = document.getElementById('changePasswordForm');
    const passwordMessage = document.getElementById('passwordMessage');

    // Load profile
    fetch("../backend/actions/user/get_profile.php")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                full_name.value = data.user.full_name;
                email.value = data.user.email;
                
                // Cập nhật avatar nếu có
                if (data.user.avatar) {
                    // Chuyển div thành img element
                    avatarPreview.innerHTML = `<img src="${data.user.avatar}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">`;
                }
            } else {
                message.textContent = data.message;
                message.style.color = "red";
            }
        })
        .catch(err => {
            message.textContent = "Lỗi kết nối server!";
            message.style.color = "red";
        });

    // Change avatar button click
    changeAvatarBtn.addEventListener('click', () => {
        avatarInput.click();
    });

    // Avatar file selected
    avatarInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            avatarMessage.textContent = "Vui lòng chọn file ảnh!";
            avatarMessage.style.color = "red";
            return;
        }

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            avatarMessage.textContent = "Kích thước ảnh tối đa 2MB!";
            avatarMessage.style.color = "red";
            return;
        }

        // Preview image
        const reader = new FileReader();
        reader.onload = (e) => {
            // Thay thế nội dung div bằng img
            avatarPreview.innerHTML = `<img src="${e.target.result}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">`;
        };
        reader.readAsDataURL(file);

        // Upload avatar
        const formData = new FormData();
        formData.append('avatar', file);

        avatarMessage.textContent = "Đang tải lên...";
        avatarMessage.style.color = "blue";

        fetch("../backend/actions/user/upload_avatar.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            avatarMessage.textContent = data.message;
            avatarMessage.style.color = data.success ? "green" : "red";

            if (data.success) {
                setTimeout(() => {
                    avatarMessage.textContent = "";
                }, 2000);
            }
        })
        .catch(err => {
            avatarMessage.textContent = "Lỗi kết nối server!";
            avatarMessage.style.color = "red";
        });
    });

    // Update profile
    profileForm.addEventListener("submit", e => {
        e.preventDefault();
        
        const formData = new FormData(profileForm);
        
        fetch("../backend/actions/user/update_profile.php", { 
            method: "POST", 
            body: formData 
        })
        .then(res => res.json())
        .then(data => {
            message.textContent = data.message;
            message.style.color = data.success ? "green" : "red";
            
            if (data.success) {
                // Reload sau 1.5s
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        })
        .catch(err => {
            message.textContent = "Lỗi kết nối server!";
            message.style.color = "red";
        });
    });

    // Change password
    changePasswordForm.addEventListener("submit", e => {
        e.preventDefault();
        
        const formData = new FormData(changePasswordForm);
        
        // Client-side validation
        const newPassword = formData.get('new_password');
        const confirmPassword = formData.get('confirm_password');
        
        if (newPassword !== confirmPassword) {
            passwordMessage.textContent = "Mật khẩu xác nhận không khớp!";
            passwordMessage.style.color = "red";
            return;
        }
        
        fetch("../backend/actions/user/change_password.php", { 
            method: "POST", 
            body: formData 
        })
        .then(res => res.json())
        .then(data => {
            passwordMessage.textContent = data.message;
            passwordMessage.style.color = data.success ? "green" : "red";
            
            if (data.success) {
                // Reset form và reload sau 2s
                changePasswordForm.reset();
                setTimeout(() => {
                    passwordMessage.textContent = "";
                }, 2000);
            }
        })
        .catch(err => {
            passwordMessage.textContent = "Lỗi kết nối server!";
            passwordMessage.style.color = "red";
        });
    });
</script>
<script src="assets/js/main.js"></script>
<script src="logo.js"></script>
</body>
</html>
