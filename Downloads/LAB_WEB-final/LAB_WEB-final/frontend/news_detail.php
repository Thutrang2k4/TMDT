<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Meta Tags - Will be updated dynamically -->
    <title>Chi tiết tin tức - GoTour</title>
    <meta name="description" content="Đọc tin tức và cập nhật mới nhất về du lịch từ GoTour">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph - Will be updated dynamically -->
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="GoTour">
    
    <!-- Canonical -->
    <link rel="canonical" href="">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .news-detail-page {
            min-height: 100%;
            margin-bottom: 10% !important; /* Tránh footer che nội dung */
        }
        .news-container { max-width: 800px; margin: 0 auto; padding-bottom: 100px; }
        .comment-avatar { 
            width: 45px; height: 45px; 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            background-color: #e9ecef;
            color: #495057;
        }
        .comment-box { background-color: #f8f9fa; border-radius: 12px; padding: 15px; width: 100%; }
        .news-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 15px 0; }
    </style>
</head>
<body>

<?php include 'partials/header.php'; ?>

<main class="container py-5 news-detail-page">
    <div class="news-container">
        
        <div id="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Đang tải bài viết...</p>
        </div>

        <div id="newsContent" style="display: none;">
            </div>

        <div id="commentSection" class="mt-5 pt-4 border-top" style="display: none;">
            <h4 class="mb-4 fw-bold"><i class="bi bi-chat-dots me-2"></i>Bình luận & Đánh giá</h4>
            
            <div class="card mb-4 border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="card-title fw-bold mb-3">Để lại ý kiến của bạn</h6>
                    <form id="commentForm">
                        <input type="hidden" id="postIdInput" name="post_id">
                        
                        <input type="hidden" name="user_id" value="2"> 
                        
                        <div class="mb-3">
                            <textarea name="content" class="form-control" rows="3" placeholder="Chia sẻ suy nghĩ của bạn..." required></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="me-2 fw-semibold">Đánh giá:</span>
                                <select name="rating" class="form-select form-select-sm w-auto border-warning text-warning fw-bold">
                                    <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                                    <option value="4">⭐⭐⭐⭐ (Tốt)</option>
                                    <option value="3">⭐⭐⭐ (Bình thường)</option>
                                    <option value="2">⭐⭐ (Tệ)</option>
                                    <option value="1">⭐ (Rất tệ)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-send-fill me-1"></i> Gửi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="commentsList">
                </div>
        </div>

    </div>
</main>

<?php include 'partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Helper function to update meta tags
    function updateMetaTag(property, content) {
        let meta = document.querySelector(`meta[property="${property}"]`);
        if (meta) {
            meta.setAttribute('content', content);
        } else {
            meta = document.createElement('meta');
            meta.setAttribute('property', property);
            meta.setAttribute('content', content);
            document.head.appendChild(meta);
        }
    }
    
    // Lấy ID từ URL
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");
    
    const elements = {
        newsContent: document.getElementById("newsContent"),
        commentSection: document.getElementById("commentSection"),
        commentsList: document.getElementById("commentsList"),
        loading: document.getElementById("loading"),
        postIdInput: document.getElementById("postIdInput"),
        commentForm: document.getElementById("commentForm")
    };

    if (!id) {
        elements.loading.style.display = 'none';
        elements.newsContent.style.display = 'block';
        elements.newsContent.innerHTML = `<div class="alert alert-warning text-center">Không tìm thấy ID bài viết. <a href="news.php">Quay lại danh sách</a></div>`;
        return;
    }

    // Gán ID vào hidden field của form comment
    elements.postIdInput.value = id;

    // --- 1. HÀM TẢI CHI TIẾT BÀI VIẾT ---
    function fetchNewsDetail() {
        // Gọi API get_by_id (từ frontend/ lên backend/)
        fetch(`../backend/news/get_by_id.php?id=${id}`)
            .then(res => res.json())
            .then(data => {
                elements.loading.style.display = 'none';
                elements.newsContent.style.display = 'block';

                if (!data.success) {
                    elements.newsContent.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    return;
                }

                const n = data.news;
                const dateStr = n.created_at ? new Date(n.created_at).toLocaleDateString('vi-VN') : 'Vừa xong';
                // Xử lý xuống dòng cho nội dung
                const formattedContent = n.content.replace(/\n/g, '<br>');
                
                // Update SEO meta tags dynamically
                document.title = `${n.title} - GoTour`;
                
                // Update or create meta description
                let metaDesc = document.querySelector('meta[name="description"]');
                const description = n.content.substring(0, 155) + '...';
                if (metaDesc) {
                    metaDesc.setAttribute('content', description);
                } else {
                    metaDesc = document.createElement('meta');
                    metaDesc.name = 'description';
                    metaDesc.content = description;
                    document.head.appendChild(metaDesc);
                }
                
                // Update Open Graph tags
                updateMetaTag('og:title', n.title);
                updateMetaTag('og:description', description);
                updateMetaTag('og:url', window.location.href);
                
                // Update canonical
                let canonical = document.querySelector('link[rel="canonical"]');
                if (canonical) {
                    canonical.href = window.location.href;
                }

                // Render bài viết
                elements.newsContent.innerHTML = `
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="news.php" class="text-decoration-none">Tin tức</a></li>
                            <li class="breadcrumb-item active" aria-current="page">${n.title}</li>
                        </ol>
                    </nav>
                    
                    <h1 class="fw-bold display-6 mb-3">${n.title}</h1>
                    
                    <div class="d-flex align-items-center text-muted mb-4 pb-3 border-bottom">
                        <span class="badge bg-success me-2">${n.category_name || 'Tổng hợp'}</span>
                        <small><i class="bi bi-clock me-1"></i> ${dateStr}</small>
                        <span class="mx-2">•</span>
                        <small><i class="bi bi-person me-1"></i> Admin</small>
                    </div>
                    
                    <div class="news-content fs-5 lh-lg text-justify text-break">
                        ${formattedContent}
                    </div>
                    
                    <div class="mt-5 text-end">
                        <a href="news.php" class="btn btn-outline-secondary btn-sm">← Quay lại danh sách</a>
                    </div>
                `;

                // Sau khi load bài viết xong thì load comment
                fetchComments(); 
                elements.commentSection.style.display = 'block';
            })
            .catch(err => {
                elements.loading.style.display = 'none';
                elements.newsContent.innerHTML = `<div class="alert alert-danger">Lỗi kết nối: ${err.message}</div>`;
            });
    }

    // --- 2. HÀM TẢI BÌNH LUẬN ---
    function fetchComments() {
        elements.commentsList.innerHTML = '<div class="text-center py-2"><span class="spinner-border spinner-border-sm text-secondary"></span></div>';
        
        fetch(`../backend/news/get_comments.php?post_id=${id}`)
            .then(res => res.json())
            .then(data => {
                elements.commentsList.innerHTML = ''; // Clear loading
                
                if (!data.success || data.comments.length === 0) {
                    elements.commentsList.innerHTML = '<p class="text-muted text-center fst-italic py-3">Chưa có bình luận nào. Hãy là người đầu tiên!</p>';
                    return;
                }

                data.comments.forEach(cmt => {
                    const userName = cmt.full_name || 'Người dùng ẩn danh';
                    const time = new Date(cmt.created_at).toLocaleDateString('vi-VN');
                    // Tạo sao đánh giá
                    const stars = Array(parseInt(cmt.rating || 5)).fill('<i class="bi bi-star-fill text-warning" style="font-size:0.8rem"></i>').join('');

                    const html = `
                        <div class="d-flex mb-3 fade-in">
                            <div class="flex-shrink-0">
                                <div class="comment-avatar">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="comment-box">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <span class="fw-bold me-2">${userName}</span>
                                            <span class="me-2">${stars}</span>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.85rem;">${time}</small>
                                    </div>
                                    <p class="mb-0 text-dark" style="white-space: pre-wrap;">${cmt.content}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    elements.commentsList.insertAdjacentHTML('beforeend', html);
                });
            })
            .catch(err => {
                elements.commentsList.innerHTML = '<p class="text-danger text-center">Không thể tải bình luận.</p>';
            });
    }

    // --- 3. XỬ LÝ GỬI FORM ---
    elements.commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Disable nút để tránh spam
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang gửi...';

        const formData = new FormData(this);

        fetch('../backend/news/add_comment.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Reset form và tải lại comment
                this.reset();
                // Gán lại ID vì reset() sẽ xóa hidden input
                elements.postIdInput.value = id; 
                fetchComments();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(err => alert('Lỗi kết nối: ' + err.message))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Chạy hàm khởi tạo
    fetchNewsDetail();
});
</script>
<script src="assets/js/main.js"></script>
<script src="logo.js"></script>
</body>
</html>