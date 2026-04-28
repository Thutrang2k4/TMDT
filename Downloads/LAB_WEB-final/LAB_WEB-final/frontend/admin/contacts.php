<?php
require_once __DIR__ . "/../../backend/auth/check_role.php";
requireAdmin();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản lý Liên hệ - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/admin.css">
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
            <h2 class="page-title">Liên hệ từ khách hàng</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="page-body">
      <div class="container-xl">
        <!-- Search & Filter -->
        <div class="card mb-3">
          <div class="card-body">
            <div class="row g-2">
              <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên, email, tiêu đề...">
              </div>
              <div class="col-md-3">
                <select id="statusFilter" class="form-select">
                  <option value="">Tất cả trạng thái</option>
                  <option value="new">Chưa xử lý</option>
                  <option value="read">Đang xử lý</option>
                  <option value="replied">Đã xử lý</option>
                </select>
              </div>
              <div class="col-md-3">
                <button class="btn btn-primary w-100" onclick="loadContacts(1)">
                  <i class="bi bi-search me-1"></i> Tìm kiếm
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="card">
          <div class="table-responsive">
            <table class="table table-vcenter card-table table-striped" id="contactTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Họ tên</th>
                  <th>Email</th>
                  <th>Tiêu đề</th>
                  <th>Trạng thái</th>
                  <th class="w-1"></th>
                </tr>
              </thead>
              <tbody id="contactTableBody"></tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="card-footer d-flex align-items-center" id="paginationFooter" style="display: none !important;">
            <div class="text-muted">
              Hiển thị <strong id="showingStart">0</strong> - <strong id="showingEnd">0</strong> 
              trong tổng số <strong id="totalContacts">0</strong> liên hệ
            </div>
            <ul class="pagination m-0 ms-auto" id="pagination"></ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL CHI TIẾT -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chi tiết liên hệ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label text-muted">ID</label>
            <p class="mb-0 fw-bold" id="d_id"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted">Ngày gửi</label>
            <p class="mb-0" id="d_created"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted">Họ tên</label>
            <p class="mb-0" id="d_name"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted">Email</label>
            <p class="mb-0"><a id="d_email" href="mailto:" class="text-primary"></a></p>
          </div>
          <div class="col-12">
            <label class="form-label text-muted">Tiêu đề</label>
            <p class="mb-0" id="d_subject"></p>
          </div>
          <div class="col-12">
            <label class="form-label text-muted">Nội dung</label>
            <div id="d_message" class="p-3 bg-light rounded border-start border-primary border-4" style="white-space: pre-line; max-height: 250px; overflow-y: auto;"></div>
          </div>
          <div class="col-12">
            <label class="form-label text-muted">Trạng thái</label>
            <div class="d-flex align-items-center gap-2">
              <span class="badge" id="statusBadge"></span>
              <small class="text-muted">Xử lý bởi: <strong id="handledByName">Chưa có</strong></small>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning" onclick="markProcessing()">
          <i class="bi bi-clock-history me-1"></i> Đang xử lý
        </button>
        <button class="btn btn-success" onclick="markHandled()">
          <i class="bi bi-check-lg me-1"></i> Đã xử lý
        </button>
        <button class="btn btn-danger" onclick="deleteContact(currentID)">
          <i class="bi bi-trash me-1"></i> Xóa
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Load sidebar & topbar
    fetch("partials/sidebar.html").then(r => r.text()).then(t => document.getElementById("sidebar").innerHTML = t);
    fetch("partials/topbar.html").then(r => r.text()).then(t => document.getElementById("topbar").innerHTML = t);

    let currentID = null;
    let modalInstance = null;
    let allContacts = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    async function loadContacts(page = 1) {
      try {
        const searchKeyword = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        
        const res = await fetch("../../backend/actions/contacts/get_contacts.php");
        const data = await res.json();
        
        // Lọc contacts
        allContacts = data.filter(c => {
          const matchSearch = !searchKeyword || 
            c.name.toLowerCase().includes(searchKeyword) ||
            c.email.toLowerCase().includes(searchKeyword) ||
            (c.subject && c.subject.toLowerCase().includes(searchKeyword));
          
          const matchStatus = !statusFilter || c.status === statusFilter;
          
          return matchSearch && matchStatus;
        });

        const tbody = document.getElementById("contactTableBody");
        
        if (allContacts.length === 0) {
          tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">Không tìm thấy liên hệ nào</td></tr>`;
          document.getElementById('paginationFooter').style.display = 'none';
          return;
        }
        
        // Pagination
        currentPage = page;
        const totalPages = Math.ceil(allContacts.length / itemsPerPage);
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const contactsToShow = allContacts.slice(startIndex, endIndex);
        
        // Update info
        document.getElementById('showingStart').textContent = startIndex + 1;
        document.getElementById('showingEnd').textContent = Math.min(endIndex, allContacts.length);
        document.getElementById('totalContacts').textContent = allContacts.length;
        document.getElementById('paginationFooter').style.display = 'flex';

        tbody.innerHTML = contactsToShow.map(c => {
          const statusMap = {
            'replied': { text: 'Đã xử lý', class: 'bg-green' },
            'read': { text: 'Đang xử lý', class: 'bg-yellow' },
            'new': { text: 'Chưa xử lý', class: 'bg-red' }
          };
          const status = statusMap[c.status] || statusMap['new'];
          
          return `
          <tr>
            <td class="text-muted"><strong>#${c.id}</strong></td>
            <td>${c.name}</td>
            <td>${c.email}</td>
            <td>${c.subject || '<i class="text-muted">Không có tiêu đề</i>'}</td>
            <td>
              <span class="badge ${status.class}">${status.text}</span>
            </td>
            <td>
              <button class="btn btn-sm btn-primary" onclick='showDetail(${JSON.stringify(c)})'>
                <i class="bi bi-eye"></i>
              </button>
            </td>
          </tr>
        `}).join("");
        
        // Render pagination
        renderPagination(totalPages);

      } catch (err) {
        alert("Lỗi tải dữ liệu: " + err.message);
      }
    }
    
    function renderPagination(totalPages) {
      const pagination = document.getElementById('pagination');
      pagination.innerHTML = '';
      
      if (totalPages <= 1) return;
      
      // Previous button
      const prevLi = document.createElement('li');
      prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
      prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadContacts(${currentPage - 1})">‹</a>`;
      pagination.appendChild(prevLi);
      
      // First page
      if (currentPage > 3) {
        const firstLi = document.createElement('li');
        firstLi.className = 'page-item';
        firstLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadContacts(1)">1</a>`;
        pagination.appendChild(firstLi);
        
        const dotsLi = document.createElement('li');
        dotsLi.className = 'page-item disabled';
        dotsLi.innerHTML = `<span class="page-link">...</span>`;
        pagination.appendChild(dotsLi);
      }
      
      // Page numbers
      const startPage = Math.max(1, currentPage - 2);
      const endPage = Math.min(totalPages, currentPage + 2);
      
      for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadContacts(${i})">${i}</a>`;
        pagination.appendChild(li);
      }
      
      // Last page
      if (currentPage < totalPages - 2) {
        const dotsLi = document.createElement('li');
        dotsLi.className = 'page-item disabled';
        dotsLi.innerHTML = `<span class="page-link">...</span>`;
        pagination.appendChild(dotsLi);
        
        const lastLi = document.createElement('li');
        lastLi.className = 'page-item';
        lastLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadContacts(${totalPages})">${totalPages}</a>`;
        pagination.appendChild(lastLi);
      }
      
      // Next button
      const nextLi = document.createElement('li');
      nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
      nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="loadContacts(${currentPage + 1})">›</a>`;
      pagination.appendChild(nextLi);
    }

    async function showDetail(c) {
      currentID = c.id;

      document.getElementById("d_id").textContent = '#' + c.id;
      document.getElementById("d_name").textContent = c.name;
      document.getElementById("d_email").textContent = c.email;
      document.getElementById("d_email").href = "mailto:" + c.email;
      document.getElementById("d_subject").textContent = c.subject || "(không có tiêu đề)";
      document.getElementById("d_created").textContent = new Date(c.created_at).toLocaleString('vi-VN');
      document.getElementById("d_message").textContent = c.message || "(trống)";

      // Xử lý trạng thái
      let handledName = "Chưa có";
      if (c.handled_by) {
        try {
          const r = await fetch(`../../backend/actions/contacts/get_name.php?id=${c.handled_by}`);
          const j = await r.json();
          if (j.name) handledName = j.name;
        } catch(e) {}
      }

      const statusMap = {
        'replied': { text: 'ĐÃ XỬ LÝ', class: 'bg-green' },
        'read': { text: 'ĐANG XỬ LÝ', class: 'bg-yellow' },
        'new': { text: 'CHƯA XỬ LÝ', class: 'bg-red' }
      };
      const status = statusMap[c.status] || statusMap['new'];

      const badge = document.getElementById("statusBadge");
      badge.textContent = status.text;
      badge.className = 'badge ' + status.class;
      document.getElementById("handledByName").textContent = handledName;

      modalInstance = new bootstrap.Modal(document.getElementById('detailModal'));
      modalInstance.show();
    }

    function closeDetail() {
      if (modalInstance) {
        modalInstance.hide();
      }
    }

    // Các hàm hành động
    async function markProcessing() {
      if (!confirm("Chuyển thành Đang xử lý?")) return;
      await fetch(`../../backend/actions/contacts/update_status.php?id=${currentID}`);
      alert("Đã cập nhật!");
      closeDetail();
      loadContacts();
    }

    async function markHandled() {
      if (!confirm("Đánh dấu đã xử lý xong?")) return;
      await fetch(`../../backend/actions/contacts/mark_handled.php?id=${currentID}`);
      alert("Đã đánh dấu hoàn thành!");
      closeDetail();
      loadContacts();
    }

    async function deleteContact(id) {
      if (!confirm("Xóa vĩnh viễn tin nhắn này? Không phục hồi được đâu nhé!")) return;
      const res = await fetch(`../../backend/actions/contacts/delete_contact.php?id=${id}`);
      const json = await res.json();
      if (json.success) {
        alert("Đã xóa thành công!");
        closeDetail();
        loadContacts();
      } else {
        alert("Lỗi: " + (json.message || "Unknown"));
      }
    }

    // Khởi chạy
    loadContacts(1);
    
    // Event listeners
    document.getElementById('searchInput').addEventListener('keypress', (e) => {
      if (e.key === 'Enter') loadContacts(1);
    });
  </script>
</body>
</html>
