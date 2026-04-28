<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tin tức - TravelCo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .news-card-img { height: 220px; object-fit: cover; }
        .card { transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        /* Đã xóa CSS nút delete nổi */
    </style>
</head>
<body>

<?php include 'partials/header.php'; ?>

<!-- Header & Toolbar -->
<div class="bg-light py-5 mb-4 border-bottom">
    <div class="container text-center">
        <h1 class="display-5 fw-bold text-dark">Tin Tức Du Lịch</h1>
        <p class="lead text-muted mb-4">Cập nhật những thông tin mới nhất</p>
        
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <!-- Nút mở Modal Đăng tin -->
            <button class="btn btn-success px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                <i class="bi bi-pencil-square me-1"></i> Viết bài mới
            </button>
            
            <!-- Tìm kiếm -->
            <div class="input-group w-auto">
                <input type="text" id="searchInput" class="form-control" placeholder="Tìm bài viết..." style="min-width: 250px;">
                <button class="btn btn-primary" type="button" id="searchBtn"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </div>
</div>

<main class="container mb-5">
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    <!-- Danh sách tin tức -->
    <div id="newsList" class="row gy-4"></div>
</main>

<!-- =========================================== -->
<!-- MODAL: FORM ĐĂNG TIN MỚI -->
<!-- =========================================== -->
<div class="modal fade" id="addNewsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addNewsForm">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Soạn bài viết mới</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg" placeholder="Nhập tiêu đề..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung chi tiết <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="10" placeholder="Nhập nội dung bài viết tại đây..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="bi bi-send me-1"></i> Đăng bài
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const newsList = document.getElementById("newsList");
    const loading = document.getElementById("loading");
    const searchInput = document.getElementById("searchInput");
    
    // --- 1. TẢI DANH SÁCH TIN ---
    function loadNews(keyword = '') {
        loading.style.display = 'block';
        newsList.innerHTML = '';
        
        fetch(`../backend/news/get_all.php?search=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(data => {
                loading.style.display = 'none';

                if (!data.success || !data.news || data.news.length === 0) {
                    newsList.innerHTML = `<div class="col-12 text-center py-5"><p class="text-muted fs-5">Không tìm thấy bài viết nào.</p></div>`;
                    return;
                }

                data.news.forEach(item => {
                    const dateStr = item.created_at ? new Date(item.created_at).toLocaleDateString('vi-VN') : 'Mới';

                    const col = document.createElement("div");
                    col.className = "col-md-4";
                    
                    // Giao diện thẻ tin (Card) - ĐÃ XÓA NÚT DELETE
                    col.innerHTML = `
                        <div class="card shadow-sm h-100 border-0 position-relative">
                            
                            <!-- Nội dung bài viết -->
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">
                                        ${item.category_name || 'Tin tức'}
                                    </span>
                                    <small class="text-muted"><i class="bi bi-clock"></i> ${dateStr}</small>
                                </div>
                                
                                <h4 class="card-title fw-bold mt-2 mb-3">
                                    <a href="news_detail.php?id=${item.id}" class="text-decoration-none text-dark stretched-link">
                                        ${item.title}
                                    </a>
                                </h4>
                                
                                <p class="card-text text-secondary text-truncate" style="-webkit-line-clamp: 4; display: -webkit-box; -webkit-box-orient: vertical; white-space: normal;">
                                    ${item.summary || item.title}
                                </p>
                                
                                <div class="mt-auto pt-3 border-top">
                                    <a href="news_detail.php?id=${item.id}" class="text-primary text-decoration-none fw-bold small">
                                        Đọc tiếp <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    newsList.appendChild(col);
                });
            })
            .catch(err => {
                loading.style.display = 'none';
                newsList.innerHTML = `<div class="col-12 text-center text-danger">Lỗi kết nối server: ${err.message}</div>`;
            });
    }

    // --- 2. XỬ LÝ ĐĂNG TIN ---
    document.getElementById('addNewsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang lưu...';
        submitBtn.disabled = true;

        const formData = new FormData(this);

        fetch('../backend/news/create.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Đăng tin thành công!');
                this.reset(); 
                bootstrap.Modal.getInstance(document.getElementById('addNewsModal')).hide(); 
                loadNews(); 
            } else {
                alert('Thất bại: ' + data.message);
            }
        })
        .catch(err => alert('Lỗi: ' + err.message))
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // --- 3. TÌM KIẾM ---
    document.getElementById('searchBtn').addEventListener('click', () => loadNews(searchInput.value));
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') loadNews(searchInput.value);
    });

    loadNews();
});
</script>
<script src="assets/js/main.js"></script>
<script src="logo.js"></script>
</body>
</html>