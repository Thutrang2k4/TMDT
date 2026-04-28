<?php
session_start();
require_once __DIR__ . "/../../backend/auth/check_role.php";
require_once __DIR__ . "/../../backend/models/content_model.php";

requireAdmin();

$about = getAboutContent();

$success_msg = '';
$error_msg = '';

if (isset($_GET['success'])) {
    $success_msg = 'Cập nhật nội dung thành công!';
}

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'validation') {
        $error_msg = 'Có lỗi trong dữ liệu nhập vào. Vui lòng kiểm tra lại.';
        if (isset($_SESSION['about_errors'])) {
            $errors = $_SESSION['about_errors'];
            unset($_SESSION['about_errors']);
        }
        if (isset($_SESSION['about_form_data'])) {
            $about = array_merge($about, $_SESSION['about_form_data']);
            unset($_SESSION['about_form_data']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý trang Giới thiệu - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        textarea.form-control {
            padding: 12px 15px;
            line-height: 1.6;
        }
        
        input[type="text"].form-control {
            padding: 10px 15px;
        }
    </style>
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
                        <h2 class="page-title">Quản lý trang Giới thiệu</h2>
                    </div>
                    <div class="col-auto ms-auto">
                        <a href="../about.php" class="btn btn-white" target="_blank">
                            <i class="bi bi-eye me-2"></i>Xem trang công khai
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                
                <?php if ($success_msg): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div><i class="bi bi-check-circle-fill me-2"></i></div>
                            <div><?php echo htmlspecialchars($success_msg); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error_msg): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div><i class="bi bi-exclamation-triangle-fill me-2"></i></div>
                            <div>
                                <?php echo htmlspecialchars($error_msg); ?>
                                <?php if (isset($errors)): ?>
                                    <ul class="mb-0 mt-2">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                        <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form action="../../backend/actions/content/process_about.php" method="POST" enctype="multipart/form-data" id="aboutForm">
                            <div class="mb-3">
                                <label class="form-label" for="title">Tiêu đề trang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?php echo htmlspecialchars($about['title'] ?? ''); ?>" 
                                       required maxlength="255">
                                <small class="form-hint">Tiêu đề chính của trang giới thiệu (tối đa 255 ký tự)</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" for="content">Nội dung giới thiệu <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="content" name="content" rows="8" required><?php echo htmlspecialchars($about['content'] ?? ''); ?></textarea>
                                <small class="form-hint">Nội dung giới thiệu chi tiết về công ty</small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="mission">Sứ mệnh <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="mission" name="mission" rows="5" required><?php echo htmlspecialchars($about['mission'] ?? ''); ?></textarea>
                                    <small class="form-hint">Sứ mệnh của công ty</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="vision">Tầm nhìn <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="vision" name="vision" rows="5" required><?php echo htmlspecialchars($about['vision'] ?? ''); ?></textarea>
                                    <small class="form-hint">Tầm nhìn của công ty</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" for="image">Hình ảnh banner</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <small class="form-hint">Chọn hình ảnh mới (JPG, PNG, GIF, WEBP - tối đa 5MB). Để trống nếu không muốn thay đổi.</small>
                                
                                <?php if (!empty($about['image_url'])): ?>
                                    <div class="mt-3">
                                        <p class="mb-2 fw-bold">Hình ảnh hiện tại:</p>
                                        <img src="../../frontend/<?php echo htmlspecialchars($about['image_url']); ?>" 
                                             alt="Current banner" class="img-fluid rounded border" style="max-width: 400px;" id="currentImage">
                                    </div>
                                <?php endif; ?>
                                
                                <div id="imagePreview" style="display: none;">
                                    <p class="mt-3 mb-2 fw-bold">Xem trước hình ảnh mới:</p>
                                    <img src="" alt="Preview" class="img-fluid rounded border" style="max-width: 400px;" id="previewImg">
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Lưu thay đổi
                                </button>
                            </div>
                        </form>
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
        
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
        
        document.getElementById('aboutForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            const mission = document.getElementById('mission').value.trim();
            const vision = document.getElementById('vision').value.trim();
            
            if (!title || !content || !mission || !vision) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ các trường bắt buộc!');
                return false;
            }
            
            if (title.length > 255) {
                e.preventDefault();
                alert('Tiêu đề không được vượt quá 255 ký tự!');
                return false;
            }
            
            const imageFile = document.getElementById('image').files[0];
            if (imageFile) {
                const maxSize = 5 * 1024 * 1024;
                if (imageFile.size > maxSize) {
                    e.preventDefault();
                    alert('Kích thước file ảnh không được vượt quá 5MB!');
                    return false;
                }
                
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(imageFile.type)) {
                    e.preventDefault();
                    alert('Chỉ chấp nhận file ảnh định dạng JPG, PNG, GIF, WEBP!');
                    return false;
                }
            }
            
            return true;
        });
    </script>
</body>
</html>
