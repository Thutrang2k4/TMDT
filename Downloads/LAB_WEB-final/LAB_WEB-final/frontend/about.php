<?php
require_once __DIR__ . '/../backend/models/content_model.php';

$about = getAboutContent();

if (!$about) {
    $about = array(
        'title' => 'Về GoTour',
        'content' => 'Chào mừng đến với GoTour',
        'mission' => 'Sứ mệnh của chúng tôi',
        'vision' => 'Tầm nhìn của chúng tôi',
        'image_url' => 'assets/images/about-banner.jpg'
    );
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars(mb_substr(strip_tags($about['content']), 0, 160)); ?>">
    <meta name="keywords" content="GoTour, du lịch, giới thiệu, về chúng tôi">
    <title><?php echo htmlspecialchars($about['title']); ?> - GoTour</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS custom đặt sau Bootstrap để override -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/about.css"> 

    
</head>
<body>
    <div id="header"></div>
    
    <!-- Banner -->
     <div class="about-page">
    <div class="about-banner" style="background-image: url('<?php echo htmlspecialchars($about['image_url']); ?>');">
        <h1><?php echo htmlspecialchars($about['title']); ?></h1>
    </div>
    
    <!-- Content -->
    <main class="about-content">
        <div class="about-section">
            <h2>Giới thiệu</h2>
            <p><?php echo nl2br(htmlspecialchars($about['content'])); ?></p>
        </div>
        
        <div class="mission-vision">
            <div class="mv-card">
                <h3>Sứ mệnh</h3>
                <p><?php echo nl2br(htmlspecialchars($about['mission'])); ?></p>
            </div>
            <div class="mv-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h3>Tầm nhìn</h3>
                <p><?php echo nl2br(htmlspecialchars($about['vision'])); ?></p>
            </div>
        </div>
    </main>
    </div>
    <div id="footer"></div>
    <script src="assets/js/main.js"></script>
    <script src="logo.js"></script>
</body>
</html>
