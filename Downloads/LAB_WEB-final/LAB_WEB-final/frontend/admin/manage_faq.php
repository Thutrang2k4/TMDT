<?php
session_start();
require_once __DIR__ . "/../../backend/auth/check_role.php";
require_once __DIR__ . "/../../backend/models/content_model.php";

requireAdmin();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 10;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    $faqs = searchFAQs($search, $page, $per_page);
    $total_faqs = countSearchFAQs($search);
} else {
    $faqs = getAllFAQsForAdmin($page, $per_page);
    $total_faqs = countTotalFAQs();
}

$total_pages = ceil($total_faqs / $per_page);

$success_msg = '';
$error_msg = '';

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added':
            $success_msg = 'Thêm câu hỏi thành công!';
            break;
        case 'updated':
            $success_msg = 'Cập nhật câu hỏi thành công!';
            break;
        case 'deleted':
            $success_msg = 'Xóa câu hỏi thành công!';
            break;
        case 'status_updated':
            $success_msg = 'Cập nhật trạng thái thành công!';
            break;
    }
}

if (isset($_GET['error'])) {
    $error_msg = 'Có lỗi xảy ra. Vui lòng thử lại.';
    if (isset($_SESSION['faq_errors'])) {
        $errors = $_SESSION['faq_errors'];
        unset($_SESSION['faq_errors']);
    }
}

$edit_faq = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_faq = getFAQById($edit_id);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý FAQ - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        textarea.form-control {
            padding: 12px 15px;
            line-height: 1.6;
        }
        input[type="text"].form-control,
        input[type="number"].form-control {
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
                        <h2 class="page-title">Quản lý câu hỏi FAQ</h2>
                    </div>
                    <div class="col-auto ms-auto">
                        <div class="btn-list">
                            <a href="../faq.php" class="btn btn-white" target="_blank">
                                <i class="bi bi-eye me-2"></i>Xem trang công khai
                            </a>
                            <button type="button" class="btn btn-primary" onclick="openAddModal()">
                                <i class="bi bi-plus-lg me-2"></i>Thêm câu hỏi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <!-- Alerts -->
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
                
                <!-- Search Box -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm câu hỏi..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Tìm
                            </button>
                            <?php if ($search): ?>
                                <a href="manage_faq.php" class="btn btn-secondary">Reset</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                
                <!-- FAQ Table -->
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Câu hỏi</th>
                                    <th>Danh mục</th>
                                    <th>Thứ tự</th>
                                    <th>Trạng thái</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($faqs)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Không có câu hỏi nào</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($faqs as $faq): ?>
                                        <tr>
                                            <td class="text-muted">#<?php echo $faq['id']; ?></td>
                                            <td>
                                                <div><strong><?php echo htmlspecialchars($faq['question']); ?></strong></div>
                                                <div class="text-muted small">
                                                    <?php echo htmlspecialchars(mb_substr($faq['answer'], 0, 100)); ?>...
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($faq['category']); ?></td>
                                            <td><?php echo $faq['display_order']; ?></td>
                                            <td>
                                                <?php if ($faq['is_active']): ?>
                                                    <span class="badge bg-green">Hiển thị</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Ẩn</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <button class="btn btn-sm btn-primary" onclick='editFAQ(<?php echo json_encode($faq); ?>)'>
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a href="../../backend/actions/content/process_faq.php?action=toggle&id=<?php echo $faq['id']; ?>" 
                                                       class="btn btn-sm btn-info" 
                                                       onclick="return confirm('Thay đổi trạng thái?')">
                                                        <i class="bi bi-eye<?php echo $faq['is_active'] ? '-slash' : ''; ?>"></i>
                                                    </a>
                                                    <a href="../../backend/actions/content/process_faq.php?action=delete&id=<?php echo $faq['id']; ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">Trang <?php echo $page; ?> / <?php echo $total_pages; ?></p>
                            <ul class="pagination m-0 ms-auto">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo ($page - 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo ($page + 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
    
    <!-- Modal Add/Edit FAQ -->
    <div id="faqModal" class="modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Thêm câu hỏi FAQ</h5>
                    <button type="button" class="btn-close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <form action="../../backend/actions/content/process_faq.php" method="POST" id="faqForm">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="faqId" value="">
                        
                        <div class="mb-3">
                            <label class="form-label" for="question">Câu hỏi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="question" name="question" required maxlength="500">
                            <small class="form-hint">Tối đa 500 ký tự</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="answer">Câu trả lời <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="answer" name="answer" rows="6" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="category">Danh mục <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="category" name="category" required maxlength="100" value="Chung">
                                <small class="form-hint">Ví dụ: Đặt tour, Thanh toán, Dịch vụ...</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="display_order">Thứ tự hiển thị</label>
                                <input type="number" class="form-control" id="display_order" name="display_order" value="0" min="0">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Hiển thị trên trang công khai</label>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Lưu
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        fetch("partials/sidebar.html").then(r => r.text()).then(t => document.getElementById("sidebar").innerHTML = t);
        fetch("partials/topbar.html").then(r => r.text()).then(t => document.getElementById("topbar").innerHTML = t);
        
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Thêm câu hỏi FAQ';
            document.getElementById('formAction').value = 'add';
            document.getElementById('faqId').value = '';
            document.getElementById('faqForm').reset();
            document.getElementById('is_active').checked = true;
            
            const modal = new bootstrap.Modal(document.getElementById('faqModal'));
            modal.show();
        }
        
        function editFAQ(faq) {
            document.getElementById('modalTitle').textContent = 'Sửa câu hỏi FAQ';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('faqId').value = faq.id;
            document.getElementById('question').value = faq.question;
            document.getElementById('answer').value = faq.answer;
            document.getElementById('category').value = faq.category;
            document.getElementById('display_order').value = faq.display_order;
            document.getElementById('is_active').checked = faq.is_active == 1;
            
            const modal = new bootstrap.Modal(document.getElementById('faqModal'));
            modal.show();
        }
        
        function closeModal() {
            const modalElement = document.getElementById('faqModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
        
        document.getElementById('faqForm').addEventListener('submit', function(e) {
            const question = document.getElementById('question').value.trim();
            const answer = document.getElementById('answer').value.trim();
            const category = document.getElementById('category').value.trim();
            
            if (!question || !answer || !category) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ các trường bắt buộc!');
                return false;
            }
            
            if (question.length > 500) {
                e.preventDefault();
                alert('Câu hỏi không được vượt quá 500 ký tự!');
                return false;
            }
            
            if (category.length > 100) {
                e.preventDefault();
                alert('Danh mục không được vượt quá 100 ký tự!');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>
