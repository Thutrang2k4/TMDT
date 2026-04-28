async function loadToursCarousel() {
    try {
        const res = await fetch("../backend/actions/tour/get_tour.php");
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || "Lỗi tải slider");

        const inner = document.getElementById("carouselContent");
        const indicators = document.getElementById("carouselIndicators");

        if (data.length === 0) return;

        inner.innerHTML = data.map((t, i) => `
           <div class="carousel-item ${i === 0 ? "active" : ""}">
     <a href="product-detail.php?slug=${t.slug}">
        <img src="${t.image}" class="d-block w-100" alt="${t.title}">
    </a>
    <div class="carousel-caption d-none d-md-block">
        <!-- Subtitle có thể là location hoặc tour đi đâu -->
        <h5 class="tour-subtitle text-primary">Ưu đãi ${t.location || ''}</h5>

        <!-- Title bỏ 3N2Đ -->
        <h1 class="tour-title">
            ${t.title.replace(/\s*\d+N\d+Đ/g, '')}
        </h1>

        <!-- Giá hiển thị -->
        <p class="tour-price">Chỉ còn <span>${t.price} đ</span></p>
    </div>
</div>

        `).join("");

        indicators.innerHTML = data.map((_, i) => `
            <button type="button" 
                data-bs-target="#tourCarousel" 
                data-bs-slide-to="${i}" 
                class="${i === 0 ? "active" : ""}">
            </button>
        `).join("");

    } catch (err) {
        console.error("Lỗi slider:", err);
    }
}

loadToursCarousel(); 



async function loadFeaturedTours() {
    try {
        const res = await fetch("../backend/actions/tour/get_tour.php?limit=3"); // lấy 3 tour đầu
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || "Lỗi tải tour nổi bật");

        const container = document.getElementById("featuredTours"); // div row g-4
        if (!container || data.length === 0) return;

        container.innerHTML = data.map(t => `
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow border-0">
                    <img src="${t.image}" class="card-img-top" alt="${t.title}">
                    <div class="card-body">
                        <h3 class="card-title fs-5 fw-bold">${t.title}</h3>
                        <p class="card-text mb-1"><small class="text-muted">Khởi hành: ${t.start_location}</small></p>
                        <p class="card-text mb-1"><small class="text-muted">Thời gian: ${t.duration}</small></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h4 class="text-danger fw-bold m-0">${Number(t.price).toLocaleString()}₫</h4>
                            <a href="product-detail.php?slug=${t.slug}" class="btn btn-outline-primary">Chi tiết</a>
                        </div>
                    </div>
                </div>
            </div>
        `).join("");

    } catch (err) {
        console.error("Lỗi tải tour nổi bật:", err);
    }
}

// Gọi hàm sau khi load DOM
loadFeaturedTours();

async function loadLatestArticles() {
    try {
        const res = await fetch("../backend/actions/company/get_post.php?limit=3");
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || "Lỗi tải bài viết");

        const container = document.getElementById("latestArticles");
        if (!container || data.length === 0) return;

        container.innerHTML = data.map(a => `
            <div class="col-md-6 col-lg-4">
              <a href="news_detail.php?id=${a.id}" class="card h-100 shadow border-0 text-decoration-none">
                <div class="card h-100 shadow border-0">
                    <div class="card-body">
                        <h3 class="card-title fs-5 fw-bold">${a.title}</h3>
                        <p class="card-text text-muted">${a.summary}</p>
                    </div>
                </div>
              </a>
            </div>
            
        `).join("");

    } catch (err) {
        console.error("Lỗi tải bài viết:", err);
    }
}

loadLatestArticles();
