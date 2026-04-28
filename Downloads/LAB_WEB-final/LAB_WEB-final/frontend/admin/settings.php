<?php
session_start();
require_once __DIR__ . "/../../backend/auth/check_role.php";

// Kiểm tra quyền admin
requireAdmin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Thông tin công ty & Chi nhánh - Admin</title>

  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/admin.css">
  <style>
    /* Tăng padding cho input */
    input[type="text"].form-control {
      padding: 10px 15px;
    }
    
    /* Company info styling */
    .list-group-item {
      padding: 1rem 1.5rem;
    }
    
    .list-group-item .avatar {
      width: 3rem;
      height: 3rem;
      font-size: 1.5rem;
      background: var(--tblr-primary-lt);
      color: var(--tblr-primary);
    }
    
    /* Branch card styling */
    .branch-card {
      border-left: 3px solid var(--tblr-primary);
      transition: all 0.3s ease;
    }
    
    .branch-card:hover {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      transform: translateY(-2px);
    }
    
    .branch-card .card-title {
      color: var(--tblr-primary);
      font-size: 1.1rem;
    }
    
    .branch-info-item {
      display: flex;
      align-items-start;
      margin-bottom: 0.75rem;
    }
    
    .branch-info-item i {
      width: 24px;
      margin-right: 8px;
      font-size: 1rem;
    }
    
    /* Region filter buttons */
    .region-filter .btn {
      min-width: 120px;
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
            <h2 class="page-title">Cài đặt hệ thống</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="page-body">
      <div class="container-xl">
        <!-- THÔNG TIN CÔNG TY -->
        <section class="mb-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><i class="bi bi-building-fill me-2"></i>Thông tin công ty</h3>
            </div>
            <div class="card-body p-0" id="companyInfo"></div>
          </div>
        </section>

        <!-- QUẢN LÝ CHI NHÁNH -->
        <section>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><i class="bi bi-geo-alt-fill me-2"></i>Quản lý chi nhánh</h3>
            </div>
            <div class="card-body">
              <div class="region-filter d-flex flex-wrap gap-2 justify-content-center mb-4">
                <button class="btn btn-primary active" data-area="hcm">
                  <i class="bi bi-geo-fill me-1"></i> TP.HCM
                </button>
                <button class="btn btn-white" data-area="mien-bac">
                  <i class="bi bi-geo-fill me-1"></i> Hà Nội
                </button>
                <button class="btn btn-white" data-area="mien-trung">
                  <i class="bi bi-geo-fill me-1"></i> Đà Nẵng
                </button>
                <button class="btn btn-white" data-area="nuoc-ngoai">
                  <i class="bi bi-globe me-1"></i> Nước ngoài
                </button>
              </div>

              <div id="branch-list" class="mb-4"></div>

              <div class="text-center">
                <button id="btnAddBranch" class="btn btn-primary">
                  <i class="bi bi-plus-circle me-2"></i>Thêm chi nhánh mới
                </button>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>

<!-- Modal chỉnh sửa -->
<div class="modal modal-blur fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Chỉnh sửa thông tin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" id="editLabel"></label>
        <input type="text" class="form-control" id="editInput">
        <input type="hidden" id="editId">
        <input type="hidden" id="editField">
        <input type="hidden" id="editType">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" onclick="saveEdit()">
          <i class="bi bi-check-circle me-1"></i>Lưu thay đổi
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal thêm chi nhánh -->
<div class="modal modal-blur fade" id="addBranchModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm chi nhánh mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Tên chi nhánh <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="branchName" placeholder="Nhập tên chi nhánh...">
        </div>

        <div class="mb-3">
          <label class="form-label">Địa chỉ</label>
          <input type="text" class="form-control" id="branchAddress" placeholder="Nhập địa chỉ...">
        </div>

        <div class="mb-3">
          <label class="form-label">Hotline</label>
          <input type="text" class="form-control" id="branchPhone" placeholder="Nhập hotline...">
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="text" class="form-control" id="branchEmail" placeholder="Nhập email...">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-success" onclick="saveNewBranch()">
          <i class="bi bi-plus-circle me-1"></i>Thêm
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

  let editModalInstance = null;
  let addBranchModalInstance = null;

  // Load thông tin công ty
  async function loadCompany() {
    const res = await fetch("../../backend/actions/company/get_company.php");
    const [c] = await res.json();

    const fields = [
      { label: "Tên công ty", field: "name", value: c.name, icon: "building" },
      { label: "Logo", field: "logo", value: c.logo, isImage: true, icon: "image" },
      { label: "Website", field: "website", value: c.website, icon: "link-45deg" },
      { label: "Email hỗ trợ", field: "email_support", value: c.email_support, icon: "envelope" }
    ];

    document.getElementById("companyInfo").innerHTML = fields.map(f => `
      <div class="list-group-item">
        <div class="row align-items-center">
          <div class="col-auto">
            <span class="avatar"><i class="bi bi-${f.icon}-fill"></i></span>
          </div>
          <div class="col text-truncate">
            <div class="text-muted small">${f.label}</div>
            <div class="text-reset">
              ${f.isImage 
                ? (f.value ? `<img src="${f.value}" alt="Logo" class="img-fluid rounded border" style="max-height: 60px;">` : '<span class="text-muted fst-italic">Chưa có logo</span>')
                : (f.value || '<span class="text-muted fst-italic">Chưa có thông tin</span>')
              }
            </div>
          </div>
          <div class="col-auto">
            <button class="btn btn-sm btn-primary" onclick="openEdit('${c.id}', '${f.field}', '${(f.value||"").replace(/'/g, "\\'")}', '${f.label}', 'company')">
              <i class="bi bi-pencil"></i>
            </button>
          </div>
        </div>
      </div>
    `).join("");
  }

  let allBranches = [];
  async function loadBranches() {
    const res = await fetch("../../backend/actions/company/get_branches.php");
    allBranches = await res.json();
    filterBranches('hcm');
  }

  function filterBranches(region) {
    document.querySelectorAll("[data-area]").forEach(b => {
      b.classList.remove("active", "btn-primary");
      b.classList.add("btn-white");
    });
    const activeBtn = document.querySelector(`[data-area="${region}"]`);
    activeBtn.classList.remove("btn-white");
    activeBtn.classList.add("active", "btn-primary");

    const filtered = allBranches.filter(b => b.region === region);
    renderBranches(filtered);
  }

  function renderBranches(list) {
    const container = document.getElementById("branch-list");
    if (!list.length) {
      container.innerHTML = `
        <div class="empty">
          <div class="empty-icon">
            <i class="bi bi-inbox"></i>
          </div>
          <p class="empty-title">Chưa có chi nhánh</p>
          <p class="empty-subtitle text-muted">Chưa có chi nhánh nào trong khu vực này.</p>
        </div>
      `;
      return;
    }

    container.innerHTML = list.map((b, index) => `
      <div class="card branch-card mb-3">
        <div class="card-body">
          <div class="d-flex align-items-start mb-3">
            <div class="flex-grow-1">
              <h3 class="card-title mb-0">
                <i class="bi bi-building me-2"></i>${b.name || 'Chưa đặt tên'}
              </h3>
              <div class="text-muted small mt-1">Cơ sở ${index + 1}</div>
            </div>
            <button class="btn btn-sm btn-danger" onclick="deleteBranch(${b.id})" title="Xóa chi nhánh">
              <i class="bi bi-trash"></i>
            </button>
          </div>

          <div class="branch-info-item">
            <i class="bi bi-geo-alt-fill text-red"></i>
            <div class="flex-grow-1">
              <strong>Địa chỉ:</strong><br>
              ${b.address || '<span class="text-muted fst-italic">Chưa cập nhật</span>'}
            </div>
            <button class="btn btn-sm btn-icon btn-ghost-secondary" onclick="openEdit('${b.id}', 'address', '${(b.address||"").replace(/'/g, "\\'")}', 'Địa chỉ', 'branch')" title="Sửa địa chỉ">
              <i class="bi bi-pencil"></i>
            </button>
          </div>

          <div class="branch-info-item">
            <i class="bi bi-telephone-fill text-green"></i>
            <div class="flex-grow-1">
              <strong>Hotline:</strong><br>
              ${b.phone || '<span class="text-muted fst-italic">Chưa cập nhật</span>'}
            </div>
            <button class="btn btn-sm btn-icon btn-ghost-secondary" onclick="openEdit('${b.id}', 'phone', '${(b.phone||"").replace(/'/g, "\\'")}', 'Hotline', 'branch')" title="Sửa hotline">
              <i class="bi bi-pencil"></i>
            </button>
          </div>

          <div class="branch-info-item mb-0">
            <i class="bi bi-envelope-fill text-blue"></i>
            <div class="flex-grow-1">
              <strong>Email:</strong><br>
              ${b.email || '<span class="text-muted fst-italic">Chưa cập nhật</span>'}
            </div>
            <button class="btn btn-sm btn-icon btn-ghost-secondary" onclick="openEdit('${b.id}', 'email', '${(b.email||"").replace(/'/g, "\\'")}', 'Email', 'branch')" title="Sửa email">
              <i class="bi bi-pencil"></i>
            </button>
          </div>
        </div>
      </div>
    `).join("");
  }

  // Modal functions
  function openEdit(id, field, value, label, type) {
    document.getElementById("editId").value = id;
    document.getElementById("editField").value = field;
    document.getElementById("editInput").value = value;
    document.getElementById("editLabel").textContent = label;
    document.getElementById("editType").value = type;
    
    if (!editModalInstance) {
      editModalInstance = new bootstrap.Modal(document.getElementById('editModal'));
    }
    editModalInstance.show();
  }

  async function saveEdit() {
    const form = new FormData();
    form.append("id", document.getElementById("editId").value);
    form.append("field", document.getElementById("editField").value);
    form.append("value", document.getElementById("editInput").value);

    const url = document.getElementById("editType").value === "company"
      ? "../../backend/actions/company/update_company.php"
      : "../../backend/actions/company/update_branch.php";

    const res = await fetch(url, { method: "POST", body: form });
    const json = await res.json();

    if (!json.success) return alert("Lỗi: " + (json.message || "Không thể cập nhật"));

    if (editModalInstance) editModalInstance.hide();
    alert("Cập nhật thành công!");
    document.getElementById("editType").value === "company" ? loadCompany() : loadBranches();
  }

  async function saveNewBranch() {
    const name = document.getElementById("branchName").value.trim();
    if (!name) return alert("Vui lòng nhập tên chi nhánh!");

    const activeBtn = document.querySelector("[data-area].active");
    const region = activeBtn ? activeBtn.dataset.area : "";

    const form = new FormData();
    form.append("name", name);
    form.append("address", document.getElementById("branchAddress").value.trim());
    form.append("phone", document.getElementById("branchPhone").value.trim());
    form.append("email", document.getElementById("branchEmail").value.trim());
    form.append("region", region);

    const res = await fetch("../../backend/actions/company/add_branch.php", { method: "POST", body: form });
    const json = await res.json();

    alert(json.message || "Đã thêm chi nhánh mới!");
    if (addBranchModalInstance) addBranchModalInstance.hide();
    
    // Clear form
    ["branchName","branchAddress","branchPhone","branchEmail"].forEach(id => document.getElementById(id).value = "");
    
    loadBranches();
  }

  function deleteBranch(id) {
    if (!confirm("Bạn có chắc chắn muốn xóa chi nhánh này?")) return;

    const form = new FormData();
    form.append("id", id);

    fetch("../../backend/actions/company/delete_branch.php", { method: "POST", body: form })
      .then(r => r.json())
      .then(data => {
        alert(data.message);
        if (data.status === "success") loadBranches();
      })
      .catch(() => alert("Lỗi kết nối server!"));
  }

  // Events
  document.addEventListener("DOMContentLoaded", () => {
    loadCompany();
    loadBranches();

    document.querySelectorAll("[data-area]").forEach(btn => {
      btn.addEventListener("click", () => filterBranches(btn.dataset.area));
    });

    document.getElementById("btnAddBranch").addEventListener("click", () => {
      ["branchName","branchAddress","branchPhone","branchEmail"].forEach(id => document.getElementById(id).value = "");
      
      if (!addBranchModalInstance) {
        addBranchModalInstance = new bootstrap.Modal(document.getElementById('addBranchModal'));
      }
      addBranchModalInstance.show();
    });
  });
</script>
</body>
</html>
