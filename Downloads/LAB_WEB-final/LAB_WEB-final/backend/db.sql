CREATE DATABASE IF NOT EXISTS GoTour;

USE GoTour;

ALTER DATABASE GoTour
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Bảng phân quyền
CREATE TABLE roles (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Bảng người dùng (thành viên + admin)
CREATE TABLE users (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    role_id        INT NOT NULL,
    full_name      VARCHAR(100) NOT NULL,
    email          VARCHAR(150) NOT NULL UNIQUE,
    password_hash  VARCHAR(255) NOT NULL,
    avatar         VARCHAR(255) DEFAULT NULL,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE tours (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    title             VARCHAR(200) NOT NULL,
    slug              VARCHAR(200) NOT NULL UNIQUE,
    short_description TEXT,
    price             DECIMAL(12,2) NOT NULL,
    duration_days     INT NOT NULL,
    status            ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP,
    image             varchar(255) NOT NULL,
    place_start       varchar(255) NOT NULL,
    vehicle           varchar(255) NOT NULL,
    long_description  LONGTEXT
) ENGINE=InnoDB;

CREATE TABLE orders (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT DEFAULT NULL,       -- cho phép khách vãng lai
    tour_id      INT NOT NULL,
    order_date   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    quantity     INT NOT NULL DEFAULT 1,
    total_price  DECIMAL(12,2) NOT NULL,
    status       ENUM('pending','confirmed','cancelled')
                    NOT NULL DEFAULT 'pending',

    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_orders_tour
        FOREIGN KEY (tour_id) REFERENCES tours(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Loại bài viết (Tin tức, Cẩm nang, Khuyến mãi,...)
CREATE TABLE post_categories (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL,
    slug  VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Bài viết / tin tức
CREATE TABLE posts (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    category_id      INT NOT NULL,
    author_id        INT NOT NULL,
    title            VARCHAR(200) NOT NULL,
    slug             VARCHAR(200) NOT NULL UNIQUE,
    summary          TEXT,
    content          LONGTEXT,
    status           ENUM('draft','published') NOT NULL DEFAULT 'draft',
    published_at     DATETIME DEFAULT NULL,
    created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_posts_category
        FOREIGN KEY (category_id) REFERENCES post_categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_posts_author
        FOREIGN KEY (author_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE tour_comments (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    tour_id     INT NOT NULL,
    user_id     INT NOT NULL,
    content     TEXT NOT NULL,
    rating      TINYINT DEFAULT NULL,  -- có thể NULL nếu chỉ hỏi đáp
    status      ENUM('pending','approved','rejected')
                    NOT NULL DEFAULT 'pending',
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_tour_comments_tour
        FOREIGN KEY (tour_id) REFERENCES tours(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_tour_comments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT chk_tour_comments_rating
        CHECK (rating IS NULL OR rating BETWEEN 1 AND 5)
) ENGINE=InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE post_comments (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    post_id     INT NOT NULL,
    user_id     INT NOT NULL,
    content     TEXT NOT NULL,
    rating      TINYINT DEFAULT NULL,  -- có thể NULL
    status      ENUM('pending','approved','rejected')
                    NOT NULL DEFAULT 'pending',
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_post_comments_post
        FOREIGN KEY (post_id) REFERENCES posts(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_post_comments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT chk_post_comments_rating
        CHECK (rating IS NULL OR rating BETWEEN 1 AND 5)
) ENGINE=InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE faqs (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    question      VARCHAR(500) NOT NULL,
    answer        TEXT NOT NULL,
    category      VARCHAR(100) DEFAULT 'Chung',
    display_order INT DEFAULT 0,
    is_active     TINYINT(1) DEFAULT 1,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by    INT DEFAULT NULL,

    KEY idx_category (category),
    KEY idx_active (is_active),
    KEY idx_order (display_order),

    CONSTRAINT fk_faqs_user
        FOREIGN KEY (created_by) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_messages (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL,      -- tên người gửi (không cần là user)
    email        VARCHAR(150) NOT NULL,
    subject      VARCHAR(200) NOT NULL,
    message      TEXT NOT NULL,
    status       ENUM('new','read','replied')
                    NOT NULL DEFAULT 'new',
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    handled_by   INT DEFAULT NULL,          -- user/admin xử lý (nếu có)

    CONSTRAINT fk_contact_messages_handler
        FOREIGN KEY (handled_by) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;


-- Bảng lưu nội dung trang About (Giới thiệu)
CREATE TABLE about_content (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    content     TEXT NOT NULL,
    mission     TEXT DEFAULT NULL,
    vision      TEXT DEFAULT NULL,
    image_url   VARCHAR(255) DEFAULT NULL,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by  INT DEFAULT NULL,

    CONSTRAINT fk_about_content_user
        FOREIGN KEY (updated_by) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu
-- =========================
-- 1. ROLES
-- =========================
INSERT INTO roles (id, name) VALUES
(1, 'admin'),
(2, 'member');

-- =========================
-- 2. USERS
-- =========================
INSERT INTO users (id, role_id, full_name, email, password_hash, avatar, created_at) VALUES
(1, 1, 'Nguyễn Quản Trị', 'admin@mywebtour.com',  '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle', NULL,        NOW()),
(2, 2, 'Trần Khách Hàng', 'tran.khach@example.com', '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle', NULL,      NOW()),
(3, 2, 'Lê Du Lịch',      'le.dulich@example.com', '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle', NULL,      NOW()),
(4, 2, 'Phạm Tham Quan',  'pham.thamquan@example.com', '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle', NULL, NOW());

-- =========================
-- 3. TOURS
-- =========================
INSERT INTO tours (id, title, slug, short_description, price, duration_days, status, created_at, updated_at, image) VALUES
(1, 'Đà Nẵng - Hội An 3N2Đ', 'da-nang-hoi-an-3n2d',
 'Tour khám phá Đà Nẵng, phố cổ Hội An, check-in Cầu Rồng.', 
 3990000, 3, 'active', NOW(), NOW(),
 'https://bazantravel.com/cdn/medias/uploads/21/21380-cap-treo-tour-da-nang-hoi-an-4-ngay-3-dem-670x446.jpg'),

(2, 'Nha Trang Biển Xanh 4N3Đ', 'nha-trang-bien-xanh-4n3d',
 'Tour nghỉ dưỡng Nha Trang, tham quan VinWonders, tắm biển.', 
 4590000, 4, 'active', NOW(), NOW(),
 'https://static.vinwonders.com/production/bai-bien-nha-trang-topbanner.jpg'),

(3, 'Đà Lạt Mộng Mơ 3N2Đ', 'da-lat-mong-mo-3n2d',
 'Tour Đà Lạt ngắm hoa, săn mây, tham quan các địa điểm nổi tiếng.', 
 3590000, 3, 'active', NOW(), NOW(),
 'https://samtenhills.vn/wp-content/uploads/2024/01/dai-bao-thap-kinh-luan-lon-nhat-the-gioi.jpg'),

(4, 'Hà Nội - Hạ Long 4N3Đ', 'ha-noi-ha-long-4n3d',
 'Tour kết hợp Hà Nội phố cổ và du thuyền Vịnh Hạ Long.', 
 5690000, 4, 'active', NOW(), NOW(),
 'https://statics.vinpearl.com/du-lich-ha-long-2_1632635256.jpg'),

(5, 'Phú Quốc Thiên Đường Nghỉ Dưỡng 3N2Đ', 'phu-quoc-thien-duong-3n2d',
 'Tour Phú Quốc tắm biển, câu cá, tham quan GrandWorld.', 
 4990000, 3, 'inactive', NOW(), NOW(),
 'https://samtenhills.vn/wp-content/uploads/2024/01/dai-bao-thap-kinh-luan-lon-nhat-the-gioi.jpg');

-- =========================
-- 4. ORDERS
-- =========================
-- Giả sử user_id = 2,3,4 là member; có 1 đơn guest (user_id NULL)
INSERT INTO orders (id, user_id, tour_id, order_date, quantity, total_price, status) VALUES
(1, 2, 1, NOW() - INTERVAL 10 DAY, 2, 2 * 3990000, 'confirmed'),
(2, 2, 3, NOW() - INTERVAL 5 DAY,  3, 3 * 3590000, 'pending'),
(3, 3, 2, NOW() - INTERVAL 7 DAY,  1, 1 * 4590000, 'cancelled'),
(4, 4, 4, NOW() - INTERVAL 2 DAY,  4, 4 * 5690000, 'confirmed'),
(5, NULL, 1, NOW() - INTERVAL 1 DAY, 2, 2 * 3990000, 'pending'); -- khách vãng lai

-- =========================
-- 5. POST CATEGORIES
-- =========================
INSERT INTO post_categories (id, name, slug) VALUES
(1, 'Tin tức', 'tin-tuc'),
(2, 'Cẩm nang du lịch', 'cam-nang-du-lich'),
(3, 'Khuyến mãi', 'khuyen-mai');

-- =========================
-- 6. POSTS
-- =========================
INSERT INTO posts (id, category_id, author_id, title, slug, summary, content, status, published_at, created_at) VALUES
(1, 1, 1,
 'Khai trương website MyWeb Tour với nhiều ưu đãi',
 'khai-truong-website-myweb-tour',
 'Giới thiệu website du lịch MyWeb Tour và các chương trình ưu đãi khai trương.',
 'Nội dung chi tiết về lễ khai trương, các chương trình giảm giá, voucher cho khách hàng mới...',
 'published', NOW() - INTERVAL 15 DAY, NOW() - INTERVAL 16 DAY),

(2, 2, 1,
 '5 kinh nghiệm du lịch Đà Lạt tự túc',
 '5-kinh-nghiem-du-lich-da-lat-tu-tuc',
 'Tổng hợp các kinh nghiệm hữu ích khi tự đi Đà Lạt.',
 'Bài viết chia sẻ về thời điểm nên đi, phương tiện di chuyển, địa điểm tham quan và các món ăn nên thử...',
 'published', NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 13 DAY),

(3, 2, 1,
 'Gợi ý lịch trình 3 ngày tại Đà Nẵng',
 'goi-y-lich-trinh-3-ngay-tai-da-nang',
 'Lịch trình tham quan Đà Nẵng cơ bản trong 3 ngày.',
 'Ngày 1: Bán đảo Sơn Trà, biển Mỹ Khê. Ngày 2: Bà Nà Hills. Ngày 3: Hội An cổ kính...',
 'published', NOW() - INTERVAL 9 DAY, NOW() - INTERVAL 10 DAY),

(4, 3, 1,
 'Giảm giá 20% tour Nha Trang trong tháng này',
 'giam-gia-20-tour-nha-trang-thang-nay',
 'Chương trình khuyến mãi dành cho tour Nha Trang biển xanh 4N3Đ.',
 'Chi tiết điều kiện áp dụng, thời gian đặt tour và thời gian khởi hành trong đợt khuyến mãi...',
 'published', NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 7 DAY),

(5, 1, 1,
 'Thông báo cập nhật chính sách hoàn hủy tour',
 'cap-nhat-chinh-sach-hoan-huy-tour',
 'Giới thiệu các thay đổi trong quy định hoàn/huỷ tour.',
 'Nội dung giải thích rõ các mức phí, thời hạn báo huỷ, và các trường hợp đặc biệt...',
 'draft', NULL, NOW() - INTERVAL 3 DAY);

-- =========================
-- 7. TOUR COMMENTS
-- =========================
INSERT INTO tour_comments (id, tour_id, user_id, content, rating, status, created_at) VALUES
(1, 1, 2,
 'Tour Đà Nẵng - Hội An rất vui, HDV nhiệt tình, lịch trình hợp lý.',
 5, 'approved', NOW() - INTERVAL 8 DAY),

(2, 1, 3,
 'Khách sạn ổn, nhưng đồ ăn chưa đa dạng lắm.',
 4, 'approved', NOW() - INTERVAL 7 DAY),

(3, 3, 4,
 'Đà Lạt đẹp, thời tiết dễ chịu. Sẽ quay lại cùng MyWeb Tour.',
 5, 'approved', NOW() - INTERVAL 4 DAY),

(4, 2, 2,
 'Mình muốn hỏi thêm về chi phí phát sinh trong tour Nha Trang?',
 NULL, 'pending', NOW() - INTERVAL 1 DAY),

(5, 4, 3,
 'Lịch trình Hà Nội - Hạ Long khá dày, hơi mệt với người lớn tuổi.',
 3, 'approved', NOW() - INTERVAL 2 DAY);

-- =========================
-- 8. POST COMMENTS
-- =========================
INSERT INTO post_comments (id, post_id, user_id, content, rating, status, created_at) VALUES
(1, 2, 2,
 'Bài viết rất hữu ích, mình chuẩn bị đi Đà Lạt nên áp dụng luôn.',
 5, 'approved', NOW() - INTERVAL 11 DAY),

(2, 3, 3,
 'Lịch trình gợi ý khá hợp lý, nhưng có thể thêm một vài điểm ăn uống.',
 4, 'approved', NOW() - INTERVAL 8 DAY),

(3, 4, 4,
 'Mình muốn hỏi thời gian áp dụng khuyến mãi là đến ngày nào?',
 NULL, 'pending', NOW() - INTERVAL 5 DAY),

(4, 1, 2,
 'Chúc mừng MyWeb Tour khai trương, mong sớm có thêm nhiều tour mới.',
 5, 'approved', NOW() - INTERVAL 14 DAY),

(5, 2, 4,
 'Mình thấy nên bổ sung thêm gợi ý khách sạn giá rẻ.',
 4, 'approved', NOW() - INTERVAL 10 DAY);

-- =========================
-- 9. FAQS
-- =========================
INSERT INTO faqs (question, answer, category, display_order, is_active, created_by) VALUES
('Làm thế nào để đặt tour du lịch?', 'Bạn có thể đặt tour trực tiếp trên website của chúng tôi bằng cách chọn tour mong muốn, điền thông tin và thanh toán. Hoặc liên hệ hotline: 1900-xxxx để được tư vấn và hỗ trợ đặt tour.', 'Đặt tour', 1, 1, 1),
('Tôi có thể hủy tour đã đặt không?', 'Có, bạn có thể hủy tour theo chính sách hủy của chúng tôi. Tùy vào thời gian hủy, bạn sẽ được hoàn lại một phần hoặc toàn bộ chi phí. Vui lòng xem chi tiết trong mục Điều khoản và Điều kiện.', 'Đặt tour', 2, 1, 1),
('Các hình thức thanh toán nào được chấp nhận?', 'Chúng tôi chấp nhận thanh toán qua thẻ ATM, thẻ tín dụng (Visa/Mastercard), chuyển khoản ngân hàng, ví điện tử (MoMo, ZaloPay) và thanh toán trực tiếp tại văn phòng.', 'Thanh toán', 3, 1, 1),
('Tour có bao gồm bảo hiểm du lịch không?', 'Có, tất cả các tour của chúng tôi đều bao gồm bảo hiểm du lịch cơ bản. Bạn cũng có thể mua thêm gói bảo hiểm mở rộng nếu muốn.', 'Dịch vụ', 4, 1, 1),
('Tôi cần chuẩn bị gì trước khi đi tour?', 'Bạn cần chuẩn bị giấy tờ tùy thân (CMND/Passport), quần áo phù hợp với thời tiết, thuốc men cá nhân và một số vật dụng cần thiết khác. Chúng tôi sẽ gửi danh sách chi tiết sau khi bạn đặt tour.', 'Chuẩn bị', 5, 1, 1),
('Trẻ em có được giảm giá không?', 'Có, trẻ em dưới 5 tuổi được miễn phí (không tính ghế riêng), từ 5-10 tuổi được giảm 50% và từ 11 tuổi trở lên tính như người lớn.', 'Giá cả', 6, 1, 1),
('Tôi có thể thay đổi lịch trình tour không?', 'Tùy thuộc vào loại tour và thời gian thay đổi, bạn có thể được phép thay đổi lịch trình với một khoản phí nhất định. Vui lòng liên hệ với chúng tôi càng sớm càng tốt.', 'Đặt tour', 7, 1, 1),
('Hướng dẫn viên có nói được tiếng Anh không?', 'Có, chúng tôi có hướng dẫn viên nói tiếng Anh cho các tour quốc tế và tour trong nước theo yêu cầu. Vui lòng thông báo trước khi đặt tour.', 'Dịch vụ', 8, 1, 1);

-- =========================
-- 10. CONTACT MESSAGES
-- =========================
INSERT INTO contact_messages (id, name, email, subject, message, status, created_at, handled_by) VALUES
(1,
 'Nguyễn Văn A',
 'nguyenvana@example.com',
 'Hỏi về lịch khởi hành tour Đà Nẵng',
 'Cho mình hỏi tour Đà Nẵng - Hội An 3N2Đ có lịch khởi hành gần nhất là khi nào?',
 'replied', NOW() - INTERVAL 6 DAY, 1),

(2,
 'Trần Thị B',
 'tranthib@example.com',
 'Tư vấn tour cho gia đình 4 người',
 'Gia đình mình có 2 người lớn, 2 trẻ em muốn đi biển trong tháng 7, nhờ tư vấn giúp.',
 'read', NOW() - INTERVAL 3 DAY, 1),

(3,
 'Lê Minh C',
 'leminhc@example.com',
 'Góp ý về giao diện website',
 'Mình góp ý là nên có bộ lọc theo giá và theo thời gian khởi hành để dễ tìm tour hơn.',
 'new', NOW() - INTERVAL 1 DAY, NULL);

-- Thêm công ty demo
CREATE TABLE company (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    website VARCHAR(255) DEFAULT NULL,
    email_support VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

INSERT INTO company (name, logo, email_support)
VALUES ('GoTour', 'assets/images/logo.png', 'support@gotour.com');

-- Thêm chi nhánh demo
-- Bảng chi nhánh
CREATE TABLE branches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    region VARCHAR(50),
    name VARCHAR(255),
    address TEXT,
    phone VARCHAR(50),
    email VARCHAR(255)
    
);

INSERT INTO branches (id, region, name, address, phone, email) VALUES
(1,'hcm', 'Cơ sở 1', '268 Lý Thường Kiệt, Phường 14, Quận 10, TP. Hồ Chí Minh.', '(84-24) 3866 8999', '268LyThuongKieu@vietravel.com'),
(2,'hcm', 'Cơ sở 2', 'Khu phố Tân Lập, Phường Đông Hòa, TP.HCM', '(84-24) 3535 8709', 'LangDaiHoc@vietravel.com'),
(3,'mien-bac', 'Cơ sở chính', 'Số 1 Đại Cồ Việt, Phường Bạch Mai, Thành phố Hà Nội', '(84-24) 3869 2942', 'hanoi@vietravel.com'),
(4,'mien-trung', 'Cơ sở chính', '54 Nguyễn Lương Bằng, phường Hòa Khánh Bắc, quận Liên Chiểu, thành phố Đà Nẵng.', '(84-236) 3863 544', 'danang@vietravel.com');

INSERT INTO about_content (id, title, content, mission, vision, image_url) VALUES
(1, 'Về chúng tôi - GoTour', 
'GoTour là công ty du lịch hàng đầu tại Việt Nam, chuyên cung cấp các tour du lịch trong nước và quốc tế với chất lượng dịch vụ cao cấp. Với đội ngũ nhân viên giàu kinh nghiệm và tận tâm, chúng tôi cam kết mang đến cho khách hàng những trải nghiệm du lịch tuyệt vời nhất.',
'Sứ mệnh của chúng tôi là kết nối mọi người với thế giới thông qua những chuyến du lịch ý nghĩa, an toàn và đáng nhớ.',
'Trở thành công ty du lịch hàng đầu khu vực Đông Nam Á, mang đến trải nghiệm du lịch đẳng cấp thế giới cho mọi khách hàng.',
'assets/images/about-us.jpg');