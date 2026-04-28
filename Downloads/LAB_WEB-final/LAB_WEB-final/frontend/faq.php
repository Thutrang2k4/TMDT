<?php
require_once __DIR__ . '/../backend/models/content_model.php';

$faqs = getAllFAQs();
$categories = getAllFAQCategories();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Các câu hỏi thường gặp về dịch vụ du lịch GoTour">
    <meta name="keywords" content="GoTour, FAQ, hỏi đáp, câu hỏi thường gặp, du lịch">
    <title>Hỏi đáp - GoTour</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .faq-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .faq-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .faq-header h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .faq-header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }
        .faq-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .category-btn {
            padding: 0.6rem 1.5rem;
            border: 2px solid orange;
            background: white;
            color: orange;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .category-btn:hover,
        .category-btn.active {
            background: orange;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
        }
        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .faq-item {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .faq-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            transition: background 0.3s ease;
        }
        .faq-question:hover {
            background: #e9ecef;
        }
        .faq-question h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 1.1rem;
            font-weight: 600;
            flex: 1;
            padding-right: 1rem;
        }
        .faq-icon {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            background:orange;
            color: white;
            border-radius: 50%;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }
        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease;
            padding: 0 1.5rem;
        }
        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 1.5rem;
        }
        .faq-answer p {
            margin: 0;
            color: #555;
            line-height: 1.8;
        }
        .faq-category-badge {
            display: inline-block;
            background: #e3f2fd;
            color: orange: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
        .no-results {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }
        @media (max-width: 768px) {
            .faq-header h1 {
                font-size: 2rem;
            }
            .faq-question h3 {
                font-size: 1rem;
            }
            .category-btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div id="header"></div>
    
    <main class="faq-container">
        <div class="py-5 mb-4 text-center">
            <h1 class="display-5 fw-bold text-dark">Câu hỏi thường gặp</h1>
            <p>Tìm câu trả lời cho các thắc mắc của bạn</p>
        </div>
        
        <?php if (!empty($categories)): ?>
        <div class="faq-categories">
            <button class="category-btn active" data-category="all">Tất cả</button>
            <?php foreach ($categories as $category): ?>
                <button class="category-btn" data-category="<?php echo htmlspecialchars($category); ?>">
                    <?php echo htmlspecialchars($category); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div class="faq-list">
            <?php if (empty($faqs)): ?>
                <div class="no-results">
                    <h3>Chưa có câu hỏi nào</h3>
                    <p>Vui lòng quay lại sau hoặc liên hệ với chúng tôi để được hỗ trợ.</p>
                </div>
            <?php else: ?>
                <?php foreach ($faqs as $faq): ?>
                    <div class="faq-item" data-category="<?php echo htmlspecialchars($faq['category']); ?>">
                        <div class="faq-question">
                            <div>
                                <span class="faq-category-badge"><?php echo htmlspecialchars($faq['category']); ?></span>
                                <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                            </div>
                            <div class="faq-icon">+</div>
                        </div>
                        <div class="faq-answer">
                            <p><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <div id="footer"></div>
    
    <script src="assets/js/main.js"></script>
    <script>
        
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const faqItem = this.parentElement;
                const isActive = faqItem.classList.contains('active');
                
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });
        
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.dataset.category;
                const faqItems = document.querySelectorAll('.faq-item');
                
                faqItems.forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('active');
                    }
                });
            });
        });
    </script>
    
    <div id="footer"></div>
    <script src="assets/js/main.js"></script>
    <script src="logo.js"></script>
</body>

</html>
