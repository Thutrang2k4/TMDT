<?php
// Kiểm tra quyền Admin (đường dẫn tương đối từ frontend/admin/ ra backend)
require_once __DIR__ . "/../../backend/auth/check_role.php";
requireAdmin(); 
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Tin tức - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .news-card-img { height: 180px; object-fit: cover; }
        .card { transition: transform 0.2s; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        
        /* Đảm bảo các card có chiều cao đồng nhất */
        .card { height: 100%; display: flex; flex-direction: column; }
        .card-body { flex: 1; display: flex; flex-direction: column; }
        
        /* Giới hạn tiêu đề 2 dòng */
        .card-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
            min-height: 2.8em; /* 2 dòng */
        }
        
        /* Giới hạn summary 2 dòng */
        .card-text-summary {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
            min-height: 3em; /* 2 dòng */
            margin-top: auto; /* Đẩy xuống dưới cùng */
        }
        
        /* Nút xóa nổi trên card */
        .btn-delete-float {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            background: #fff;
            border: 1px solid #dc3545;
            color: #dc3545;
            width: 32px; height: 32px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            opacity: 0.8; transition: all 0.2s;
        }
        .btn-delete-float:hover { background: #dc3545; color: #fff; opacity: 1; transform: scale(1.1); }
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
                        <h2 class="page-title">Quản lý Tin tức</h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="d-flex gap-2">
                            <!-- Search -->
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Tìm bài viết...">
                                <button class="btn btn-white" onclick="loadNews(document.getElementById('searchInput').value)">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            
                            <!-- Add Button -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                                <i class="bi bi-plus-lg me-1"></i> Thêm bài viết
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div id="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>

                <!-- News Grid -->
                <div id="newsList" class="row row-cards"></div>
                
                <!-- Pagination -->
                <div id="paginationContainer" class="mt-4 d-flex justify-content-between align-items-center" style="display: none !important;">
                    <div class="text-muted">
                        Hiển thị <strong id="showingStart">0</strong> - <strong id="showingEnd">0</strong> 
                        trong tổng số <strong id="totalNews">0</strong> bài viết
                    </div>
                    <ul class="pagination m-0" id="pagination"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Tin -->
<div class="modal fade" id="addNewsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addNewsForm">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm bài viết mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="8" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary px-4">Đăng bài</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Load Layout
    fetch("partials/sidebar.html").then(r=>r.text()).then(t=>document.getElementById("sidebar").innerHTML=t);
    fetch("partials/topbar.html").then(r=>r.text()).then(t=>document.getElementById("topbar").innerHTML=t);

    const newsList = document.getElementById("newsList");
    const loading = document.getElementById("loading");
    const searchInput = document.getElementById("searchInput");
    
    let currentPage = 1;
    let currentKeyword = '';
    const itemsPerPage = 9; // 3 hàng x 3 cột

    // 1. Tải danh sách tin tức với phân trang
    function loadNews(keyword = '', page = 1) {
        loading.style.display = 'block';
        newsList.innerHTML = '';
        document.getElementById('paginationContainer').style.display = 'none';
        
        currentKeyword = keyword;
        currentPage = page;

        fetch(`../../backend/news/get_all.php?search=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(data => {
                loading.style.display = 'none';
                
                if (!data.success || !data.news || data.news.length === 0) {
                    newsList.innerHTML = `<div class="col-12 text-center text-muted py-5">Không tìm thấy bài viết nào.</div>`;
                    return;
                }

                const allNews = data.news;
                const totalPages = Math.ceil(allNews.length / itemsPerPage);
                const startIndex = (page - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const newsToShow = allNews.slice(startIndex, endIndex);
                
                // Hiển thị thông tin phân trang
                document.getElementById('showingStart').textContent = startIndex + 1;
                document.getElementById('showingEnd').textContent = Math.min(endIndex, allNews.length);
                document.getElementById('totalNews').textContent = allNews.length;
                document.getElementById('paginationContainer').style.display = 'flex';

                newsToShow.forEach(item => {
                    const dateStr = item.created_at ? new Date(item.created_at).toLocaleDateString('vi-VN') : 'Mới';
                    // Admin dùng đường dẫn tương đối ra ngoài frontend/uploads
                    const imgUrl = item.image ? `../../uploads/news/${item.image}` : '../../assets/images/default-avatar.png';

                    const col = document.createElement("div");
                    col.className = "col-md-6 col-lg-4"; 
                    col.innerHTML = `
                        <div class="card position-relative">
                            <button class="btn-delete-float shadow-sm" onclick="deleteNews(${item.id}, '${item.title.replace(/'/g, "\\'")}'))" title="Xóa bài viết">
                                <i class="bi bi-trash"></i>
                            </button>

                            <div class="card-body">
                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-blue-lt">${item.category_name || 'Tin tức'}</span>
                                    <span class="text-muted small">${dateStr}</span>
                                </div>
                                <h3 class="card-title mb-3">
                                    <a href="news_detail.php?id=${item.id}" class="text-decoration-none text-reset stretched-link" title="${item.title}">
                                        ${item.title}
                                    </a>
                                </h3>
                                <p class="text-muted mb-0 small card-text-summary">
                                    ${item.summary || item.content || '...'}
                                </p>
                            </div>
                        </div>
                    `;
                    newsList.appendChild(col);
                });
                
                // Render pagination
                renderPagination(totalPages, page, allNews.length);
            })
            .catch(err => {
                loading.style.display = 'none';
                newsList.innerHTML = `<div class="col-12 text-center text-danger">Lỗi kết nối server</div>`;
            });
    }
    
    // Render pagination controls
    function renderPagination(totalPages, currentPage, totalItems) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';
        
        if (totalPages <= 1) return;
        
        // Previous button
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadNews('${currentKeyword}', ${currentPage - 1})">‹</a>`;
        pagination.appendChild(prevLi);
        
        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);
        
        if (startPage > 1) {
            const firstLi = document.createElement('li');
            firstLi.className = 'page-item';
            firstLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadNews('${currentKeyword}', 1)">1</a>`;
            pagination.appendChild(firstLi);
            
            if (startPage > 2) {
                const dotsLi = document.createElement('li');
                dotsLi.className = 'page-item disabled';
                dotsLi.innerHTML = `<span class="page-link">...</span>`;
                pagination.appendChild(dotsLi);
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadNews('${currentKeyword}', ${i})">${i}</a>`;
            pagination.appendChild(li);
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const dotsLi = document.createElement('li');
                dotsLi.className = 'page-item disabled';
                dotsLi.innerHTML = `<span class="page-link">...</span>`;
                pagination.appendChild(dotsLi);
            }
            
            const lastLi = document.createElement('li');
            lastLi.className = 'page-item';
            lastLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadNews('${currentKeyword}', ${totalPages})">${totalPages}</a>`;
            pagination.appendChild(lastLi);
        }
        
        // Next button
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadNews('${currentKeyword}', ${currentPage + 1})">›</a>`;
        pagination.appendChild(nextLi);
    }

    // 2. Thêm bài viết
    document.getElementById('addNewsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true; submitBtn.innerHTML = 'Đang lưu...';

        const formData = new FormData(this);
        fetch('../../backend/news/create.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Thêm thành công!');
                    this.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addNewsModal')).hide();
                    loadNews();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(err => alert('Lỗi kết nối'))
            .finally(() => { submitBtn.disabled = false; submitBtn.innerHTML = 'Đăng bài'; });
    });

    // 3. Xóa bài viết
    window.deleteNews = function(id, title) {
        event.stopPropagation();
        event.preventDefault();
        if(confirm(`Bạn có chắc muốn xóa bài: "${title}"?\nHành động này không thể hoàn tác.`)) {
            const formData = new FormData();
            formData.append('id', id);
            fetch('../../backend/news/delete.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.success) loadNews(searchInput.value);
                    else alert(data.message);
                })
                .catch(err => alert('Lỗi kết nối'));
        }
    };

    // Events
    searchInput.addEventListener('keypress', (e) => { if(e.key === 'Enter') loadNews(searchInput.value); });
    loadNews();
</script>
</body>
</html>