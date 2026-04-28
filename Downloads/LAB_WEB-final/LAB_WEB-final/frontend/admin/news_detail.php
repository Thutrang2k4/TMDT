<?php
// Kiểm tra quyền Admin
require_once __DIR__ . "/../../backend/auth/check_role.php";
// requireAdmin(); // Bỏ comment dòng này nếu bạn đã hiện thực check_role
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý bài viết - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .comment-avatar { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; }
        .comment-box { background-color: #f8f9fa; border-radius: 10px; padding: 15px; position: relative; }
        
        /* CSS cho nút xóa comment nằm góc phải */
        .btn-delete-cmt {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none; background: none;
            color: #dc3545; /* Màu đỏ */
            opacity: 0.6;
            transition: 0.2s;
            z-index: 10;
        }
        .btn-delete-cmt:hover { opacity: 1; transform: scale(1.1); cursor: pointer; }
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
                        <h2 class="page-title">Chi tiết bài viết</h2>
                    </div>
                    <div class="col-auto ms-auto">
                        <div class="btn-list">
                            <a href="news.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Quay lại
                            </a>
                            <button class="btn btn-danger" onclick="deleteCurrentPost()">
                                <i class="bi bi-trash-fill me-1"></i> Xóa bài viết
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
                    <p class="mt-2 text-muted">Đang tải dữ liệu...</p>
                </div>

                <div id="contentArea" style="display: none;">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div id="postData"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-chat-square-text me-2"></i>Quản lý Bình luận
                                <span class="badge bg-secondary ms-2" id="commentCount">0</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="commentsList"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Load Sidebar & Topbar (Admin Layout)
        fetch("partials/sidebar.html").then(r=>r.text()).then(t=>document.getElementById("sidebar").innerHTML=t);
        fetch("partials/topbar.html").then(r=>r.text()).then(t=>document.getElementById("topbar").innerHTML=t);

        const params = new URLSearchParams(window.location.search);
        const postId = params.get("id");
        
        if (!postId) { alert("Thiếu ID bài viết"); window.location.href = "news.php"; return; }

        // --- 1. TẢI CHI TIẾT BÀI VIẾT ---
        function loadPostDetail() {
            fetch(`../../backend/news/get_by_id.php?id=${postId}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message);
                        window.location.href = "news.php";
                        return;
                    }
                    
                    const n = data.news;
                    const dateStr = n.created_at ? new Date(n.created_at).toLocaleDateString('vi-VN') : 'Mới';

                    document.getElementById('postData').innerHTML = `
                        <h1 class="mb-3">${n.title}</h1>
                        <div class="d-flex align-items-center mb-4 text-muted">
                            <span class="badge bg-green-lt me-2">${n.category_name || 'Tin tức'}</span>
                            <i class="bi bi-clock me-1"></i> ${dateStr}
                            <span class="mx-2">|</span>
                            <span>Tác giả: Admin</span>
                        </div>
                        <div class="markdown" style="text-align: justify;">
                            ${n.content.replace(/\n/g, '<br>')}
                        </div>
                    `;
                    
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('contentArea').style.display = 'block';

                    // Tải comment sau khi có bài
                    loadComments();
                })
                .catch(err => alert("Lỗi kết nối: " + err.message));
        }

        // --- 2. TẢI BÌNH LUẬN ---
        window.loadComments = function() { // Gán vào window để gọi lại được sau khi xóa
            const list = document.getElementById("commentsList");
            list.innerHTML = '<div class="text-center py-3 text-muted"><div class="spinner-border spinner-border-sm"></div> Đang tải bình luận...</div>';

            fetch(`../../backend/news/get_comments.php?post_id=${postId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.comments.length > 0) {
                        document.getElementById('commentCount').innerText = data.comments.length;
                        list.innerHTML = '';
                        
                        data.comments.forEach(cmt => {
                            const user = cmt.full_name || 'Người dùng ẩn danh';
                            const time = new Date(cmt.created_at).toLocaleDateString('vi-VN');
                            const ratingStar = cmt.rating ? `<span class="text-warning ms-2 small">${cmt.rating} <i class="bi bi-star-fill"></i></span>` : '';
                            
                            // Render HTML bình luận + Nút Xóa
                            list.innerHTML += `
                                <div class="d-flex mb-3 align-items-start position-relative">
                                    <div class="flex-shrink-0">
                                        <span class="avatar avatar-rounded">
                                            <i class="bi bi-person-fill"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="comment-box">
                                            <button class="btn-delete-cmt" title="Xóa bình luận này" onclick="deleteComment(${cmt.id})">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>

                                            <div class="d-flex justify-content-between align-items-center mb-1 pe-4">
                                                <strong class="mb-0">${user} ${ratingStar}</strong>
                                            </div>
                                            <div class="text-muted small mb-2">${time}</div>
                                            <div style="white-space: pre-wrap;">${cmt.content}</div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        document.getElementById('commentCount').innerText = 0;
                        list.innerHTML = '<p class="text-muted text-center py-3">Chưa có bình luận nào.</p>';
                    }
                })
                .catch(err => {
                    list.innerHTML = '<p class="text-danger text-center">Không thể tải bình luận.</p>';
                });
        }

        // --- 3. XÓA BÌNH LUẬN ---
        window.deleteComment = function(commentId) {
            if(!confirm('Bạn có chắc chắn muốn xóa vĩnh viễn bình luận này không?')) return;

            const formData = new FormData();
            formData.append('id', commentId);

            // Gọi API xóa trong folder news
            fetch('../../backend/news/delete_comment.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Xóa thành công thì tải lại danh sách
                    loadComments();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(err => alert('Lỗi kết nối: ' + err.message));
        }

        // --- 4. XÓA BÀI VIẾT ---
        window.deleteCurrentPost = function() {
            if(!confirm('CẢNH BÁO: Hành động này sẽ xóa bài viết và TOÀN BỘ bình luận liên quan.\nBạn có chắc chắn muốn tiếp tục?')) return;

            const fd = new FormData(); 
            fd.append('id', postId);
            
            fetch('../../backend/news/delete_comment.php', { method: 'POST', body: fd })
                .then(r => r.json()).then(d => {
                    if(d.success) { 
                        alert('Đã xóa bài viết!'); 
                        window.location.href = "news.php"; 
                    } else { 
                        alert(d.message); 
                    }
                });
        }

        // Chạy hàm khởi tạo
        loadPostDetail();
    });
</script>
</body>
</html>