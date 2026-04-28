<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>GoTour - Đặt Tour Du Lịch Giá Rẻ | Khám Phá Việt Nam và Thế Giới</title>
    <meta name="description" content="GoTour - Nền tảng đặt tour du lịch trực tuyến hàng đầu Việt Nam. Khám phá các tour trong nước và quốc tế với giá tốt nhất. Đặt tour nhanh chóng, an toàn và tiện lợi.">
    <meta name="keywords" content="du lịch, tour du lịch, đặt tour, tour giá rẻ, du lịch Việt Nam, tour nước ngoài, GoTour">
    <meta name="author" content="GoTour">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://gotour.vn/">
    <meta property="og:title" content="GoTour - Đặt Tour Du Lịch Giá Rẻ">
    <meta property="og:description" content="Khám phá các tour du lịch trong nước và quốc tế với giá tốt nhất. Đặt tour nhanh chóng, an toàn và tiện lợi.">
    <meta property="og:image" content="https://gotour.vn/assets/images/og-image.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://gotour.vn/">
    <meta property="twitter:title" content="GoTour - Đặt Tour Du Lịch Giá Rẻ">
    <meta property="twitter:description" content="Khám phá các tour du lịch trong nước và quốc tế với giá tốt nhất.">
    <meta property="twitter:image" content="https://gotour.vn/assets/images/twitter-image.jpg">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://gotour.vn/">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
    
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
    
    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TravelAgency",
      "name": "GoTour",
      "description": "Nền tảng đặt tour du lịch trực tuyến hàng đầu Việt Nam",
      "url": "https://gotour.vn",
      "logo": "https://gotour.vn/assets/images/logo.png",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+84-123-456-789",
        "contactType": "Customer Service",
        "areaServed": "VN",
        "availableLanguage": ["Vietnamese", "English"]
      },
      "sameAs": [
        "https://facebook.com/gotour",
        "https://twitter.com/gotour",
        "https://instagram.com/gotour"
      ]
    }
    </script>
</head>
<body>

    <!-- Header -->
    <div id="header"></div>

    <main>
        <!-- Banner Carousel -->
        <section class="banner">
            <div id="tourCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators" id="carouselIndicators"></div>
                <div class="carousel-inner" id="carouselContent"></div>

                <button class="carousel-control-prev" type="button" data-bs-target="#tourCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#tourCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </section>

        <div class="container">

            <!-- Why Choose Us -->
            <section class="why-choose-us text-center py-5">
                <h1 class="mb-5 fw-bold">Vì sao bạn nên chọn <span class="text-primary">GoTour</span></h1>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-lg border-0 p-3">
                            <div class="card-body text-center">
                                <div class="feature-icon mb-3"><i class="bi bi-wallet2 fs-2 text-primary"></i></div>
                                <h3 class="card-title text-primary fs-4">Giá tốt nhất cho bạn</h3>
                                <p class="card-text text-muted">Có nhiều mức giá đa dạng phù hợp với ngân sách và nhu cầu của bạn.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-lg border-0 p-3">
                            <div class="card-body text-center">
                                <div class="feature-icon mb-3"><i class="bi bi-calendar-check fs-2 text-primary"></i></div>
                                <h3 class="card-title text-primary fs-4">Booking dễ dàng</h3>
                                <p class="card-text text-muted">Các bước booking và chăm sóc khách hàng nhanh chóng và thuận tiện.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-lg border-0 p-3">
                            <div class="card-body text-center">
                                <div class="feature-icon mb-3"><i class="bi bi-globe fs-2 text-primary"></i></div>
                                <h3 class="card-title text-primary fs-4">Tour du lịch tối ưu</h3>
                                <p class="card-text text-muted">Đa dạng các loại hình tour du lịch với nhiều mức giá khác nhau.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="section-divider"></div>
            <!-- Booking Steps -->
            <section class="booking-together text-center py-5">
                <h1 class="mb-5 fw-bold">Booking cùng <span class="text-primary">GoTour</span></h1>
                <p class="lead mb-5 text-muted">Chỉ với 3 bước đơn giản và dễ dàng có ngay trải nghiệm tuyệt vời!</p>
                <div class="row g-4">
                    <!-- Step 1 -->
                    <div class="col-md-4">
                        <div class="step h-100">
                            <div class="icon-circle mb-3 bg-warning text-white mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                <span class="display-6 fw-bold">1</span>
                            </div>
                            <h4 class="fw-bold mt-2">Tìm nơi muốn đến</h4>
                            <p>Bất cứ nơi đâu bạn muốn đến, chúng tôi có tất cả những gì bạn cần.</p>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="col-md-4">
                        <div class="step h-100">
                            <div class="icon-circle mb-3 bg-warning text-white mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                <span class="display-6 fw-bold">2</span>
                            </div>
                            <h4 class="fw-bold mt-2">Đặt vé</h4>
                            <p>GoTour sẽ hỗ trợ bạn đặt vé trực tiếp nhanh chóng và thuận tiện.</p>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="col-md-4">
                        <div class="step h-100">
                            <div class="icon-circle mb-3 bg-warning text-white mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                <span class="display-6 fw-bold">3</span>
                            </div>
                            <h4 class="fw-bold mt-2">Thanh toán</h4>
                            <p>Hoàn thành bước thanh toán và sẵn sàng cho chuyến đi ngay thôi.</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <div class="section-divider"></div>
            <!-- Featured Tours -->
            <section class="featured-tours py-5">
                <h1 class="text-center mb-5 fw-bold">Tour <span class="text-primary">Nổi bật</span></h1>
                <
                <div class="row g-4" id="featuredTours"></div>
                <div class="text-center mt-5">
                    <a class="btn btn-primary btn-lg shadow" href="pricing.php">Xem tất cả tour</a>
                </div>
            </section>
            <div class="section-divider"></div>
            <!-- Latest Articles -->
            <section class="blog-news py-5">
                <h1 class="text-center mb-5 fw-bold">Cập nhật thông tin mới nhất <span class="text-primary">Du lịch</span></h2>
                
                <div class="row g-4 justify-content-center" id="latestArticles"></div>

                <div class="text-center mt-5">
                    <a class="btn btn-primary btn-lg shadow" href="news.php">Xem tất cả các thông báo</a>
                </div>
            </section>
            <div class="section-divider"></div>
            <!-- Newsletter -->
            <section class="newsletter text-center py-5">
                <img src="https://i.imgur.com/Dh7U4bp.png" width="200" class="mb-4">
                <h2 class="mb-4 fw-bold">Bạn có muốn nhận tin khuyến mãi hot?</h2>
                <p class="lead mb-5">Đăng ký ngay để không bỏ lỡ ưu đãi tốt nhất!</p>
                <div class="mx-auto" style="max-width: 500px;">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter email" aria-label="Email" aria-describedby="subscribeButton">
                        <button class="btn btn-success border-rad" type="button" id="subscribeButton">Subscribe</button>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Footer -->
    <div id="footer"></div>

    <!-- Bootstrap JS & Custom JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/index.js"></script>
    <script src="logo.js"></script>
</body>
</html>
