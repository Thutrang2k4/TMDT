<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  
  <!-- SEO Meta Tags -->
  <title>Liên Hệ - GoTour | Hỗ Trợ 24/7 Đặt Tour Du Lịch</title>
  <meta name="description" content="Liên hệ với GoTour qua hotline, email hoặc gửi tin nhắn trực tuyến. Đội ngũ tư vấn viên chuyên nghiệp sẵn sàng hỗ trợ bạn 24/7 về các tour du lịch.">
  <meta name="keywords" content="liên hệ GoTour, hotline du lịch, tư vấn tour, hỗ trợ khách hàng, đặt tour">
  <meta name="robots" content="index, follow">
  
  <!-- Open Graph -->
  <meta property="og:title" content="Liên Hệ - GoTour">
  <meta property="og:description" content="Liên hệ với GoTour để được tư vấn và hỗ trợ về các tour du lịch trong nước và quốc tế.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://gotour.vn/contact.php">
  
  <!-- Canonical -->
  <link rel="canonical" href="https://gotour.vn/contact.php">
  
  <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/contact.css">
  
  <!-- Structured Data -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ContactPage",
    "name": "Liên Hệ GoTour",
    "description": "Trang liên hệ của GoTour - Nền tảng đặt tour du lịch",
    "url": "https://gotour.vn/contact.php"
  }
  </script>

</head>

<body>

  <div id="header"></div>

  <main class="contact-container">
    <div class="contact-header mb-3 mt-3 text-center">
      <h1 class="display-5 fw-bold text-dark">Liên hệ với <span class="text-primary">GoTour</span></h1>
      <p class="lead text-muted mb-4">Gửi thông tin hoặc liên hệ hotline các chi nhánh bên dưới để được hỗ trợ nhanh nhất.</p>
    </div>
    <div class="contact-c">
      <div class="contact-form">
        <form id="contactForm">
          <div class="form-group">
            <label class="form-label">Họ tên</label>
            <input type="text" name="name" id="name" class="form-control" required>
            <div id="nameError" class="text-danger small"></div>
          </div>

          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
            <div id="emailError" class="text-danger small"></div>
          </div>

          <div class="form-group">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="subject" class="form-control">
          </div>

          <div class="form-group">
            <label class="form-label">Nội dung</label>
            <textarea name="message" class="form-control" rows="4"></textarea>
          </div>

          <button class="btn btn-primary w-100">Gửi ngay</button>
        </form>
      </div>

      <!-- ========== CỘT CHI NHÁNH ========== -->
      <div class="branch-column">
        <div class="branch-filter">
          <button class="filter-btn active" data-area="hcm">TP.Hồ Chí Minh</button>
          <button class="filter-btn" data-area="mien-bac">Tp. Hà Nội</button>
          <button class="filter-btn" data-area="mien-trung">Đà Nẵng</button>
          <button class="filter-btn" data-area="nuoc-ngoai">Nước ngoài</button>
        </div>
        <div id="branch-list"></div>
      </div>

    </div>
  </main>

  <div id="footer"></div>

  <script>
  let allBranches = [];
  async function loadBranches() {
  const res = await fetch("../backend/actions/company/get_branches.php");
  const data = await res.json();
  allBranches = data;
  filterBranches('hcm');

  // thêm event listener cho các button
  document.querySelectorAll(".filter-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const area = btn.getAttribute("data-area");
      filterBranches(area);
    });
  });
}


  function filterBranches(region) {
    document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
    document.querySelector(`[data-area="${region}"]`).classList.add("active");

    const filtered = allBranches.filter(b => b.region === region);
    renderBranches(filtered);
  }

  function renderBranches(list) {
    const container = document.getElementById("branch-list");
    if (!list.length) {
      container.innerHTML = `<p style="text-align:center; color:#999; padding:3rem 0;">Chưa có chi nhánh khu vực này.</p>`;
      return;
    }

    container.innerHTML = list.map(b => `
      <div class="branch-card">
        <h5>${b.name || 'Chưa đặt tên'}</h5>
        <p><strong>Địa chỉ:</strong> ${b.address || '<i>Chưa cập nhật</i>'}</p>
        <p><strong>Hotline:</strong> ${b.phone || '<i>Chưa cập nhật</i>'}</p>
        <p><strong>Email:</strong> ${b.email || '<i>Chưa cập nhật</i>'}</p>

        
      </div>
    `).join("");
  }
  loadBranches();


    // ================= FORM VALIDATION + AJAX =================
    const form = document.getElementById('contactForm');

    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    

    const nameError = document.getElementById('nameError');
    const emailError = document.getElementById('emailError');
    
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      nameError.textContent = '';
      emailError.textContent = '';
      

      let valid = true;

      // Validate name
      const nameValue = nameInput.value.trim();
      if (nameValue.length < 3 || nameValue.split(" ").length < 2) {
        nameError.textContent = "Vui lòng nhập họ tên đầy đủ.";
        valid = false;
      }

      // Validate email
      const emailValue = emailInput.value.trim();
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(emailValue)) {
        emailError.textContent = "Email không hợp lệ.";
        valid = false;
      }

      

      if (!valid) return;

      const formData = new FormData(form);

      try {
        const res = await fetch('../backend/actions/contacts/admin_contact.php', {
          method: 'POST',
          body: formData
        });

        const data = await res.json();

        if (data.success) {
          alert("Gửi liên hệ thành công!");
          form.reset();
        } else {
          alert("Lỗi: " + data.message);
        }
      } catch (err) {
        alert("Lỗi kết nối: " + err.message);
      }
    });
  </script>

  <script src="assets/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="logo.js"></script>

</body>

</html>
