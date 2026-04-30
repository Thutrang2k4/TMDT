CREATE DATABASE IF NOT EXISTS GoTour; USE
    GoTour;
ALTER
    DATABASE GoTour CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SET NAMES
    utf8mb4;
SET SESSION
    sql_mode = '';
    -- ============================================================
    -- SCHEMA
    -- ============================================================
CREATE TABLE `roles`(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    PRIMARY KEY(`id`),
    UNIQUE KEY `name`(`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `users`(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `role_id` INT(11) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(), PRIMARY KEY(`id`), UNIQUE KEY `email`(`email`), KEY `fk_users_role`(`role_id`), CONSTRAINT `fk_users_role` FOREIGN KEY(`role_id`) REFERENCES `roles`(`id`) ON UPDATE CASCADE) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `about_content`(
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(255) NOT NULL,
        `content` TEXT NOT NULL,
        `mission` TEXT DEFAULT NULL,
        `vision` TEXT DEFAULT NULL,
        `image_url` VARCHAR(255) DEFAULT NULL,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(), `updated_by` INT(11) DEFAULT NULL, PRIMARY KEY(`id`), KEY `fk_about_content_user`(`updated_by`), CONSTRAINT `fk_about_content_user` FOREIGN KEY(`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `branches`(
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `region` VARCHAR(50) DEFAULT NULL,
            `name` VARCHAR(255) DEFAULT NULL,
            `address` TEXT DEFAULT NULL,
            `phone` VARCHAR(50) DEFAULT NULL,
            `email` VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(`id`)
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `company`(
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `logo` VARCHAR(255) DEFAULT NULL,
            `website` VARCHAR(255) DEFAULT NULL,
            `email_support` VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(`id`)
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `contact_messages`(
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `subject` VARCHAR(200) NOT NULL,
            `message` TEXT NOT NULL,
            `status` ENUM('new', 'read', 'replied') NOT NULL DEFAULT 'new',
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(), `handled_by` INT(11) DEFAULT NULL, PRIMARY KEY(`id`), KEY `fk_contact_messages_handler`(`handled_by`), CONSTRAINT `fk_contact_messages_handler` FOREIGN KEY(`handled_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `faqs`(
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `question` VARCHAR(500) NOT NULL,
                `answer` TEXT NOT NULL,
                `category` VARCHAR(100) DEFAULT 'Chung',
                `display_order` INT(11) DEFAULT 0,
                `is_active` TINYINT(1) DEFAULT 1,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(), `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(), `created_by` INT(11) DEFAULT NULL, PRIMARY KEY(`id`), KEY `idx_category`(`category`), KEY `idx_active`(`is_active`), KEY `idx_order`(`display_order`), KEY `fk_faqs_user`(`created_by`), CONSTRAINT `fk_faqs_user` FOREIGN KEY(`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `tours`(
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `title` VARCHAR(200) NOT NULL,
                    `slug` VARCHAR(200) NOT NULL,
                    `short_description` TEXT DEFAULT NULL,
                    `price` DECIMAL(12, 2) NOT NULL,
                    `duration_days` INT(11) NOT NULL,
                    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
                    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(), `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(), `image` VARCHAR(255) NOT NULL, `place_start` VARCHAR(255) NOT NULL, `vehicle` VARCHAR(255) NOT NULL, `day_start` VARCHAR(255) NOT NULL, `host` VARCHAR(255) NOT NULL, `long_description` LONGTEXT DEFAULT NULL, `hotel` TEXT NOT NULL, `note` LONGTEXT DEFAULT NULL, PRIMARY KEY(`id`), UNIQUE KEY `slug`(`slug`)) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `orders`(
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `user_id` INT(11) DEFAULT NULL,
                        `tour_id` INT(11) NOT NULL,
                        `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(), `quantity` INT(11) NOT NULL DEFAULT 1, `total_price` DECIMAL(12, 2) NOT NULL, `status` ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending', PRIMARY KEY(`id`), KEY `fk_orders_user`(`user_id`), KEY `fk_orders_tour`(`tour_id`), CONSTRAINT `fk_orders_user` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE, CONSTRAINT `fk_orders_tour` FOREIGN KEY(`tour_id`) REFERENCES `tours`(`id`) ON UPDATE CASCADE) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `my_orders`(
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `user_id` INT(11) NOT NULL,
                            `order_id` INT(11) NOT NULL,
                            `tour_id` INT(11) NOT NULL,
                            `total_quantity` INT(11) DEFAULT 1,
                            `adult_qty` INT(11) DEFAULT 0,
                            `child_qty` INT(11) DEFAULT 0,
                            `infant_qty` INT(11) DEFAULT 0,
                            `depart_date` DATE DEFAULT NULL,
                            `total_price` INT(11) NOT NULL,
                            `status` ENUM(
                                'pending',
                                'confirmed',
                                'completed',
                                'cancelled'
                            ) DEFAULT 'confirmed',
                            `note` TEXT DEFAULT NULL,
                            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(), PRIMARY KEY(`id`)) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `post_categories`(
                                `id` INT(11) NOT NULL AUTO_INCREMENT,
                                `name` VARCHAR(100) NOT NULL,
                                `slug` VARCHAR(100) NOT NULL,
                                PRIMARY KEY(`id`),
                                UNIQUE KEY `slug`(`slug`)
                            ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `posts`(
                                `id` INT(11) NOT NULL AUTO_INCREMENT,
                                `category_id` INT(11) NOT NULL,
                                `author_id` INT(11) NOT NULL,
                                `title` VARCHAR(200) NOT NULL,
                                `slug` VARCHAR(200) NOT NULL,
                                `summary` TEXT DEFAULT NULL,
                                `content` LONGTEXT DEFAULT NULL,
                                `status` ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
                                `published_at` DATETIME DEFAULT NULL,
                                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(), PRIMARY KEY(`id`), UNIQUE KEY `slug`(`slug`), KEY `fk_posts_category`(`category_id`), KEY `fk_posts_author`(`author_id`), CONSTRAINT `fk_posts_category` FOREIGN KEY(`category_id`) REFERENCES `post_categories`(`id`) ON UPDATE CASCADE, CONSTRAINT `fk_posts_author` FOREIGN KEY(`author_id`) REFERENCES `users`(`id`) ON UPDATE CASCADE) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `post_comments`(
                                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                                    `post_id` INT(11) NOT NULL,
                                    `user_id` INT(11) NOT NULL,
                                    `content` TEXT NOT NULL,
                                    `rating` TINYINT(4) DEFAULT NULL,
                                    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
                                    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(), PRIMARY KEY(`id`), KEY `fk_post_comments_post`(`post_id`), KEY `fk_post_comments_user`(`user_id`), CONSTRAINT `fk_post_comments_post` FOREIGN KEY(`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_post_comments_user` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci; CREATE TABLE `tour_comments`(
                                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                                        `tour_id` INT(11) NOT NULL,
                                        `user_id` INT(11) NOT NULL,
                                        `content` TEXT NOT NULL,
                                        `rating` TINYINT(4) DEFAULT NULL,
                                        `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
                                        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(), PRIMARY KEY(`id`), KEY `fk_tour_comments_tour`(`tour_id`), KEY `fk_tour_comments_user`(`user_id`), CONSTRAINT `fk_tour_comments_tour` FOREIGN KEY(`tour_id`) REFERENCES `tours`(`id`) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_tour_comments_user` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
                                        -- ============================================================
                                        -- DATA
                                        -- ============================================================
                                        INSERT INTO `roles`(`id`, `name`)
                                    VALUES(1, 'admin'),
                                    (2, 'member');
                                INSERT INTO `users`(
                                    `id`,
                                    `role_id`,
                                    `full_name`,
                                    `email`,
                                    `password_hash`,
                                    `avatar`,
                                    `created_at`
                                )
                            VALUES(
                                1,
                                1,
                                'Nguyễn Quản Trị',
                                'admin@mywebtour.com',
                                '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle',
                                NULL,
                                '2026-04-28 02:37:58'
                            ),
                            (
                                2,
                                2,
                                'Trần Khách Hàng',
                                'tran.khach@example.com',
                                '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle',
                                NULL,
                                '2026-04-28 02:37:58'
                            ),
                            (
                                3,
                                2,
                                'Lê Du Lịch',
                                'le.dulich@example.com',
                                '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle',
                                NULL,
                                '2026-04-28 02:37:58'
                            ),
                            (
                                4,
                                2,
                                'Phạm Tham Quan',
                                'pham.thamquan@example.com',
                                '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle',
                                NULL,
                                '2026-04-28 02:37:58'
                            ),
                            (
                                5,
                                1,
                                'Trang Trịnh',
                                'admin1@travelco.com',
                                '$2y$10$iUDnqnQr7.6kHRg5TAYbaOWhmwECjb1MVQJpJ/twZ4TXIVxXjiuPi',
                                NULL,
                                '2026-04-28 04:08:05'
                            ),
                            (
                                6,
                                2,
                                'Trang Trịnh',
                                'admin@travelco.com',
                                '$2y$10$8O.ccNYjxOF/iDoCqkvkwuB/BTb1SAbP/b4t2sdLUJ/OwvUER7bsC',
                                NULL,
                                '2026-04-28 11:58:36'
                            ),
                            (
                                7,
                                2,
                                'tuanuser',
                                'tuanuser@gmail.com',
                                '$2y$10$VkUJ.iErdBYo13g52NV0Fe2OyMykYQHAsOzPTY9hjuhpRu0K7MESK',
                                NULL,
                                '2026-04-30 13:35:34'
                            ),
                            (
                                8,
                                2,
                                'tuanadmin',
                                'tuanadmin@gmail.com',
                                '$2y$10$aFcu.26gGi.THWp1xnteeOcKy1P7lDq9sISLaOO9eY2LZqMel1y9O',
                                NULL,
                                '2026-04-30 13:36:26'
                            );
                        INSERT INTO `about_content`(
                            `id`,
                            `title`,
                            `content`,
                            `mission`,
                            `vision`,
                            `image_url`,
                            `updated_at`,
                            `updated_by`
                        )
                    VALUES(
                        1,
                        'Về chúng tôi - GoTour',
                        'GoTour là công ty du lịch hàng đầu tại Việt Nam, chuyên cung cấp các tour du lịch trong nước và quốc tế với chất lượng dịch vụ cao cấp. Với đội ngũ nhân viên giàu kinh nghiệm và tận tâm, chúng tôi cam kết mang đến cho khách hàng những trải nghiệm du lịch tuyệt vời nhất.',
                        'Sứ mệnh của chúng tôi là kết nối mọi người với thế giới thông qua những chuyến du lịch ý nghĩa, an toàn và đáng nhớ.',
                        'Trở thành công ty du lịch hàng đầu khu vực Đông Nam Á, mang đến trải nghiệm du lịch đẳng cấp thế giới cho mọi khách hàng.',
                        'assets/images/about-us.jpg',
                        '2026-04-27 12:37:58',
                        NULL
                    );
                INSERT INTO `branches`(
                    `id`,
                    `region`,
                    `name`,
                    `address`,
                    `phone`,
                    `email`
                )
            VALUES(
                1,
                'hcm',
                'Cơ sở 1',
                '268 Lý Thường Kiệt, Phường 14, Quận 10, TP. Hồ Chí Minh.',
                '(84-24) 3866 8999',
                '268LyThuongKieu@vietravel.com'
            ),
            (
                2,
                'hcm',
                'Cơ sở 2',
                'Khu phố Tân Lập, Phường Đông Hòa, TP.HCM',
                '(84-24) 3535 8709',
                'LangDaiHoc@vietravel.com'
            ),
            (
                3,
                'mien-bac',
                'Cơ sở chính',
                'Số 1 Đại Cồ Việt, Phường Bạch Mai, Thành phố Hà Nội',
                '(84-24) 3869 2942',
                'hanoi@vietravel.com'
            ),
            (
                4,
                'mien-trung',
                'Cơ sở chính',
                '54 Nguyễn Lương Bằng, phường Hòa Khánh Bắc, quận Liên Chiểu, thành phố Đà Nẵng.',
                '(84-236) 3863 544',
                'danang@vietravel.com'
            );
        INSERT INTO `company`(
            `id`,
            `name`,
            `logo`,
            `website`,
            `email_support`
        )
    VALUES(
        1,
        'GoTour',
        'assets/images/logo.png',
        NULL,
        'support@gotour.com'
    );
INSERT INTO `contact_messages`(
    `id`,
    `name`,
    `email`,
    `subject`,
    `message`,
    `status`,
    `created_at`,
    `handled_by`
)
VALUES(
    1,
    'Nguyễn Văn A',
    'nguyenvana@example.com',
    'Hỏi về lịch khởi hành tour Đà Nẵng',
    'Cho mình hỏi tour Đà Nẵng - Hội An 3N2Đ có lịch khởi hành gần nhất là khi nào?',
    'replied',
    '2026-04-22 02:37:58',
    1
),
(
    2,
    'Trần Thị B',
    'tranthib@example.com',
    'Tư vấn tour cho gia đình 4 người',
    'Gia đình mình có 2 người lớn, 2 trẻ em muốn đi biển trong tháng 7, nhờ tư vấn giúp.',
    'read',
    '2026-04-25 02:37:58',
    1
),
(
    3,
    'Lê Minh C',
    'leminhc@example.com',
    'Góp ý về giao diện website',
    'Mình góp ý là nên có bộ lọc theo giá và theo thời gian khởi hành để dễ tìm tour hơn.',
    'new',
    '2026-04-27 02:37:58',
    NULL
);
INSERT INTO `faqs`(
    `id`,
    `question`,
    `answer`,
    `category`,
    `display_order`,
    `is_active`,
    `created_at`,
    `updated_at`,
    `created_by`
)
VALUES(
    1,
    'Làm thế nào để đặt tour du lịch?',
    'Bạn có thể đặt tour trực tiếp trên website của chúng tôi bằng cách chọn tour mong muốn, điền thông tin và thanh toán. Hoặc liên hệ hotline: 1900-xxxx để được tư vấn và hỗ trợ đặt tour.',
    'Đặt tour',
    1,
    1,
    '2026-04-27 12:37:58',
    '2026-04-27 12:37:58',
    1
),
(
    2,
    'Tôi có thể hủy tour đã đặt không?',
    'Có, bạn có thể hủy tour theo chính sách hủy của chúng tôi. Tùy vào thời gian hủy, bạn sẽ được hoàn lại một phần hoặc toàn bộ chi phí. Vui lòng xem chi tiết trong mục Điều khoản và Điều kiện.',
    'Đặt tour',
    2,
    1,
    '2026-04-27 12:37:58',
    '2026-04-27 12:37:58',
    1
),
(
    3,
    'Các hình thức thanh toán nào được chấp nhận?',
    'Chúng tôi chấp nhận thanh toán qua thẻ ATM, thẻ tín dụng (Visa/Mastercard), chuyển khoản ngân hàng, ví điện tử (MoMo, ZaloPay) và thanh toán trực tiếp tại văn phòng.',
    'Thanh toán',
    3,
    1,
    '2026-04-27 12:37:58',
    '2026-04-27 12:37:58',
    1
),
(
    4,
    'Tour có bao gồm bảo hiểm du lịch không?',
    'Có, tất cả các tour của chúng tôi đều bao gồm bảo hiểm du lịch cơ bản. Bạn cũng có thể mua thêm gói bảo hiểm mở rộng nếu muốn.',
    'Dịch vụ',
    4,
    1,
    '2026-04-27 12:37:58',
    '2026-04-27 12:37:58',
    1
),
(
    5,
    'Tôi cần chuẩn bị gì trước khi đi tour?',
    'Bạn cần chuẩn bị giấy tờ tùy thân (CMND/Passport), quần áo phù hợp với thời tiết, thuốc men cá nhân và một số vật dụng cần thiết khác. Chúng tôi sẽ gửi danh sách chi tiết sau khi bạn đặt tour.',
    'Chuẩn bị',
    5,
    1,
    '2026-04-27 12:37:58',
    '2026-04-27 12:37:58',
    1
),
(
    6,
    'Trẻ em có được giảm giá không?',
    'Có, trẻ em dưới 5 tuổi được miễn phí (không tính ghế riêng), từ 5-10 tuổi được giảm 50% và từ 11 tuổi trở lên tính như người lớn.',
    'Giá cả',
    6,
    1,
    '2026-04-27 12:37:58',
    '2026-04-27 12:37:58',
    1
),
(
    7,
    'Tôi có thể thay đổi lịch trình tour không?',
    'Tùy thuộc vào loại tour và thời gian thay đổi, bạn có thể được phép thay đổi lịch trình với một khoản phí nhất định. Vui lòng liên hệ với chúng tôi càng sớm càng tốt.',
    'Đặt tour',
    7,
    1,
    '2026-04-27 12:37:58',
    '2026-04-27 12:37:58',
    1
),
(
    8,
    'Hướng dẫn viên có nói được tiếng Anh không?',
    'Có, chúng tôi có hướng dẫn viên nói tiếng Anh cho các tour quốc tế và tour trong nước theo yêu cầu. Vui lòng thông báo trước khi đặt tour.',
    'Dịch vụ',
    8,
    1,
    '2026-04-27 12:37:58',
    '2026-04-27 12:37:58',
    1
);
INSERT INTO `tours`(
    `id`,
    `title`,
    `slug`,
    `short_description`,
    `price`,
    `duration_days`,
    `status`,
    `created_at`,
    `updated_at`,
    `image`,
    `place_start`,
    `vehicle`,
    `day_start`,
    `host`,
    `long_description`,
    `hotel`,
    `note`
)
VALUES(
    1,
    'TOUR ĐÀ LẠT  3N3Đ | Thiên Đường Săn Mây - Langbiang Land - Tiệc BBQ',
    'tour-da-lat-3n3d-thien-duong-san-may-langbiang-land-tiec-bbq',
    'Trải nghiệm cáp treo xuyên rừng thông - Săn mây cầu đất - Langbiang land – giao lưu cồng chiêng',
    2586000.00,
    3,
    'active',
    '2026-04-28 02:37:58',
    '2026-04-30 02:36:38',
    'https://bazantravel.com/cdn/medias/uploads/21/21380-cap-treo-tour-da-nang-hoi-an-4-ngay-3-dem-670x446.jpg',
    'Tp.Hồ Chí Minh',
    'Xe du lịch',
    'Tối Thứ 5 hàng tuần',
    'Du lịch Việt',
    'NGÀY 1 | THIÊN ĐƯỜNG SĂN MÂY – ĐỒI CHÈ CẦU ĐẤT - TRẠM KÍ ỨC – CÁP TREO ĐỒI ROBIN - THIỀN VIỆN TRÚC LÂM (Ăn sáng, ăn trưa, BBQ tối)\n05h00: Đến Cầu Đất, Đoàn đến với khu vực:\n\n• THIÊN ĐƯỜNG SĂN MÂY - Cầu Đất Panorama để đón bình mình lên, ngắm và chụp hình với những đám mây trắng bồng bềnh. Quý khách sẽ được thỏa sức check in với những tiểu cảnh đẹp mê người nơi đây. Ngoài ra Quý khách cũng có thể thuê những bộ trang phục theo nhiều phong cách: Cổ trang, Mông Cổ…để chụp những bộ ảnh độc đáo (chi phí thuê trang phục tự túc).\n06h30: Quý khách dùng điểm tâm sáng tại nhà hàng Panorama.\n07h30: Tiếp tục khám phá những điểm tham quan hấp dẫn tại khu vực Cầu Đất:\n\n• ĐỒI CHÈ CẦU ĐẤT: Với độ cao 1650 mét so với mực nước biển cùng những dãy chè xanh tươi nối dài bất tận, đồi chè Cầu Đất đẹp đến ngỡ ngàng dưới làn sương sớm của bình mình chớm nở. \nCheck – in với CÁNH ĐỒNG ĐIỆN GIÓ trên đồi chè xanh mát.\nĐoàn di chuyển về trung tâm Thành Phố Đà Lạt, ghé tham quan :\n\n• TRẠM KÝ ỨC- NGÔI LÀNG CỔ CHÂU ÂU là bức tranh hoàn mỹ, nơi từng đường nét khắc họa lên đường nét vẻ đẹp yên bình. Không chỉ đơn giản là một nơi để ghé thăm, mà nó là một hành trình – đưa bạn trở về với những giấc mơ thuở ấu thơ.\nTrưa:    Dùng bữa trưa tại nhà hàng. Đoàn về khách sạn nhận phòng nghỉ ngơi.\nChiều:  Đoàn di chuyển tham quan\n\n• CÁP TREO XUYÊN RỪNG THÔNG – ĐẦU TIÊN TẠI ĐÀ LẠT mang đến trải nghiệm ngắm cảnh tuyệt vời, cho phép du khách chiêm ngưỡng toàn cảnh thành phố, rừng thông bạt ngàn và hồ Tuyền Lâm từ trên cao (chi phí tự túc).\n• THIỀN VIỆN TRÚC LÂM : Tọa lạc trên núi Phụng Hoàng, nhìn xuống hồ Tuyền Lâm xanh biếc, đây là thiền viện lớn và đẹp bậc nhất Đà Lạt.\nTối:       Quý khách dùng bữa tối BBQ hoành tráng tại nhà hàng trong tiết trời se lạnh và không gian cực thư giãn của Đà Lạt. \n\nNGÀY 2 | LANGBIANG LAND- GIAO LƯU VĂN HOÁ CỒNG CHIÊNG- FRESH GARDEN (Ăn sáng, trưa) (Tối tự túc) \nSáng: Quý khách dùng điểm tâm sáng. Bắt đầu hành trình tham quan TP Đà Lạt:\n\n• QUẢNG TRƯỜNG LÂM VIÊN : Tọa lạc giữa \"trái tim\" của thành phố hoa, hướng ra hồ Xuân Hương, ấn tượng với công trình nghệ thuật khổng lồ với khối bông hoa dã quỳ và khối nụ hoa được thiết kế bằng kính màu lạ mắt.\n• LANGBIANG LAND Trú ngụ dưới chân núi Langbiang yên bình – nơi mang đậm giá trị văn hóa thiêng liêng của người đồng bào K\'Ho. Là điểm tham quan mang giá trị tinh hoa của núi rừng Tây Nguyên, gồm những hạng mục sau:\n- Tham quan Thác hoa đào,\n- Vườn đào lông, vườn dâu Nhật\n- Tham quan Công viên khủng Long\n- Tham quan Vườn thú cưng\n- Trò chơi Trượt phao kỳ thú\n- Trò chơi Trượt máng cầu vồng\n- Trò chơi chạy xe Greenline Luge\n- Tham quan Tượng \"Vũ điệu Langbiangland\" trên hồ vô cực\n- Tham quan Cầu bán nguyệt\n• Quý khách trải nghiệm GIAO LƯU VĂN HOÁ CỒNG CHIÊNG : tiếng chiêng vang vọng, ánh lửa bập bùng. Điệu múa uyển chuyển của các chàng trai, cô gái Tây Nguyên tái hiện lại những nghi lễ linh thiêng, từ lễ cúng bến nước, lễ mừng lúa mới đến những ngày hội của buôn làng.\nTrưa: Quý khách dùng bữa trưa tại nhà hàng. Về khách sạn nghỉ ngơi.\nChiều: Xe đưa Đoàn đi tham quan.\n\n- FRESH GARDEN là một trong những nơi sở hữu cánh đồng hoa đa dạng nhất Đà Lạt. Từ lavender tím mộng mơ, hoa hướng dương rực rỡ, đến cẩm tú cầu, hoa sao nhái…bên cạnh đó còn có nhưng hạng mục hấp dẫn:\n- Cối xay gió và ngôi nhà phủ đầy hoa\n- Hồ vô cực với view rừng thông\nThác nước nhân tạo ảo diệu\n- Cổng trời Châu Âu\nĐộng băng thiên thần….\n- FRESH ZOO được thiết kế theo mô hình sở thú trong nhà, mang đến cho bạn trải nghiệm gần gũi và an toàn khi tương tác với các loài thú. Quý khách sẽ có cơ hội tiếp xúc trực tiếp, cho ăn và chụp ảnh cùng những loài động vật như: ngựa lùn, bò lùn, dê lùn, vẹt, thỏ, chuột lang, sóc Bắc Mỹ, lạc đà Alpaca… \nTối: Quý khách dùng bữa tối tự túc, tự do vui chơi. Quý khách vui chơi với CHỢ ĐÊM ÂM PHỦ, hãy sắm cho mình những chiếc khăn choàng và nón được đan từ những đôi bàn tay tài hoa nhưng không kém phần tỉ mỉ. Xung quanh chợ hoặc các khu phố, dễ dàng bắt gặp hình ảnh quay quần bên nhau cùng ly sữa nóng, chiếc bánh rán nóng hổi. \n\nNGÀY 3 | CHỢ ĐÀ LẠT - MUA SẮM NÔNG SẢN – TP.HCM (Ăn sáng, trưa) \nSáng: Quý khách dùng điểm tâm sáng và làm thủ tục trả phòng.\n\nCHỢ ĐÀ LẠT - Du khách sẽ choáng ngợp bởi hàng ngàn loại rau củ, hàng trăm loại trái cây và đủ các loại hoa khoe sắc.\nTrưa: Đoàn dừng chân dùng bữa trưa tại BẢO LỘC. Tiếp tục hành trình về TP.HCM.\nChiều: Về đến điểm đón ban đầu, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYẾN THAM QUAN BỔ ÍCH!\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00\n',
    '3-4 sao',
    'GIÁ TOUR BAO GỒM:  \n\n- Phương tiện: Xe đời mới, máy lạnh suốt tuyến, phục vụ lịch sự chu đáo, đạt chuẩn du lịch.  \n- Lưu trú: 02 đêm khách sạn 3* (tiêu chuẩn: 02 – 03 – 04 khách/phòng). Quý khách đi nhóm gia đình sẽ ưu tiên ở phòng Family.  \n- Ăn uống: 02 bữa sáng (1 tô + 1 ly) + 02 bữa trưa  \n- Bảo hiểm du lịch nội địa (Không được áp dụng bảo hiểm du lịch cho các trường hợp hợp sau: trên 80 tuổi, tiền sử các bệnh như: tim mạch, đột quỵ, …)  \n- Hướng dẫn viên kinh nghiệm, nhiệt tình, vui vẻ phục vụ đoàn suốt tuyến đi.  \n- Đoàn được phục vụ: Nước suối: 1 chai 500ml/ngày/khách.  \n\nGIÁ TOUR KHÔNG BAO GỒM:  \n\n- Chi phí cá nhân: giặt ủi, điện thoại, thức uống trong các bữa ăn, minibar… và tham quan, vận chuyển ngoài chương trình.  \n- Phụ thu phòng đơn: 700.000 VND  \n- Phụ thu ghế ưu tiên: 100.000 VND/ghế  \n- Vé tham quan Puppy Farm: 100.000 VND/khách  \n- Thức uống tại các điểm café  \n- 01 bữa ăn sáng ngày 1 + 02 bữa ăn tối + 01 bữa trưa ngày về.  \n- Tip (chi phí bồi dưỡng) cho tài xế và hướng dẫn viên.  \n- Thuế VAT  '
),
(
    2,
    'TOUR ĐÀ LẠT 3N2Đ | Langbiang Land 3N2Đ: Hành Trình "Adventure" - Miền Sơn Cước',
    'tour-da-lat-3n2d-langbiang-adventure-mien-son-cuoc',
    'Khám Phá KDL Madagui - Langbiang Land - Thưởng thức ẩm thực núi rừng - Giao lưu văn hóa cồng chiêng',
    1986000.00,
    3,
    'active',
    '2026-04-28 02:37:58',
    '2026-04-30 00:38:19',
    'https://datviettour.com.vn/uploads/images/tay-nguyen/madagui/danh-thang/kdl-madagui-850px.jpg',
    'Tp.Hồ Chí Minh',
    'Xe ghế ngồi',
    'Thứ 6 hàng tuần',
    'Du Lịch Đất Việt',
    'NGÀY 1 | TP.HCM - KDL MADAGUI - QUẢNG TRƯỜNG LÂM VIÊN ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách có mặt tại điểm tập trung. Xe và hướng dẫn viên đón đoàn theo giờ đã hẹn. Chào mừng các thành viên đã đồng hành và gửi những phần quà thiết yếu cho đoàn. Tham gia các trò chơi tập thể trên xe để nhận phần quà hấp dẫn.\n\nĐoàn ăn sáng tại nhà hàng địa phương.\nTrưa: Đến KDL Madagui - Nơi đây có hệ động thực vật vô cùng phong phú. Đến với khu du lịch Madagui bạn sẽ ngỡ ngàng trước một thế giới hoàn toàn khác đầy ắp tiếng chim ca véo von, không gian yên tĩnh sẽ giúp bạn thư giãn và trốn khỏi sự ồn ào, náo nhiệt của thành phố\n\nQuý khách dùng cơm trưa tại nhà hàng. Sau đó Đoàn tự do khám phá Madagui với các hoạt động thú vị như Quý khách tự do tham quan, khám phá, vui chơi các hoạt động khác tại KDL như Leo Núi, Trượt Cỏ, Bóng Lăn, Bóng Rổ, Bóng Đá, Bóng Chuyền, Tennis…tại Khu Liên Hợp Thể Thao, Khám phà Hang Dơi, Hang Tử Thần, Trượt Zipline, Câu Cá Giải Trí, Chèo Thuyền Kayak…(Chi phí các trò chơi tự túc)\nChiều: Tiếp tục hành trình đến Đà Lạt, Đoàn tham quan:\n\nCheck-in Quảng Trường Lâm Viên: Tọa lạc giữa \"trái tim\" của thành phố hoa, hướng ra hồ Xuân Hương, không chỉ mang đến không gian rộng lớn, thoáng mát với nhiều hoạt động giải trí mà Quảng Trường Lâm Viên còn ấn tượng với công trình nghệ thuật khổng lồ với khối bông hoa dã quỳ và khối nụ hoa được thiết kế bằng kính màu lạ mắt. Đừng quên lưu giữ lại cùng gia đình những bức ảnh thú vị này.\nĐoàn nhận phòng khách sạn nghỉ ngơi.\n\nTối: Quý khách tự do khám phá và thưởng thức ẩm thực Đà Lạt trong tiết trời se lạnh và không gian cực thư giãn.\n\nNGÀY 2 | LANGBIANG LAND - GIAO LƯU CỒNG CHIÊNG - DOMAINE DE MARIE - VIỆN SINH HỌC ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách dùng điểm tâm sáng. Bắt đầu hành trình tham quan TP Đà Lạt:\n\nLangbiang Land: Trú ngụ dưới chân núi Langbiang yên bình - nơi mang đậm giá trị văn hóa thiêng liêng của người đồng bào K\'Ho. Là điểm tham quan mang giá trị tinh hoa của núi rừng Tây Nguyên.\n\nTrải nghiệm văn hóa Giao Lưu Văn Hoá Cồng Chiêng: tiếng chiêng vang vọng, ánh lửa bập bùng.\nTrưa: Quý khách dùng bữa trưa tại nhà hàng. Đoàn về lại khách sạn, nghỉ ngơi.\n\nChiều: Đoàn khởi hành tham quan:\n\nNhà Thờ Domaine Da Marie: công trình kiến trúc phong cách châu Âu thế kỷ XVII.\nPhân Viện Sinh Học Đà Lạt: Được xây dựng vào khoảng những năm 50 của thế kỷ trước bởi người Pháp.\nTối: Quý khách dùng bữa tối tự túc, tự do vui chơi tại CHỢ ĐÊM ÂM PHỦ.\n\nNGÀY 3 | CHỢ ĐÀ LẠT - MUA SẮM NÔNG SẢN - TP.HCM ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách dùng điểm tâm sáng và làm thủ tục trả phòng.\n\nChợ Đà Lạt - Du khách sẽ choáng ngợp bởi hàng ngàn loại rau củ, hàng trăm loại trái cây và đủ các loại hoa khoe sắc.\nTrưa: Đoàn dừng chân dùng bữa trưa tại Bảo Lộc. Tiếp tục hành trình về TP.HCM.\n\nChiều: Về đến điểm đón ban đầu, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYỂN THAM QUAN BỔ ÍCH\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00',
    '4 sao',
    'GIÁ TOUR BAO GỒM:  \n\n- Phương tiện: Xe đời mới, máy lạnh suốt tuyến, phục vụ lịch sự chu đáo, đạt chuẩn du lịch.  \n- Lưu trú: 02 đêm khách sạn 3* (tiêu chuẩn: 02 – 03 – 04 khách/phòng). Quý khách đi nhóm gia đình sẽ ưu tiên ở phòng Family.  \n- Ăn uống: 02 bữa sáng (1 tô + 1 ly) + 02 bữa trưa  \n- Bảo hiểm du lịch nội địa (Không được áp dụng bảo hiểm du lịch cho các trường hợp hợp sau: trên 80 tuổi, tiền sử các bệnh như: tim mạch, đột quỵ, …)  \n- Hướng dẫn viên kinh nghiệm, nhiệt tình, vui vẻ phục vụ đoàn suốt tuyến đi.  \n- Đoàn được phục vụ: Nước suối: 1 chai 500ml/ngày/khách.  \n\nGIÁ TOUR KHÔNG BAO GỒM:  \n\n- Chi phí cá nhân: giặt ủi, điện thoại, thức uống trong các bữa ăn, minibar… và tham quan, vận chuyển ngoài chương trình.  \n- Phụ thu phòng đơn: 700.000 VND  \n- Phụ thu ghế ưu tiên: 100.000 VND/ghế  \n- Vé tham quan Puppy Farm: 100.000 VND/khách  \n- Thức uống tại các điểm café  \n- 01 bữa ăn sáng ngày 1 + 02 bữa ăn tối + 01 bữa trưa ngày về.  \n- Tip (chi phí bồi dưỡng) cho tài xế và hướng dẫn viên.  \n- Thuế VAT  '
),
(
    3,
    'TOUR ĐÀ LẠT  4N3Đ: Làng Hoa Vạn Thành - Puppy Farm - Langbiang - Học Viện Don Bosco - Đồi Chè Cầu Đất',
    'tour-da-lat-4n3d-langbiang-puppy-farm-doi-che-cau-dat',
    'Tham quan làng hoa Vạn Thành – Nông trại cún Puppy farm – Langbiang – Khám phá ẩm thực Đà Lạt',
    2590000.00,
    4,
    'active',
    '2026-04-28 02:37:58',
    '2026-04-30 00:38:11',
    'https://samtenhills.vn/wp-content/uploads/2024/01/dai-bao-thap-kinh-luan-lon-nhat-the-gioi.jpg',
    'Tp.Hồ Chí Minh',
    'Xe du lịch đời mới',
    'Thứ 2 &5 hàng tuần',
    'Vietourist',
    'NGÀY 1 | SÀI GÒN – ĐÀ LẠT ( Ăn Sáng, Trưa) \n\nSáng: Xe và hướng dẫn viên công ty Vietourist đón quý khách tại điểm hẹn khởi hành đi Đà Lạt.\n\nĐoàn ăn sáng tại khu vực Đồng Nai. Sau bữa sáng, Xe theo quốc lộ 20 đến với Đà Lạt. Trên xe Quý khách cùng hướng dẫn viên tìm hiểu về lịch sử, văn hóa từng vùng đất mà đoàn đi qua, tham gia các trò chơi vui nhộn cùng nhiều phần quà hấp dẫn.\n\nTrưa: Đoàn dùng cơm trưa tại Bảo Lộc. \n\nChiều: Đến với thác Pongour, cách quốc lộ 20 khoảng 7km. Thác Pongour Đà Lạt là thác nước nổi tiếng khu vực Tây Nguyên.\n\nĐến Đà Lạt, nhận phòng khách sạn nghỉ ngơi.\n\nTối: Quý khách tự túc dùng cơm tối và tự do dạo phố đêm Đà Lạt.\n\nNGÀY 2 | LÀNG HOA VẠN THÀNH – PUPPY FARM - LANGBIANG ( Ăn Sáng, Trưa, Tối) \n\nSáng: Sau bữa sáng tại khách sạn, đoàn bắt đầu hành trình tham quan Đà lạt.\n\nLàng Hoa Vạn Thành: Nói đến hoa, chúng ta không thể không nhắc đến làng hoa Vạn Thành – một làng trồng hoa truyền thống đã làm rạng danh thương hiệu hoa Đà Lạt.\n\nPuppy farm: là một địa điểm chiếm được sự yêu mến của rất nhiều người, bởi đây là ngôi nhà chung của hơn 150 chú chó.\n\nTrưa: Dùng cơm trưa tại nhà hàng. Về khách sạn nghỉ ngơi.\n\nChiều: Đoàn tiếp tục hành trình tham quan Langbiang.\n\nTối: Đoàn dùng bữa tối tại nhà hàng địa phương và tham gia buổi lễ giao lưu cồng chiêng Đà Lạt.\n\nNGÀY 3 | DINH I – HỒ TUYỀN LÂM - ĐỒI CHÈ CẦU ĐẤT ( Ăn Sáng, Trưa) \n\nSáng: Quý khách dùng bữa sáng tại khách sạn.\n\nDinh III Đà Lạt hay còn được gọi là Dinh Bảo Đại – một dinh thự sang trọng, mang đậm bản sắc châu Âu giữa lòng những đồi thông xanh.\n\nDon Bosco Đà Lạt hay tên đầy đủ hơn là Học viện Don Bosco Đà Lạt được thành lập vào năm 1971.\n\nTrưa: Dùng cơm trưa tại nhà hàng địa phương.\n\nChiều: Đồi Chè Cầu Đất Farm Đà Lạt: là địa điểm du lịch sinh thái nổi tiếng.\n\nXe và HDV đưa đoàn quay lại trung tâm Đà Lạt, dùng cơm tối tại nhà hàng địa phương.\n\nNGÀY 4 | QUẢNG TRƯỜNG LÂM VIÊN – SÀI GÒN ( Ăn Sáng, Trưa)\n\nSáng: Quý khách sau khi dùng bữa sáng tại khách sạn, làm thủ tục trả phòng. Xe đưa Quý khách đến Quảng trường Lâm Viên.\n\nVề đến TP.Bảo Lộc, Quý khách dùng cơm trưa, thưởng thức đặc sản địa phương.\n\nVề đến TP.HCM Kết thúc chuyến đi chia tay và hẹn gặp lại quý khách.',
    '3 sao',
    'GIÁ TOUR BAO GỒM:\n- Xe tham quan (xe 29 chỗ, 35 chỗ, 45 chỗ) tùy theo số lượng khách thực tế trên chuyến đi.\n- Ăn các bữa ăn theo chương trình.\n- KHÁCH SẠN: Tiêu chuẩn 2, 3 khách/ phòng. 3 SAO: DALLAS, ELC LUXURY, ... Hoặc tương đương\n- Hướng dẫn viên tiếng Việt nhiệt tình phục vụ đoàn ăn nghỉ suốt tuyến.\n- Vé tham quan theo chương trình.\n- Nước uống trên xe 01 chai/khách/ngày.\n- Tặng 01 mũ Du Lịch Việt.\n- Bảo hiểm trọn tour mức bồi thường cao nhất 100,000,000đ/trường hợp. Không áp dụng cho khách từ 75 tuổi trở lên, phụ nữ mang thai.\n- Thuế VAT.\nKHÔNG BAO GỒM:\n- Vé tham quan, café, vận chuyển không bao gồm trong chương trình.\n- 02 bữa tối như chương trình.\n- Tiền Tip hướng dẫn viên và tài xế.\n- Phụ phí phòng đơn, phụ phí người nước ngoài.'
),
(
    4,
    'TOUR ĐÀ LẠT 3N3Đ | Quảng Trường Lâm Viên - Happy Hill - Puppy Farm',
    'ha-noi-ha-long-4n3d',
    'Tour kết hợp Hà Nội phố cổ và du thuyền Vịnh Hạ Long.',
    999000.00,
    4,
    'active',
    '2026-04-28 02:37:58',
    '2026-04-30 00:35:54',
    'https://statics.vinpearl.com/du-lich-ha-long-2_1632635256.jpg',
    'Tp.Hồ Chí Minh',
    'Xe du lịch',
    'Tối thứ 5 hàng tuần',
    'Du lịch Việt',
    'NGÀY 1 | ĐÀ LẠT – QUẢNG TRƯỜNG LÂM VIÊN ĐÀ LẠT – HAPPY HILL COFFEE   (Không bao gồm ăn uống) \n05h00 Sáng: Đến Đà Lạt, đoàn vệ sinh cá nhân và tự túc dùng điểm tâm sáng. \nĐoàn checkin Quảng Trường Lâm Viên Đà Lạt.\nXe đưa đoàn đến check in tại Happy Hill Coffee (Tự túc chi phí vé tham quan và đồ uống).\n\nBuổi trưa: Quay về Trung tâm TP Đà Lạt, nhận phòng khách sạn nghỉ ngơi.\nChiều: Quý khách tự do vi vu khám phá Thành phố Đà Lạt mộng mơ.\nTối: Xe đưa Quý Khách tới Chợ Đêm Âm Phủ, tự do tham quan và khám phá Đà Lạt về đêm.\n\nNGÀY 2 | ĐÀ LẠT - PUPPY FARM (Ăn sáng)\nSáng: Quý khách dùng điểm tâm sáng. Xe đưa đoàn tới PUPPY FARM (Tự túc chi phí vé tham quan).\n\nTrưa: Quý khách về lại khách sạn nghỉ ngơi.\nChiều: Quý khách tự do khám phá Thành phố Đà Lạt.\nTối: Nghỉ đêm tại Đà Lạt.\n\nNGÀY 3 | CHỢ ĐÀ LẠT – TP. HCM (Ăn sáng)\nSáng: Quý khách dùng điểm tâm sáng và làm thủ tục trả phòng.\nĐoàn ghé CHỢ ĐÀ LẠT mua sắm đặc sản.\nTrưa: Đoàn dừng chân tại BẢO LỘC. Quý Khách tự túc bữa trưa.\nChiều: Đến TP.HCM. Kết thúc chuyến đi, chia tay đoàn và hẹn gặp lại Quý khách.',
    '3 sao',
    'GIÁ TOUR BAO GỒM:  \n\n- Phương tiện: Xe đời mới, máy lạnh suốt tuyến, phục vụ lịch sự chu đáo, đạt chuẩn du lịch.  \n- Lưu trú: 02 đêm khách sạn 3* (tiêu chuẩn: 02 – 03 – 04 khách/phòng).  \n- Ăn uống: 02 bữa sáng (1 tô + 1 ly)  \n- Bảo hiểm du lịch nội địa.  \n- Hướng dẫn viên kinh nghiệm, nhiệt tình, vui vẻ phục vụ đoàn suốt tuyến đi.  \n- Nước suối: 1 chai 500ml/ngày/khách.  \n\nGIÁ TOUR KHÔNG BAO GỒM:  \n\n- Chi phí cá nhân: giặt ủi, điện thoại, thức uống trong các bữa ăn, minibar…  \n- Phụ thu phòng đơn: 700.000 VND  \n- Vé tham quan Puppy Farm: 100.000 VND/khách  \n- Bữa trưa và tối tự túc.  \n- Tip cho tài xế và hướng dẫn viên.  \n- Thuế VAT  '
),
(
    5,
    'TOUR NHA TRANG 3N2Đ | "Trốn Thế Giới" | Hòn Tằm Escape',
    'tour-nha-trang-3n2d-tron-the-gioi-hon-tam-escape',
    'Tắm bùn Hòn Tằm - Khám phá Vinpearl Harbour - Tham quan Chùa Long Sơn',
    2086000.00,
    3,
    'active',
    '2026-04-28 02:37:58',
    '2026-04-30 00:29:11',
    'https://datviettour.com.vn/uploads/images/mien-trung/nha-trang/hinh-danh-thang/850px/hon-tam-850px.jpg',
    'Tp.Hồ Chí Minh',
    'Xe ghế ngồi',
    'Thứ 6 hàng tuần',
    'Du Lịch Đất Việt',
    'NGÀY 1 | TP.HCM - KHÁNH HÒA - CHÙA LONG SƠN ( • Ăn sáng • Ăn trưa • Ăn tối )\n\nSáng: Xe và Hướng dẫn viên đón quý khách tại điểm hẹn. Đến với địa phận tỉnh Đồng Nai, quý khách dừng chân dùng điểm tâm sáng.\n\nTrưa: Đến với Bình Thuận, Quý khách dừng chân dùng bữa trưa. Đoàn dừng chân và chụp hình với Biển Cà Ná và Cánh Đồng Muối Cà Ná.\n\nChiều: Đoàn khởi hành tham quan Chùa Long Sơn và Bảo Tàng Ngọc Trai Hoàng Gia.\n\nTối: Đoàn dùng bữa tối tại nhà hàng. Tự do khám phá đường TRẦN PHÚ hoặc CHỢ ĐÊM.\n\nNGÀY 2 | HÒN TẰM - TỰ DO TẮM BIỂN ( • Ăn sáng • Ăn trưa )\n\nSáng: Đoàn dùng bữa sáng tại Khách sạn. Đoàn có mặt tại Cảng Vĩnh Trường, tàu đưa quý khách khám phá Vịnh Nha Trang.\n\nTrải nghiệm Tắm Bùn Hòn Tằm - Thư Giãn Giữa Thiên Nhiên Biển Đảo.\n\nTrưa: Kết thúc chuyến trải nghiệm, Tàu đưa Quý khách về lại đất liền. Đoàn dùng bữa trưa tại Nhà hàng. Về khách sạn nghỉ ngơi.\n\nChiều: Quý khách có thể nghỉ ngơi hoặc tự túc khám phá Vinpearl Harbour.\n\nTối: Tự do thưởng thức ẩm thực và khám phá Nha Trang về đêm.\n\nNGÀY 3 | CHIA TAY NHA TRANG - VƯỜN NHO - TP.HCM ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách dùng điểm tâm sáng. Đoàn làm thủ tục trả phòng. Đoàn di chuyển về Ninh Thuận tham quan Vườn Nho.\n\nTrưa: Quý khách dùng bữa trưa tại nhà hàng. Đoàn khởi hành về TP.HCM.\n\nChiều: Về đến TPHCM, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYẾN THAM QUAN BỔ ÍCH!\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của Resort/ khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00.',
    '3-5 sao',
    'TIÊU CHUẨN DỊCH VỤ\nXe du lịch 16 - 45 chỗ đời mới máy lạnh, ghế bật, phục vụ đưa đón đoàn suốt tuyến.\nLƯU TRÚ: Tiêu chuẩn 2 - 3 khách/phòng.\nTại Nha Trang: 3 sao: Ale, Edele, happylight, Tuấn Vũ…. / 4 sao: Poseidon, Greenbeach, Erica, TND…. / 5 sao: Vesna, TTC, Rigaliagold….\nĂN UỐNG: Nhà hàng địa phương tiêu chuẩn, hợp vệ sinh theo gói dịch vụ.\nHDV: Tiếng Việt thuyết minh và phục vụ Đoàn tham quan theo gói dịch vụ.\nBẢO HIỂM: Bảo hiểm du lịch theo quy định 50.000.000vnđ/vụ\nTHAM QUAN: Bao gồm phí vào cổng tại các điểm tham quan theo gói dịch vụ.\nPHỤC VỤ: Nón du lịch, khăn ướt, nước đóng chai\nVAT: Theo quy định\nDỊCH VỤ CHƯA BAO GỒM\nGói VIP 1: Tự túc bữa ăn tối tại Nha Trang.\nGói VIP 2: Tự túc bữa ăn tối tại Nha Trang - Vé cáp treo Vinpearl Harbour (Giá tham khảo: 200.000vnđ/vé)\nTiền TIP cho HDV + Tài xế địa phương\nChi phí cá nhân ngoài chương trình: giặt ủi, điện thoại, minibar…\nQUY ĐỊNH TRẺ EM:\nDưới 05 tuổi: Miễn phí. Hai người lớn chỉ được kèm theo 01 trẻ, từ trẻ thứ hai tính 50% giá tour.\nTừ 05 - dưới 10 tuổi: 70% giá tour người lớn, ngủ ghép với gia đình.\nTừ 10 tuổi trở lên: giá tour như người lớn.\nĐIỀU KIỆN HỦY TOUR:\nHủy tour sau khi đăng kí: 30% giá tour.\nTrước ngày đi 20 Ngày: 50% giá tour.\nTrước ngày đi 10-19 ngày: 75% giá tour.\nTrước ngày đi 0-10 Ngày: 100% giá tour.'
),
(
    6,
    'TOUR MIỀN BẮC MÙA HÈ 6N5Đ | HÀ NỘI - HẠ LONG - NINH BÌNH - SAPA',
    'phu-qu',
    'Tham quan thủ đô Hà Nội - Checkin đỉnh Fansipan – Ngoạn cảnh Vịnh Hạ Long – Xuôi thuyền dọc theo giữa cánh đồng lúa thăm Khu du lịch Tràng An - Viếng chùa Bái Đính – ngôi chùa có nhiều kỷ lục nhất Việt Nam.',
    11399000.00,
    6,
    'active',
    '2026-04-28 02:37:58',
    '2026-04-30 02:38:18',
    'https://samtenhills.vn/wp-content/uploads/2024/01/dai-bao-thap-kinh-luan-lon-nhat-the-gioi.jpg',
    'Tp. Hồ Chí Minh',
    'Máy bay khứ hồi & xe du lịch đời mới',
    'Thứ 3 hàng tuần',
    'Du lịch Việt',
    'NGÀY 1 | TP.HCM – HÀ NỘI (Ăn trưa, chiều)\nSáng: Quý khách có mặt tại ga quốc nội, sân bay Tân Sơn Nhất trước giờ bay ít nhất ba tiếng.\n\nĐại diện công ty Du Lịch Việt đón và hỗ trợ Quý Khách làm thủ tục đón chuyến bay đi Hà Nội.\nĐến sân bay Nội Bài, Hướng Dẫn Viên đón đoàn, tham quan: Lăng Chủ tịch Hồ Chí Minh (trừ thứ 2, thứ 6 bảo trì Lăng), Phủ Chủ Tịch, ao cá, nhà sàn Bác Hồ, Chùa Một Cột, Bảo Tàng Hồ Chí Minh.\n\nTrưa: Dùng bữa trưa.\n\nĐoàn tiếp tục tham quan chùa Trấn Quốc, Hồ Tây, Hồ Trúc Bạch, Hồ Hoàn Kiếm, Bảo tàng quân sự Việt Nam.\n\nTối: Dùng bữa tối. Nghỉ đêm tại Hà Nội.\n\nNGÀY 2 | HÀ NỘI – LÀO CAI – SAPA (Ăn sáng, trưa, chiều)\nSáng: Dùng buffet sáng tại khách sạn.\n\nĐoàn khởi hành đến Lào Cai trên con đường cao tốc dài nhất Việt Nam.\nTrưa: Dùng bữa trưa.\n\nĐoàn tiếp tục đến thị trấn vùng cao Sapa. Thăm bản Cát Cát, tìm hiểu nghề dệt nhuộm của dân tộc H\'Mông.\n\nTối: Dùng bữa tối. Nghỉ đêm tại Sapa.\n\nNGÀY 3 | FANSIPAN – HÀ NỘI (Ăn sáng, trưa, chiều tự túc)\nSáng: Dùng buffet sáng tại khách sạn.\n\nĐoàn khởi hành tham quan chinh phục Fansipan, ngọn núi cao nhất Việt Nam (3.143m). Du khách chinh phục bằng hệ thống cáp treo Fansipan SaPa dài 6,2km đạt 2 kỷ lục Guinness thế giới (chi phí cáp treo tự túc).\n\nTrưa: Dùng bữa trưa. Đoàn trở về Hà Nội.\n\nTối: Nghỉ đêm tại Hà Nội. Quý khách tự do khám phá ẩm thực Hà Nội.\n\nNGÀY 4 | HÀ NỘI – YÊN TỬ - HẠ LONG (Ăn Sáng, trưa, chiều)\nSáng: Dùng buffet sáng tại khách sạn.\n\nHướng dẫn viên đón đoàn Khởi hành đến Hạ Long, trên đường dừng chân tham quan núi Yên Tử.\n\nTrưa: Dùng cơm trưa.\n\nQuý khách lên núi bằng cáp treo (chi phí tự túc), tham quan chùa Hoa Yên, Bảo Tháp, Trúc Lâm Tam Tổ, Hàng Tùng 700 tuổi. Đoàn khởi hành về thành phố Hạ Long.\n\nChiều: Dùng bữa chiều. Nghỉ đêm tại Hạ Long.\n\nNGÀY 5 | HẠ LONG – NINH BÌNH (Ăn sáng, trưa, chiều)\nSáng: Dùng buffet sáng tại khách sạn.\n\nĐoàn xuống thuyền ngoạn cảnh Vịnh Hạ Long – Di sản thiên nhiên thế giới.\n\nTrưa: Dùng bữa trưa.\n\nKhởi hành đi Ninh Bình, tham quan danh thắng Tràng An và chùa Bái Đính.\n\nTối: Dùng bữa tối. Nghỉ đêm tại Ninh Bình.\n\nNGÀY 6 | NINH BÌNH – HÀ NỘI – TP.HCM (Ăn sáng, trưa)\nSáng: Dùng buffet sáng tại khách sạn.\n\nĐoàn xuôi thuyền đi dọc theo suối giữa cánh đồng lúa thăm Khu du lịch Tràng An.\n\nTrưa: Dùng bữa trưa. Đoàn trở về Hà Nội.\n\nHướng dẫn viên tiễn đoàn ra sân bay Nội Bài đón chuyến bay về TP.HCM. Kết thúc chương trình tham quan, chia tay và hẹn gặp lại.',
    '3-4 sao',
    'GIÁ TOUR BAO GỒM:\nVé máy bay khứ hồi TP.HCM – HÀ NỘI – TP.HCM\nXe tham quan (xe 16 chỗ, 29 chỗ, 35 chỗ, 45 chỗ) tùy theo số lượng khách.\nKhách sạn tiêu chuẩn 2 khách người lớn/phòng.\nKhách sạn 3-4 sao tại Hà Nội, Sapa, Hạ Long, Ninh Bình hoặc tương đương.\nĂn uống theo chương trình: buffet sáng tại khách sạn + bữa chính tiêu chuẩn.\nVé tham quan theo chương trình.\nHướng dẫn viên tiếng Việt vui vẻ nhiệt tình suốt chuyến đi.\nBảo hiểm với mức bồi thường tối đa 100.000.000 đồng/trường hợp. Không áp dụng cho khách từ 80 tuổi trở lên.\nQuà tặng: nón du lịch Việt, nước, khăn lạnh.\nThuế VAT.\nKHÔNG BAO GỒM:\nBia hay nước ngọt trong các bữa ăn.\nTham quan ngoài chương trình.\nChi phí cá nhân: điện thoại, giặt ủi…\nVé cáp treo Fansipan, Yên Tử.\nTiền Tip hướng dẫn viên và tài xế.'
),
(
    7,
    'TOUR PHÚ YÊN - NHA TRANG 3N3Đ | Về Nơi Hoa Vàng - Đến Nơi Biển Xanh',
    'phu-qu1',
    'Khám phá Vinpearl Harbour - Tham quan Gành Đá Đĩa - Check-in Tháp Nghinh Phong',
    2586000.00,
    3,
    'active',
    '2026-04-29 17:33:58',
    '2026-04-30 02:38:26',
    'https://datviettour.com.vn/uploads/images/mien-trung/phu-yen/danh-thang/850px/phu-yen-2.jpg',
    'Tp.Hồ Chí Minh',
    'Xe ghế ngồi',
    'Thứ 5 hàng tuần',
    'Du lịch Đất Việt',
    'ĐÊM 1 | TP. HCM - PHÚ YÊN\nTối: Quý khách có mặt tại điểm tập trung. Xe và hướng dẫn viên đón đoàn theo giờ đã hẹn. Quý khách nghỉ đêm trên xe.\n\nNGÀY 1 | PHÚ YÊN - GÀNH ĐÁ ĐĨA - NHÀ THỜ MẰNG LĂNG - THÁP NGHINH PHONG ( • Ăn sáng • Ăn trưa • Ăn tối )\n\nSáng: Quý khách dừng chân vệ sinh cá nhân và dùng bữa sáng tại nhà hàng. Tiếp tục chương trình đoàn khởi hành tham quan Gành Đá Đĩa và Nhà Thờ Mằng Lăng.\n\nTrưa: Ngoạn cảnh Đầm Ô Loan - Đập Tam Giang. Đoàn dùng bữa trưa tại khu vực Đầm Ô Loan.\n\nChiều: Quý khách tham quan Quảng Trường Nghinh Phong.\n\nTối: Đoàn dùng bữa tối tại nhà hàng. Tự do khám phá Chợ Đêm Tuy Hòa.\n\nNGÀY 2 | PHÚ YÊN - NHA TRANG - NHÀ HÁT ĐÓ - VINPEARL HARBOUR ( • Ăn sáng • Ăn trưa )\n\nSáng: Đoàn dùng bữa sáng và làm thủ tục trả phòng, đoàn khởi hành đi Nha Trang. Tham quan Nhà Hát Đó, Bảo Tàng Ngọc Trai Hoàng Gia, Hòn Chồng.\n\nTrưa: Đoàn dùng bữa tại nhà hàng. Đoàn về khách sạn nhận phòng nghỉ ngơi.\n\nChiều: Đoàn tham gia Khám Phá Vinpearl Harbour.\n\nTối: Tự do thưởng thức ẩm thực đặc sản Nha Trang. Xe đón quý khách về khách sạn nghỉ ngơi.\n\nNGÀY 3 | NHA TRANG - TP.HỒ CHÍ MINH ( • Ăn sáng • Ăn trưa )\nSáng: Quý khách dùng điểm tâm sáng tại khách sạn. Làm thủ tục trả phòng, đoàn khởi hành về TP.HCM.\n\nTrưa: Quý khách dùng bữa trưa tại nhà hàng thuận tiện. Khởi hành về TP.HCM.\n\nChiều: Về đến TPHCM, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYẾN THAM QUAN VUI VẺ - BỔ ÍCH\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của Resort/ khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00',
    '3-5 sao',
    'TIÊU CHUẨN DỊCH VỤ\nXe du lịch 16 - 45 chỗ đời mới máy lạnh, ghế bật, phục vụ đưa đón đoàn suốt tuyến.\nLƯU TRÚ: Tiêu chuẩn 2 - 3 khách/phòng.\nTại Nha Trang: 3 sao: Ale, Edele, happylight, Tuấn Vũ…. / 4 sao: Poseidon, Greenbeach, Erica, TND…. / 5 sao: Vesna, TTC, Rigaliagold….\nTại Phú Yên: 3 sao: Green Oasis, Royal khanh …. / 4 sao: Kaya, Wink, Everyday….\nĂN UỐNG: Nhà hàng địa phương tiêu chuẩn, hợp vệ sinh theo gói dịch vụ.\nHDV: Tiếng Việt thuyết minh và phục vụ Đoàn tham quan theo gói dịch vụ.\nBẢO HIỂM: Bảo hiểm du lịch theo quy định 50.000.000vnđ/vụ\nTHAM QUAN: Bao gồm phí vào cổng tại các điểm tham quan theo gói dịch vụ.\nPHỤC VỤ: Nón du lịch, khăn ướt, nước đóng chai\nVAT: Theo quy định\nDỊCH VỤ CHƯA BAO GỒM\nGói VIP 1: Tự túc bữa ăn tối tại Nha Trang.\nGói VIP 2: Tự túc bữa ăn tối tại Nha Trang - Vé cáp treo Vinpearl Harbour (Giá tham khảo: 200.000vnđ/vé)\nTiền TIP cho HDV + Tài xế địa phương\nQUY ĐỊNH TRẺ EM:\nDưới 05 tuổi: Miễn phí. Hai người lớn chỉ được kèm theo 01 trẻ, từ trẻ thứ hai tính 50% giá tour.\nTừ 05 - dưới 10 tuổi: 70% giá tour người lớn, ngủ ghép với gia đình.\nTừ 10 tuổi trở lên: giá tour như người lớn.\nĐIỀU KIỆN HỦY TOUR:\nHủy tour sau khi đăng kí: 30% giá tour.\nTrước ngày đi 20 Ngày: 50% giá tour.\nTrước ngày đi 10-19 ngày: 75% giá tour.\nTrước ngày đi 0-10 Ngày: 100% giá tour.'
),
(
    8,
    'TOUR NHA TRANG 3N2Đ | Mirae Park Bãi Sỏi - Tắm Bùn Khoáng',
    'tour-nha-trang-3n2d-mirae-park-bai-soi-tam-bun-khoang',
    'Check-in Mirae Park Bãi Sỏi - Show Thực Cảnh "Ánh Sáng Huyền Thoại"',
    2286000.00,
    3,
    'active',
    '2026-04-29 17:33:58',
    '2026-04-30 00:10:05',
    'https://datviettour.com.vn/uploads/images/mien-trung/nha-trang/hinh-danh-thang/850px/nha-trang-03-850px.png',
    'Tp.Hồ Chí Minh',
    'Xe du lịch đời mới',
    'Thứ 5,6,7 hàng tuần',
    'Du lịch Đất Việt',
    'NGÀY 1 | TP. HCM - KHÁNH HÒA - TẮM BÙN KHOÁNG ( • Ăn sáng • Ăn trưa • Ăn tối )\n\nSáng: Quý khách có mặt tại điểm tập trung. Xe và hướng dẫn viên đón đoàn theo giờ đã hẹn. Trên đường di chuyển, đoàn dừng chân dùng bữa sáng tại nhà hàng, sau đó tiếp tục lộ trình đi Nha Trang.\n\nTrưa: Đoàn dùng bữa trưa tại nhà hàng. Về khách sạn nhận phòng và nghỉ ngơi tự do.\n\nChiều: Đoàn thư giãn Tắm Bùn Khoáng Tháp Bà (bao vé tắm khoáng).\n\nTối: Đoàn ăn tối nhà hàng. Tự do khám phá Nha Trang về đêm tại đường TRẦN PHÚ hoặc CHỢ ĐÊM.\n\nNGÀY 2 | MIRAE BÃI SỎI - CITY TOUR NHA TRANG ( • Ăn sáng • Ăn trưa )\n\nSáng: Đoàn dùng điểm tâm sáng. Xe đưa Đoàn đến Cảng, Đoàn đi tàu đến với Bãi Sỏi, khám phá Khu Du Lịch Mirae.\n\nTrưa: Đoàn dùng bữa tại nhà hàng.\n\nChiều: Xe đưa đoàn về lại khách sạn nghỉ ngơi tự do. Tham quan Chùa Long Sơn, Nhà Hát Đó, Bảo Tàng Ngọc Trai Hoàng Gia.\n\nTối: Đoàn tự túc ăn tối trải nghiệm ẩm thực Nha Trang.\n\nNGÀY 3 | CHIA TAY NHA TRANG ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách dùng điểm tâm sáng. Làm thủ tục trả phòng.\n\nTrưa: Quý khách dùng bữa trưa tại Cà Ná. Đoàn khởi hành về TP.HCM.\n\nChiều: Về đến TPHCM, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYẾN THAM QUAN BỔ ÍCH!\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của Resort/ khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00.',
    '4 sao',
    'GIÁ TOUR BAO GỒM:\nVẬN CHUYỂN: Xe du lịch 16 - 45 chỗ đời mới máy lạnh, ghế bật, phục vụ đưa đón đoàn suốt tuyến.\nLƯU TRÚ: Tiêu chuẩn 2 - 3 khách/phòng. Hotel 4*: TND Hotel (hoặc tương đương)\nĂN UỐNG: Nhà hàng địa phương tiêu chuẩn, hợp vệ sinh.\nHDV: Tiếng Việt thuyết minh và phục vụ Đoàn tham quan suốt tuyến.\nBẢO HIỂM: Bảo hiểm du lịch theo quy định 50.000.000vnđ/vụ\nTHAM QUAN: Bao gồm phí vào cổng tại các điểm tham quan theo chương trình.\nQUÀ TẶNG: Nón du lịch, khăn ướt, nước đóng chai\nVAT theo quy định\nGIÁ TOUR KHÔNG BAO GỒM:\nTiền TIP cho HDV + Tài xế địa phương\nChi phí cá nhân ngoài chương trình: giặt ủi, điện thoại, minibar…\nTự túc 1 bữa ăn tối.\nVé tắm bùn, thảo dược,… xem show\nQUY ĐỊNH TRẺ EM:\nDưới 05 tuổi: Miễn phí. Hai người lớn chỉ được kèm theo 01 trẻ, từ trẻ thứ hai tính 50% giá tour.\nTừ 05 - dưới 10 tuổi: giá theo quy định, ngủ ghép với gia đình.\nTừ 10 tuổi trở lên: giá tour như người lớn.\nĐIỀU KIỆN HỦY TOUR:\nViệc huỷ bỏ chuyến đi không được chấp nhận, DO TÍNH CHẤT TOUR KHUYẾN MÃI KÍCH CẦU NGÀY HỘI DU LỊCH HCM 2026.'
);
INSERT INTO `orders`(
    `id`,
    `user_id`,
    `tour_id`,
    `order_date`,
    `quantity`,
    `total_price`,
    `status`
)
VALUES(
    1,
    2,
    1,
    '2026-04-18 02:37:58',
    2,
    7980000.00,
    'confirmed'
),
(
    2,
    2,
    3,
    '2026-04-23 02:37:58',
    3,
    10770000.00,
    'pending'
),
(
    3,
    3,
    2,
    '2026-04-21 02:37:58',
    1,
    4590000.00,
    'cancelled'
),
(
    4,
    4,
    4,
    '2026-04-26 02:37:58',
    4,
    22760000.00,
    'confirmed'
),
(
    5,
    NULL,
    1,
    '2026-04-27 02:37:58',
    2,
    7980000.00,
    'pending'
),
(
    6,
    NULL,
    2,
    '2026-04-28 04:07:56',
    1,
    4590000.00,
    'pending'
),
(
    7,
    5,
    1,
    '2026-04-28 04:08:19',
    1,
    2586000.00,
    'pending'
),
(
    8,
    5,
    1,
    '2026-04-28 11:50:02',
    1,
    2586000.00,
    'pending'
),
(
    9,
    NULL,
    1,
    '2026-04-28 13:04:53',
    1,
    2586000.00,
    'pending'
);
INSERT INTO `my_orders`(
    `id`,
    `user_id`,
    `order_id`,
    `tour_id`,
    `total_quantity`,
    `adult_qty`,
    `child_qty`,
    `infant_qty`,
    `depart_date`,
    `total_price`,
    `status`,
    `note`,
    `created_at`
)
VALUES(
    1,
    6,
    43,
    7,
    1,
    1,
    0,
    0,
    '0000-00-00',
    2586000,
    'confirmed',
    'Người lớn: 1 | Trẻ em: 0 | Em bé: 0 | Ngày đi: ',
    '2026-04-29 21:13:16'
),
(
    2,
    6,
    45,
    8,
    2,
    1,
    1,
    0,
    '2026-05-07',
    4572000,
    'confirmed',
    'Người lớn: 1 | Trẻ em: 1 | Em bé: 0 | Ngày đi: 2026-05-07',
    '2026-04-29 21:38:07'
),
(
    3,
    6,
    55,
    1,
    1,
    1,
    0,
    0,
    '0000-00-00',
    2586000,
    'confirmed',
    'Người lớn: 1 | Trẻ em: 0 | Em bé: 0',
    '2026-04-29 22:18:00'
);
INSERT INTO `post_categories`(`id`, `name`, `slug`)
VALUES(1, 'Tin tức', 'tin-tuc'),
(
    2,
    'Cẩm nang du lịch',
    'cam-nang-du-lich'
),
(3, 'Khuyến mãi', 'khuyen-mai');
INSERT INTO `posts`(
    `id`,
    `category_id`,
    `author_id`,
    `title`,
    `slug`,
    `summary`,
    `content`,
    `status`,
    `published_at`,
    `created_at`
)
VALUES(
    1,
    1,
    1,
    'Khai trương website MyWeb Tour với nhiều ưu đãi',
    'khai-truong-website-myweb-tour',
    'Giới thiệu website du lịch MyWeb Tour và các chương trình ưu đãi khai trương.',
    'Nội dung chi tiết về lễ khai trương, các chương trình giảm giá, voucher cho khách hàng mới...',
    'published',
    '2026-04-13 02:37:58',
    '2026-04-12 02:37:58'
),
(
    2,
    2,
    1,
    '5 kinh nghiệm du lịch Đà Lạt tự túc',
    '5-kinh-nghiem-du-lich-da-lat-tu-tuc',
    'Tổng hợp các kinh nghiệm hữu ích khi tự đi Đà Lạt.',
    'Bài viết chia sẻ về thời điểm nên đi, phương tiện di chuyển, địa điểm tham quan và các món ăn nên thử...',
    'published',
    '2026-04-16 02:37:58',
    '2026-04-15 02:37:58'
),
(
    3,
    2,
    1,
    'Gợi ý lịch trình 3 ngày tại Đà Nẵng',
    'goi-y-lich-trinh-3-ngay-tai-da-nang',
    'Lịch trình tham quan Đà Nẵng cơ bản trong 3 ngày.',
    'Ngày 1: Bán đảo Sơn Trà, biển Mỹ Khê. Ngày 2: Bà Nà Hills. Ngày 3: Hội An cổ kính...',
    'published',
    '2026-04-19 02:37:58',
    '2026-04-18 02:37:58'
),
(
    4,
    3,
    1,
    'Giảm giá 20% tour Nha Trang trong tháng này',
    'giam-gia-20-tour-nha-trang-thang-nay',
    'Chương trình khuyến mãi dành cho tour Nha Trang biển xanh 4N3Đ.',
    'Chi tiết điều kiện áp dụng, thời gian đặt tour và thời gian khởi hành trong đợt khuyến mãi...',
    'published',
    '2026-04-22 02:37:58',
    '2026-04-21 02:37:58'
),
(
    5,
    1,
    1,
    'Thông báo cập nhật chính sách hoàn hủy tour',
    'cap-nhat-chinh-sach-hoan-huy-tour',
    'Giới thiệu các thay đổi trong quy định hoàn/huỷ tour.',
    'Nội dung giải thích rõ các mức phí, thời hạn báo huỷ, và các trường hợp đặc biệt...',
    'draft',
    NULL,
    '2026-04-25 02:37:58'
);
INSERT INTO `post_comments`(
    `id`,
    `post_id`,
    `user_id`,
    `content`,
    `rating`,
    `status`,
    `created_at`
)
VALUES(
    1,
    2,
    2,
    'Bài viết rất hữu ích, mình chuẩn bị đi Đà Lạt nên áp dụng luôn.',
    5,
    'approved',
    '2026-04-17 02:37:58'
),
(
    2,
    3,
    3,
    'Lịch trình gợi ý khá hợp lý, nhưng có thể thêm một vài điểm ăn uống.',
    4,
    'approved',
    '2026-04-20 02:37:58'
),
(
    3,
    4,
    4,
    'Mình muốn hỏi thời gian áp dụng khuyến mãi là đến ngày nào?',
    NULL,
    'pending',
    '2026-04-23 02:37:58'
),
(
    4,
    1,
    2,
    'Chúc mừng MyWeb Tour khai trương, mong sớm có thêm nhiều tour mới.',
    5,
    'approved',
    '2026-04-14 02:37:58'
),
(
    5,
    2,
    4,
    'Mình thấy nên bổ sung thêm gợi ý khách sạn giá rẻ.',
    4,
    'approved',
    '2026-04-18 02:37:58'
);
INSERT INTO `tour_comments`(
    `id`,
    `tour_id`,
    `user_id`,
    `content`,
    `rating`,
    `status`,
    `created_at`
)
VALUES(
    1,
    1,
    2,
    'Tour Đà Nẵng - Hội An rất vui, HDV nhiệt tình, lịch trình hợp lý.',
    5,
    'approved',
    '2026-04-20 02:37:58'
),
(
    2,
    1,
    3,
    'Khách sạn ổn, nhưng đồ ăn chưa đa dạng lắm.',
    4,
    'approved',
    '2026-04-21 02:37:58'
),
(
    3,
    3,
    4,
    'Đà Lạt đẹp, thời tiết dễ chịu. Sẽ quay lại cùng MyWeb Tour.',
    5,
    'approved',
    '2026-04-24 02:37:58'
),
(
    4,
    2,
    2,
    'Mình muốn hỏi thêm về chi phí phát sinh trong tour Nha Trang?',
    NULL,
    'pending',
    '2026-04-27 02:37:58'
),
(
    5,
    4,
    3,
    'Lịch trình Hà Nội - Hạ Long khá dày, hơi mệt với người lớn tuổi.',
    3,
    'approved',
    '2026-04-26 02:37:58'
);