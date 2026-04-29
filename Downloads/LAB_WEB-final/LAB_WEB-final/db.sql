-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 30, 2026 lúc 12:23 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

CREATE DATABASE IF NOT EXISTS GoTour;

USE GoTour;

ALTER DATABASE GoTour
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `gotour`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `about_content`
--

CREATE TABLE `about_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `about_content`
--

INSERT INTO `about_content` (`id`, `title`, `content`, `mission`, `vision`, `image_url`, `updated_at`, `updated_by`) VALUES
(1, 'Về chúng tôi - GoTour', 'GoTour là công ty du lịch hàng đầu tại Việt Nam, chuyên cung cấp các tour du lịch trong nước và quốc tế với chất lượng dịch vụ cao cấp. Với đội ngũ nhân viên giàu kinh nghiệm và tận tâm, chúng tôi cam kết mang đến cho khách hàng những trải nghiệm du lịch tuyệt vời nhất.', 'Sứ mệnh của chúng tôi là kết nối mọi người với thế giới thông qua những chuyến du lịch ý nghĩa, an toàn và đáng nhớ.', 'Trở thành công ty du lịch hàng đầu khu vực Đông Nam Á, mang đến trải nghiệm du lịch đẳng cấp thế giới cho mọi khách hàng.', 'assets/images/about-us.jpg', '2026-04-27 12:37:58', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `region` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `branches`
--

INSERT INTO `branches` (`id`, `region`, `name`, `address`, `phone`, `email`) VALUES
(1, 'hcm', 'Cơ sở 1', '268 Lý Thường Kiệt, Phường 14, Quận 10, TP. Hồ Chí Minh.', '(84-24) 3866 8999', '268LyThuongKieu@vietravel.com'),
(2, 'hcm', 'Cơ sở 2', 'Khu phố Tân Lập, Phường Đông Hòa, TP.HCM', '(84-24) 3535 8709', 'LangDaiHoc@vietravel.com'),
(3, 'mien-bac', 'Cơ sở chính', 'Số 1 Đại Cồ Việt, Phường Bạch Mai, Thành phố Hà Nội', '(84-24) 3869 2942', 'hanoi@vietravel.com'),
(4, 'mien-trung', 'Cơ sở chính', '54 Nguyễn Lương Bằng, phường Hòa Khánh Bắc, quận Liên Chiểu, thành phố Đà Nẵng.', '(84-236) 3863 544', 'danang@vietravel.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email_support` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `company`
--

INSERT INTO `company` (`id`, `name`, `logo`, `website`, `email_support`) VALUES
(1, 'GoTour', 'assets/images/logo.png', NULL, 'support@gotour.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') NOT NULL DEFAULT 'new',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `handled_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `status`, `created_at`, `handled_by`) VALUES
(1, 'Nguyễn Văn A', 'nguyenvana@example.com', 'Hỏi về lịch khởi hành tour Đà Nẵng', 'Cho mình hỏi tour Đà Nẵng - Hội An 3N2Đ có lịch khởi hành gần nhất là khi nào?', 'replied', '2026-04-22 02:37:58', 1),
(2, 'Trần Thị B', 'tranthib@example.com', 'Tư vấn tour cho gia đình 4 người', 'Gia đình mình có 2 người lớn, 2 trẻ em muốn đi biển trong tháng 7, nhờ tư vấn giúp.', 'read', '2026-04-25 02:37:58', 1),
(3, 'Lê Minh C', 'leminhc@example.com', 'Góp ý về giao diện website', 'Mình góp ý là nên có bộ lọc theo giá và theo thời gian khởi hành để dễ tìm tour hơn.', 'new', '2026-04-27 02:37:58', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT 'Chung',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `display_order`, `is_active`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'Làm thế nào để đặt tour du lịch?', 'Bạn có thể đặt tour trực tiếp trên website của chúng tôi bằng cách chọn tour mong muốn, điền thông tin và thanh toán. Hoặc liên hệ hotline: 1900-xxxx để được tư vấn và hỗ trợ đặt tour.', 'Đặt tour', 1, 1, '2026-04-27 12:37:58', '2026-04-27 12:37:58', 1),
(2, 'Tôi có thể hủy tour đã đặt không?', 'Có, bạn có thể hủy tour theo chính sách hủy của chúng tôi. Tùy vào thời gian hủy, bạn sẽ được hoàn lại một phần hoặc toàn bộ chi phí. Vui lòng xem chi tiết trong mục Điều khoản và Điều kiện.', 'Đặt tour', 2, 1, '2026-04-27 12:37:58', '2026-04-27 12:37:58', 1),
(3, 'Các hình thức thanh toán nào được chấp nhận?', 'Chúng tôi chấp nhận thanh toán qua thẻ ATM, thẻ tín dụng (Visa/Mastercard), chuyển khoản ngân hàng, ví điện tử (MoMo, ZaloPay) và thanh toán trực tiếp tại văn phòng.', 'Thanh toán', 3, 1, '2026-04-27 12:37:58', '2026-04-27 12:37:58', 1),
(4, 'Tour có bao gồm bảo hiểm du lịch không?', 'Có, tất cả các tour của chúng tôi đều bao gồm bảo hiểm du lịch cơ bản. Bạn cũng có thể mua thêm gói bảo hiểm mở rộng nếu muốn.', 'Dịch vụ', 4, 1, '2026-04-27 12:37:58', '2026-04-27 12:37:58', 1),
(5, 'Tôi cần chuẩn bị gì trước khi đi tour?', 'Bạn cần chuẩn bị giấy tờ tùy thân (CMND/Passport), quần áo phù hợp với thời tiết, thuốc men cá nhân và một số vật dụng cần thiết khác. Chúng tôi sẽ gửi danh sách chi tiết sau khi bạn đặt tour.', 'Chuẩn bị', 5, 1, '2026-04-27 12:37:58', '2026-04-27 12:37:58', 1),
(6, 'Trẻ em có được giảm giá không?', 'Có, trẻ em dưới 5 tuổi được miễn phí (không tính ghế riêng), từ 5-10 tuổi được giảm 50% và từ 11 tuổi trở lên tính như người lớn.', 'Giá cả', 6, 1, '2026-04-27 12:37:58', '2026-04-27 12:37:58', 1),
(7, 'Tôi có thể thay đổi lịch trình tour không?', 'Tùy thuộc vào loại tour và thời gian thay đổi, bạn có thể được phép thay đổi lịch trình với một khoản phí nhất định. Vui lòng liên hệ với chúng tôi càng sớm càng tốt.', 'Đặt tour', 7, 1, '2026-04-27 12:37:58', '2026-04-27 12:37:58', 1),
(8, 'Hướng dẫn viên có nói được tiếng Anh không?', 'Có, chúng tôi có hướng dẫn viên nói tiếng Anh cho các tour quốc tế và tour trong nước theo yêu cầu. Vui lòng thông báo trước khi đặt tour.', 'Dịch vụ', 8, 1, '2026-04-27 12:37:58', '2026-04-27 12:37:58', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `my_orders`
--

CREATE TABLE `my_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `total_quantity` int(11) DEFAULT 1,
  `adult_qty` int(11) DEFAULT 0,
  `child_qty` int(11) DEFAULT 0,
  `infant_qty` int(11) DEFAULT 0,
  `depart_date` date DEFAULT NULL,
  `total_price` int(11) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'confirmed',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `my_orders`
--

INSERT INTO `my_orders` (`id`, `user_id`, `order_id`, `tour_id`, `total_quantity`, `adult_qty`, `child_qty`, `infant_qty`, `depart_date`, `total_price`, `status`, `note`, `created_at`) VALUES
(1, 6, 43, 7, 1, 1, 0, 0, '0000-00-00', 2586000, 'confirmed', 'Người lớn: 1 | Trẻ em: 0 | Em bé: 0 | Ngày đi: ', '2026-04-29 21:13:16'),
(2, 6, 45, 8, 2, 1, 1, 0, '2026-05-07', 4572000, 'confirmed', 'Người lớn: 1 | Trẻ em: 1 | Em bé: 0 | Ngày đi: 2026-05-07', '2026-04-29 21:38:07'),
(3, 6, 55, 1, 1, 1, 0, 0, '0000-00-00', 2586000, 'confirmed', 'Người lớn: 1 | Trẻ em: 0 | Em bé: 0', '2026-04-29 22:18:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tour_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(12,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `tour_id`, `order_date`, `quantity`, `total_price`, `status`) VALUES
(1, 2, 1, '2026-04-18 02:37:58', 2, 7980000.00, 'confirmed'),
(2, 2, 3, '2026-04-23 02:37:58', 3, 10770000.00, 'pending'),
(3, 3, 2, '2026-04-21 02:37:58', 1, 4590000.00, 'cancelled'),
(4, 4, 4, '2026-04-26 02:37:58', 4, 22760000.00, 'confirmed'),
(5, NULL, 1, '2026-04-27 02:37:58', 2, 7980000.00, 'pending'),
(6, NULL, 2, '2026-04-28 04:07:56', 1, 4590000.00, 'pending'),
(7, 5, 1, '2026-04-28 04:08:19', 1, 2586000.00, 'pending'),
(8, 5, 1, '2026-04-28 11:50:02', 1, 2586000.00, 'pending'),
(9, NULL, 1, '2026-04-28 13:04:53', 1, 2586000.00, 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `category_id`, `author_id`, `title`, `slug`, `summary`, `content`, `status`, `published_at`, `created_at`) VALUES
(1, 1, 1, 'Khai trương website MyWeb Tour với nhiều ưu đãi', 'khai-truong-website-myweb-tour', 'Giới thiệu website du lịch MyWeb Tour và các chương trình ưu đãi khai trương.', 'Nội dung chi tiết về lễ khai trương, các chương trình giảm giá, voucher cho khách hàng mới...', 'published', '2026-04-13 02:37:58', '2026-04-12 02:37:58'),
(2, 2, 1, '5 kinh nghiệm du lịch Đà Lạt tự túc', '5-kinh-nghiem-du-lich-da-lat-tu-tuc', 'Tổng hợp các kinh nghiệm hữu ích khi tự đi Đà Lạt.', 'Bài viết chia sẻ về thời điểm nên đi, phương tiện di chuyển, địa điểm tham quan và các món ăn nên thử...', 'published', '2026-04-16 02:37:58', '2026-04-15 02:37:58'),
(3, 2, 1, 'Gợi ý lịch trình 3 ngày tại Đà Nẵng', 'goi-y-lich-trinh-3-ngay-tai-da-nang', 'Lịch trình tham quan Đà Nẵng cơ bản trong 3 ngày.', 'Ngày 1: Bán đảo Sơn Trà, biển Mỹ Khê. Ngày 2: Bà Nà Hills. Ngày 3: Hội An cổ kính...', 'published', '2026-04-19 02:37:58', '2026-04-18 02:37:58'),
(4, 3, 1, 'Giảm giá 20% tour Nha Trang trong tháng này', 'giam-gia-20-tour-nha-trang-thang-nay', 'Chương trình khuyến mãi dành cho tour Nha Trang biển xanh 4N3Đ.', 'Chi tiết điều kiện áp dụng, thời gian đặt tour và thời gian khởi hành trong đợt khuyến mãi...', 'published', '2026-04-22 02:37:58', '2026-04-21 02:37:58'),
(5, 1, 1, 'Thông báo cập nhật chính sách hoàn hủy tour', 'cap-nhat-chinh-sach-hoan-huy-tour', 'Giới thiệu các thay đổi trong quy định hoàn/huỷ tour.', 'Nội dung giải thích rõ các mức phí, thời hạn báo huỷ, và các trường hợp đặc biệt...', 'draft', NULL, '2026-04-25 02:37:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_categories`
--

CREATE TABLE `post_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_categories`
--

INSERT INTO `post_categories` (`id`, `name`, `slug`) VALUES
(1, 'Tin tức', 'tin-tuc'),
(2, 'Cẩm nang du lịch', 'cam-nang-du-lich'),
(3, 'Khuyến mãi', 'khuyen-mai');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_comments`
--

INSERT INTO `post_comments` (`id`, `post_id`, `user_id`, `content`, `rating`, `status`, `created_at`) VALUES
(1, 2, 2, 'Bài viết rất hữu ích, mình chuẩn bị đi Đà Lạt nên áp dụng luôn.', 5, 'approved', '2026-04-17 02:37:58'),
(2, 3, 3, 'Lịch trình gợi ý khá hợp lý, nhưng có thể thêm một vài điểm ăn uống.', 4, 'approved', '2026-04-20 02:37:58'),
(3, 4, 4, 'Mình muốn hỏi thời gian áp dụng khuyến mãi là đến ngày nào?', NULL, 'pending', '2026-04-23 02:37:58'),
(4, 1, 2, 'Chúc mừng MyWeb Tour khai trương, mong sớm có thêm nhiều tour mới.', 5, 'approved', '2026-04-14 02:37:58'),
(5, 2, 4, 'Mình thấy nên bổ sung thêm gợi ý khách sạn giá rẻ.', 4, 'approved', '2026-04-18 02:37:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'member');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tours`
--

CREATE TABLE `tours` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) NOT NULL,
  `place_start` varchar(255) NOT NULL,
  `vehicle` varchar(255) NOT NULL,
  `day_start` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `long_description` longtext DEFAULT NULL,
  `hotel` text NOT NULL,
  `note` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tours`
--

INSERT INTO `tours` (`id`, `title`, `slug`, `short_description`, `price`, `duration_days`, `status`, `created_at`, `updated_at`, `image`, `place_start`, `vehicle`, `day_start`, `host`, `long_description`, `hotel`, `note`) VALUES
(1, 'TOUR ĐÀ LẠT  3N3Đ | Thiên Đường Săn Mây - Langbiang Land - Tiệc BBQ', 'tour-da-lat-3n3d-thien-duong-san-may-langbiang-land-tiec-bbq', 'Trải nghiệm cáp treo xuyên rừng thông - Săn mây cầu đất - Langbiang land – giao lưu cồng chiêng', 2586000.00, 3, 'active', '2026-04-28 02:37:58', '2026-04-30 02:36:38', 'https://bazantravel.com/cdn/medias/uploads/21/21380-cap-treo-tour-da-nang-hoi-an-4-ngay-3-dem-670x446.jpg', 'Tp.Hồ Chí Minh', '\nXe du lịch', 'Tối Thứ 5 hàng tuần ', 'Du lịch Việt', 'NGÀY 1 | THIÊN ĐƯỜNG SĂN MÂY – ĐỒI CHÈ CẦU ĐẤT - TRẠM KÍ ỨC – CÁP TREO ĐỒI ROBIN - THIỀN VIỆN TRÚC LÂM (Ăn sáng, ăn trưa, BBQ tối)\n05h00: Đến Cầu Đất, Đoàn đến với khu vực:\n\n• THIÊN ĐƯỜNG SĂN MÂY - Cầu Đất Panorama để đón bình mình lên, ngắm và chụp hình với những đám mây trắng bồng bềnh. Quý khách sẽ được thỏa sức check in với những tiểu cảnh đẹp mê người nơi đây. Ngoài ra Quý khách cũng có thể thuê những bộ trang phục theo nhiều phong cách: Cổ trang, Mông Cổ…để chụp những bộ ảnh độc đáo (chi phí thuê trang phục tự túc).\n06h30: Quý khách dùng điểm tâm sáng tại nhà hàng Panorama.\n07h30: Tiếp tục khám phá những điểm tham quan hấp dẫn tại khu vực Cầu Đất:\n\n• ĐỒI CHÈ CẦU ĐẤT: Với độ cao 1650 mét so với mực nước biển cùng những dãy chè xanh tươi nối dài bất tận, đồi chè Cầu Đất đẹp đến ngỡ ngàng dưới làn sương sớm của bình mình chớm nở. \nCheck – in với CÁNH ĐỒNG ĐIỆN GIÓ trên đồi chè xanh mát.\nĐoàn di chuyển về trung tâm Thành Phố Đà Lạt, ghé tham quan :\n\n• TRẠM KÝ ỨC- NGÔI LÀNG CỔ CHÂU ÂU là bức tranh hoàn mỹ, nơi từng đường nét khắc họa lên đường nét vẻ đẹp yên bình. Không chỉ đơn giản là một nơi để ghé thăm, mà nó là một hành trình – đưa bạn trở về với những giấc mơ thuở ấu thơ.\nTrưa:    Dùng bữa trưa tại nhà hàng. Đoàn về khách sạn nhận phòng nghỉ ngơi.\nChiều:  Đoàn di chuyển tham quan\n\n• CÁP TREO XUYÊN RỪNG THÔNG – ĐẦU TIÊN TẠI ĐÀ LẠT mang đến trải nghiệm ngắm cảnh tuyệt vời, cho phép du khách chiêm ngưỡng toàn cảnh thành phố, rừng thông bạt ngàn và hồ Tuyền Lâm từ trên cao (chi phí tự túc).\n• THIỀN VIỆN TRÚC LÂM : Tọa lạc trên núi Phụng Hoàng, nhìn xuống hồ Tuyền Lâm xanh biếc, đây là thiền viện lớn và đẹp bậc nhất Đà Lạt.\nTối:       Quý khách dùng bữa tối BBQ hoành tráng tại nhà hàng trong tiết trời se lạnh và không gian cực thư giãn của Đà Lạt. \n\nNGÀY 2 | LANGBIANG LAND- GIAO LƯU VĂN HOÁ CỒNG CHIÊNG- FRESH GARDEN (Ăn sáng, trưa) (Tối tự túc) \nSáng: Quý khách dùng điểm tâm sáng. Bắt đầu hành trình tham quan TP Đà Lạt:\n\n• QUẢNG TRƯỜNG LÂM VIÊN : Tọa lạc giữa \"trái tim\" của thành phố hoa, hướng ra hồ Xuân Hương, ấn tượng với công trình nghệ thuật khổng lồ với khối bông hoa dã quỳ và khối nụ hoa được thiết kế bằng kính màu lạ mắt.\n• LANGBIANG LAND Trú ngụ dưới chân núi Langbiang yên bình – nơi mang đậm giá trị văn hóa thiêng liêng của người đồng bào K’Ho. Là điểm tham quan mang giá trị tinh hoa của núi rừng Tây Nguyên, gồm những hạng mục sau:\n- Tham quan Thác hoa đào,\n- Vườn đào lông, vườn dâu Nhật\n- Tham quan Công viên khủng Long\n- Tham quan Vườn thú cưng\n- Trò chơi Trượt phao kỳ thú\n- Trò chơi Trượt máng cầu vồng\n- Trò chơi chạy xe Greenline Luge\n- Tham quan Tượng \"Vũ điệu Langbiangland\" trên hồ vô cực\n- Tham quan Cầu bán nguyệt\n• Quý khách trải nghiệm GIAO LƯU VĂN HOÁ CỒNG CHIÊNG : tiếng chiêng vang vọng, ánh lửa bập bùng. Điệu múa uyển chuyển của các chàng trai, cô gái Tây Nguyên tái hiện lại những nghi lễ linh thiêng, từ lễ cúng bến nước, lễ mừng lúa mới đến những ngày hội của buôn làng.\nTrưa: Quý khách dùng bữa trưa tại nhà hàng. Về khách sạn nghỉ ngơi.\nChiều: Xe đưa Đoàn đi tham quan.\n\n- FRESH GARDEN là một trong những nơi sở hữu cánh đồng hoa đa dạng nhất Đà Lạt. Từ lavender tím mộng mơ, hoa hướng dương rực rỡ, đến cẩm tú cầu, hoa sao nhái…bên cạnh đó còn có nhưng hạng mục hấp dẫn:\n- Cối xay gió và ngôi nhà phủ đầy hoa\n- Hồ vô cực với view rừng thông\nThác nước nhân tạo ảo diệu\n- Cổng trời Châu Âu\nĐộng băng thiên thần….\n- FRESH ZOO được thiết kế theo mô hình sở thú trong nhà, mang đến cho bạn trải nghiệm gần gũi và an toàn khi tương tác với các loài thú. Quý khách sẽ có cơ hội tiếp xúc trực tiếp, cho ăn và chụp ảnh cùng những loài động vật như: ngựa lùn, bò lùn, dê lùn, vẹt, thỏ, chuột lang, sóc Bắc Mỹ, lạc đà Alpaca… \nTối: Quý khách dùng bữa tối tự túc, tự do vui chơi. Quý khách vui chơi với CHỢ ĐÊM ÂM PHỦ, hãy sắm cho mình những chiếc khăn choàng và nón được đan từ những đôi bàn tay tài hoa nhưng không kém phần tỉ mỉ. Xung quanh chợ hoặc các khu phố, dễ dàng bắt gặp hình ảnh quay quần bên nhau cùng ly sữa nóng, chiếc bánh rán nóng hổi. \n\nNGÀY 3 | CHỢ ĐÀ LẠT - MUA SẮM NÔNG SẢN – TP.HCM (Ăn sáng, trưa) \nSáng: Quý khách dùng điểm tâm sáng và làm thủ tục trả phòng.\n\nCHỢ ĐÀ LẠT - Du khách sẽ choáng ngợp bởi hàng ngàn loại rau củ, hàng trăm loại trái cây và đủ các loại hoa khoe sắc.\nTrưa: Đoàn dừng chân dùng bữa trưa tại BẢO LỘC. Tiếp tục hành trình về TP.HCM.\nChiều: Về đến điểm đón ban đầu, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYẾN THAM QUAN BỔ ÍCH!\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00\n', '3-4 sao', 'GIÁ TOUR BAO GỒM:  \n\n- Phương tiện: Xe đời mới, máy lạnh suốt tuyến, phục vụ lịch sự chu đáo, đạt chuẩn du lịch.  \n- Lưu trú: 02 đêm khách sạn 3* (tiêu chuẩn: 02 – 03 – 04 khách/phòng). Quý khách đi nhóm gia đình sẽ ưu tiên ở phòng Family.  \n- Ăn uống: 02 bữa sáng (1 tô + 1 ly) + 02 bữa trưa  \n- Bảo hiểm du lịch nội địa (Không được áp dụng bảo hiểm du lịch cho các trường hợp hợp sau: trên 80 tuổi, tiền sử các bệnh như: tim mạch, đột quỵ, …)  \n- Hướng dẫn viên kinh nghiệm, nhiệt tình, vui vẻ phục vụ đoàn suốt tuyến đi.  \n- Đoàn được phục vụ: Nước suối: 1 chai 500ml/ngày/khách.  \n\nGIÁ TOUR KHÔNG BAO GỒM:  \n\n- Chi phí cá nhân: giặt ủi, điện thoại, thức uống trong các bữa ăn, minibar… và tham quan, vận chuyển ngoài chương trình.  \n- Phụ thu phòng đơn: 700.000 VND  \n- Phụ thu ghế ưu tiên: 100.000 VND/ghế  \n- Vé tham quan Puppy Farm: 100.000 VND/khách  \n- Thức uống tại các điểm café  \n- 01 bữa ăn sáng ngày 1 + 02 bữa ăn tối + 01 bữa trưa ngày về.  \n- Tip (chi phí bồi dưỡng) cho tài xế và hướng dẫn viên.  \n- Thuế VAT  '),
(2, 'TOUR ĐÀ LẠT 3N2Đ | Langbiang Land 3N2Đ: Hành Trình “Adventure” - Miền Sơn Cước\n', 'tour-da-lat-3n2d-langbiang-adventure-mien-son-cuoc', 'Khám Phá KDL Madagui - Langbiang Land\n- Thưởng thức ẩm thực núi rừng\n- Giao lưu văn hóa cồng chiêng', 1986000.00, 3, 'active', '2026-04-28 02:37:58', '2026-04-30 00:38:19', 'https://datviettour.com.vn/uploads/images/tay-nguyen/madagui/danh-thang/kdl-madagui-850px.jpg', 'Tp.Hồ Chí Minh', 'Xe ghế ngồi', 'Thứ 6 hàng tuần', 'Du Lịch Đất Việt', 'NGÀY 1 | TP.HCM - KDL MADAGUI - QUẢNG TRƯỜNG LÂM VIÊN ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách có mặt tại điểm tập trung. Xe và hướng dẫn viên đón đoàn theo giờ đã hẹn. Chào mừng các thành viên đã đồng hành và gửi những phần quà thiết yếu cho đoàn. Tham gia các trò chơi tập thể trên xe để nhận phần quà hấp dẫn.\n\nĐoàn ăn sáng tại nhà hàng địa phương.\nTrưa: Đến KDL Madagui - Nơi đây có hệ động thực vật vô cùng phong phú. Đến với khu du lịch Madagui bạn sẽ ngỡ ngàng trước một thế giới hoàn toàn khác đầy ắp tiếng chim ca véo von, không gian yên tĩnh sẽ giúp bạn thư giãn và trốn khỏi sự ồn ào, náo nhiệt của thành phố\n\nQuý khách dùng cơm trưa tại nhà hàng. Sau đó Đoàn tự do khám phá Madagui với các hoạt động thú vị như Quý khách tự do tham quan, khám phá, vui chơi các hoạt động khác tại KDL như Leo Núi, Trượt Cỏ, Bóng Lăn, Bóng Rổ, Bóng Đá, Bóng Chuyền, Tennis…tại Khu Liên Hợp Thể Thao, Khám phà Hang Dơi, Hang Tử Thần, Trượt Zipline, Câu Cá Giải Trí, Chèo Thuyền Kayak…(Chi phí các trò chơi tự túc)\nChiều: Tiếp tục hành trình đến Đà Lạt, Đoàn tham quan:\n\nCheck-in Quảng Trường Lâm Viên: Tọa lạc giữa \"trái tim\" của thành phố hoa, hướng ra hồ Xuân Hương, không chỉ mang đến không gian rộng lớn, thoáng mát với nhiều hoạt động giải trí mà Quảng Trường Lâm Viên còn ấn tượng với công trình nghệ thuật khổng lồ với khối bông hoa dã quỳ và khối nụ hoa được thiết kế bằng kính màu lạ mắt. Đừng quên lưu giữ lại cùng gia đình những bức ảnh thú vị này.\nĐoàn nhận phòng khách sạn nghỉ ngơi.\n\nTối: Quý khách tự do khám phá và thưởng thức ẩm thực Đà Lạt trong tiết trời se lạnh và không gian cực thư giãn.\n\nNGÀY 2 | LANGBIANG LAND - GIAO LƯU CỒNG CHIÊNG - DOMAINE DE MARIE - VIỆN SINH HỌC ( • Ăn sáng • Ăn trưa )\n\nĐỐI VỚI KHÁCH HÀNG MUA GÓI VIP 2 NGÀY 2 FREEDAY: TỰ DO KHÁM PHÁ ĐÀ LẠT\n\nSáng: Quý khách dùng điểm tâm sáng. Bắt đầu hành trình tham quan TP Đà Lạt:\n\nLangbiang Land: Trú ngụ dưới chân núi Langbiang yên bình - nơi mang đậm giá trị văn hóa thiêng liêng của người đồng bào K’Ho. Là điểm tham quan mang giá trị tinh hoa của núi rừng Tây Nguyên, gồm những hạng mục sau:\n\nTham quan Thác hoa đào,\nVườn đào lông, vườn dâu Nhật\nTham quan Công viên khủng Long\nTham quan Vườn thú cưng\nTrò chơi Trượt phao kỳ thú\nTrò chơi Trượt máng cầu vồng\nTrò chơi chạy xe Greenline Luge\nTham quan Tượng \"Vũ điệu Langbiangland\" trên hồ vô cực\nTham quan Cầu bán nguyệt\nTrải nghiệm văn hóa Giao Lưu Văn Hoá Cồng Chiêng: tiếng chiêng vang vọng, ánh lửa bập bùng. Điệu múa uyển chuyển của các chàng trai, cô gái Tây Nguyên tái hiện lại những nghi lễ linh thiêng, từ lễ cúng bến nước, lễ mừng lúa mới đến những ngày hội của buôn làng.\nTrưa: Quý khách dùng bữa trưa tại nhà hàng. Đoàn về lại khách sạn, nghỉ ngơi.\n\nChiều: Đoàn khởi hành tham quan:\n\nNhà Thờ Domaine Da Marie: công trình kiến trúc phong cách châu Âu thế kỷ XVII. Đây cũng là nơi sinh sống và làm việc của các nữ tu Bác Ái.\nPhân Viện Sinh Học Đà Lạt: Được xây dựng vào khoảng những năm 50 của thế kỷ trước bởi người Pháp. Khi vừa bước vào trong tòa nhà, bạn sẽ được trải nghiệm một thế giới mới lạ, mỗi mét vuông ở đây đều là một góc sống ảo tuyệt vời cho bạn tha hồ lên ý tưởng cho những bức hình của mình\nTối: Quý khách dùng bữa tối tự túc, tự do vui chơi. Quý khách vui chơi với CHỢ ĐÊM ÂM PHỦ, hãy sắm cho mình những chiếc khăn choàng và nón được đan từ những đôi bàn tay tài hoa nhưng không kém phần tỉ mỉ. Xung quanh chợ hoặc các khu phố, dễ dàng bắt gặp hình ảnh quay quần bên nhau cùng ly sữa nóng, chiếc bánh rán nóng hổi.\n\nNGÀY 3 | CHỢ ĐÀ LẠT - MUA SẮM NÔNG SẢN - TP.HCM ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách dùng điểm tâm sáng và làm thủ tục trả phòng.\n\nChợ Đà Lạt - Du khách sẽ choáng ngợp bởi hàng ngàn loại rau củ, hàng trăm loại trái cây và đủ các loại hoa khoe sắc. Tiếng cười nói, tiếng trao đổi mua bán cùng dòng người thông thương đổ về đây. Tất cả tạo nên bức tranh thương mại sống động và vui vẻ.\nTrưa: Đoàn dừng chân dùng bữa trưa tại Bảo Lộc. Tiếp tục hành trình về TP.HCM quý khách nghỉ ngơi trên xe.\n\nChiều: Về đến điểm đón ban đầu, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYỂN THAM QUAN BỔ ÍCH\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00\nNếu quý khách đến sớm, khách sạn sẽ bố trí cho nhận phòng trong trường hợp có phòng trống. Trường hợp chưa có phòng, quý khách vui lòng dùng nước mát trong thời gian chờ đợi\nSáng: Quý khách có mặt tại điểm tập trung. Xe và hướng dẫn viên đón đoàn theo giờ đã hẹn. Chào mừng các thành viên đã đồng hành và gửi những phần quà thiết yếu cho đoàn. Tham gia các trò chơi tập thể trên xe để nhận phần quà hấp dẫn.\n\nĐoàn ăn sáng tại nhà hàng địa phương.\nTrưa: Đến KDL Madagui - Nơi đây có hệ động thực vật vô cùng phong phú. Đến với khu du lịch Madagui bạn sẽ ngỡ ngàng trước một thế giới hoàn toàn khác đầy ắp tiếng chim ca véo von, không gian yên tĩnh sẽ giúp bạn thư giãn và trốn khỏi sự ồn ào, náo nhiệt của thành phố\n\nQuý khách dùng cơm trưa tại nhà hàng. Sau đó Đoàn tự do khám phá Madagui với các hoạt động thú vị như Quý khách tự do tham quan, khám phá, vui chơi các hoạt động khác tại KDL như Leo Núi, Trượt Cỏ, Bóng Lăn, Bóng Rổ, Bóng Đá, Bóng Chuyền, Tennis…tại Khu Liên Hợp Thể Thao, Khám phà Hang Dơi, Hang Tử Thần, Trượt Zipline, Câu Cá Giải Trí, Chèo Thuyền Kayak…(Chi phí các trò chơi tự túc)\nChiều: Tiếp tục hành trình đến Đà Lạt, Đoàn tham quan:\n\nCheck-in Quảng Trường Lâm Viên: Tọa lạc giữa \"trái tim\" của thành phố hoa, hướng ra hồ Xuân Hương, không chỉ mang đến không gian rộng lớn, thoáng mát với nhiều hoạt động giải trí mà Quảng Trường Lâm Viên còn ấn tượng với công trình nghệ thuật khổng lồ với khối bông hoa dã quỳ và khối nụ hoa được thiết kế bằng kính màu lạ mắt. Đừng quên lưu giữ lại cùng gia đình những bức ảnh thú vị này.\nĐoàn nhận phòng khách sạn nghỉ ngơi.\n\nTối: Quý khách tự do khám phá và thưởng thức ẩm thực Đà Lạt trong tiết trời se lạnh và không gian cực thư giãn.\n\nNgày 2 |\nLANGBIANG LAND - GIAO LƯU CỒNG CHIÊNG - DOMAINE DE MARIE - VIỆN SINH HỌC ( • Ăn sáng • Ăn trưa )\n\nĐỐI VỚI KHÁCH HÀNG MUA GÓI VIP 2 NGÀY 2 FREEDAY: TỰ DO KHÁM PHÁ ĐÀ LẠT\n\nSáng: Quý khách dùng điểm tâm sáng. Bắt đầu hành trình tham quan TP Đà Lạt:\n\nLangbiang Land: Trú ngụ dưới chân núi Langbiang yên bình - nơi mang đậm giá trị văn hóa thiêng liêng của người đồng bào K’Ho. Là điểm tham quan mang giá trị tinh hoa của núi rừng Tây Nguyên, gồm những hạng mục sau:\n\nTham quan Thác hoa đào,\nVườn đào lông, vườn dâu Nhật\nTham quan Công viên khủng Long\nTham quan Vườn thú cưng\nTrò chơi Trượt phao kỳ thú\nTrò chơi Trượt máng cầu vồng\nTrò chơi chạy xe Greenline Luge\nTham quan Tượng \"Vũ điệu Langbiangland\" trên hồ vô cực\nTham quan Cầu bán nguyệt\nTrải nghiệm văn hóa Giao Lưu Văn Hoá Cồng Chiêng: tiếng chiêng vang vọng, ánh lửa bập bùng. Điệu múa uyển chuyển của các chàng trai, cô gái Tây Nguyên tái hiện lại những nghi lễ linh thiêng, từ lễ cúng bến nước, lễ mừng lúa mới đến những ngày hội của buôn làng.\nTrưa: Quý khách dùng bữa trưa tại nhà hàng. Đoàn về lại khách sạn, nghỉ ngơi.\n\nChiều: Đoàn khởi hành tham quan:\n\nNhà Thờ Domaine Da Marie: công trình kiến trúc phong cách châu Âu thế kỷ XVII. Đây cũng là nơi sinh sống và làm việc của các nữ tu Bác Ái.\nPhân Viện Sinh Học Đà Lạt: Được xây dựng vào khoảng những năm 50 của thế kỷ trước bởi người Pháp. Khi vừa bước vào trong tòa nhà, bạn sẽ được trải nghiệm một thế giới mới lạ, mỗi mét vuông ở đây đều là một góc sống ảo tuyệt vời cho bạn tha hồ lên ý tưởng cho những bức hình của mình\nTối: Quý khách dùng bữa tối tự túc, tự do vui chơi. Quý khách vui chơi với CHỢ ĐÊM ÂM PHỦ, hãy sắm cho mình những chiếc khăn choàng và nón được đan từ những đôi bàn tay tài hoa nhưng không kém phần tỉ mỉ. Xung quanh chợ hoặc các khu phố, dễ dàng bắt gặp hình ảnh quay quần bên nhau cùng ly sữa nóng, chiếc bánh rán nóng hổi.05h00: Đến Cầu Đất, Đoàn đến với khu vực:\n\nTHIÊN ĐƯỜNG SĂN MÂY - Cầu Đất Panorama để đón bình mình lên, ngắm và chụp hình với những đám mây trắng bồng bềnh. Quý khách sẽ được thỏa sức check in với những tiểu cảnh đẹp mê người nơi đây. Ngoài ra Quý khách cũng có thể thuê những bộ trang phục theo nhiều phong cách: Cổ trang, Mông Cổ…để chụp những bộ ảnh độc đáo (chi phí thuê trang phục tự túc).\n06h30: Quý khách dùng điểm tâm sáng tại nhà hàng Panorama.\n07h30: Tiếp tục khám phá những điểm tham quan hấp dẫn tại khu vực Cầu Đất:\n\nĐỒI CHÈ CẦU ĐẤT: Với độ cao 1650 mét so với mực nước biển cùng những dãy chè xanh tươi nối dài bất tận, đồi chè Cầu Đất đẹp đến ngỡ ngàng dưới làn sương sớm của bình mình chớm nở. \nCheck – in với CÁNH ĐỒNG ĐIỆN GIÓ trên đồi chè xanh mát.\nĐoàn di chuyển về trung tâm Thành Phố Đà Lạt, ghé tham quan :\n\nTRẠM KÝ ỨC- NGÔI LÀNG CỔ CHÂU ÂU là bức tranh hoàn mỹ, nơi từng đường nét khắc họa lên đường nét vẻ đẹp yên bình. Không chỉ đơn giản là một nơi để ghé thăm, mà nó là một hành trình – đưa bạn trở về với những giấc mơ thuở ấu thơ.\nTrưa:    Dùng bữa trưa tại nhà hàng. Đoàn về khách sạn nhận phòng nghỉ ngơi.\nChiều:  Đoàn di chuyển tham quan\n\nCÁP TREO XUYÊN RỪNG THÔNG – ĐẦU TIÊN TẠI ĐÀ LẠT mang đến trải nghiệm ngắm cảnh tuyệt vời, cho phép du khách chiêm ngưỡng toàn cảnh thành phố, rừng thông bạt ngàn và hồ Tuyền Lâm từ trên cao (chi phí tự túc).\nTHIỀN VIỆN TRÚC LÂM : Tọa lạc trên núi Phụng Hoàng, nhìn xuống hồ Tuyền Lâm xanh biếc, đây là thiền viện lớn và đẹp bậc nhất Đà Lạt.\nTối:       Quý khách dùng bữa tối BBQ hoành tráng tại nhà hàng trong tiết trời se lạnh và không gian cực thư giãn của Đà Lạt. Ngày 3\nCHỢ ĐÀ LẠT - MUA SẮM NÔNG SẢN - TP.HCM ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách dùng điểm tâm sáng và làm thủ tục trả phòng.\n\nChợ Đà Lạt - Du khách sẽ choáng ngợp bởi hàng ngàn loại rau củ, hàng trăm loại trái cây và đủ các loại hoa khoe sắc. Tiếng cười nói, tiếng trao đổi mua bán cùng dòng người thông thương đổ về đây. Tất cả tạo nên bức tranh thương mại sống động và vui vẻ.\nTrưa: Đoàn dừng chân dùng bữa trưa tại Bảo Lộc. Tiếp tục hành trình về TP.HCM quý khách nghỉ ngơi trên xe.\n\nChiều: Về đến điểm đón ban đầu, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYỂN THAM QUAN BỔ ÍCH\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00\nNếu quý khách đến sớm, khách sạn sẽ bố trí cho nhận phòng trong trường hợp có phòng trống. Trường hợp chưa có phòng, quý khách vui lòng dùng nước mát trong thời gian chờ đợi', '4 sao', 'GIÁ TOUR BAO GỒM:  \n\n- Phương tiện: Xe đời mới, máy lạnh suốt tuyến, phục vụ lịch sự chu đáo, đạt chuẩn du lịch.  \n- Lưu trú: 02 đêm khách sạn 3* (tiêu chuẩn: 02 – 03 – 04 khách/phòng). Quý khách đi nhóm gia đình sẽ ưu tiên ở phòng Family.  \n- Ăn uống: 02 bữa sáng (1 tô + 1 ly) + 02 bữa trưa  \n- Bảo hiểm du lịch nội địa (Không được áp dụng bảo hiểm du lịch cho các trường hợp hợp sau: trên 80 tuổi, tiền sử các bệnh như: tim mạch, đột quỵ, …)  \n- Hướng dẫn viên kinh nghiệm, nhiệt tình, vui vẻ phục vụ đoàn suốt tuyến đi.  \n- Đoàn được phục vụ: Nước suối: 1 chai 500ml/ngày/khách.  \n\nGIÁ TOUR KHÔNG BAO GỒM:  \n\n- Chi phí cá nhân: giặt ủi, điện thoại, thức uống trong các bữa ăn, minibar… và tham quan, vận chuyển ngoài chương trình.  \n- Phụ thu phòng đơn: 700.000 VND  \n- Phụ thu ghế ưu tiên: 100.000 VND/ghế  \n- Vé tham quan Puppy Farm: 100.000 VND/khách  \n- Thức uống tại các điểm café  \n- 01 bữa ăn sáng ngày 1 + 02 bữa ăn tối + 01 bữa trưa ngày về.  \n- Tip (chi phí bồi dưỡng) cho tài xế và hướng dẫn viên.  \n- Thuế VAT  '),
(3, 'TOUR ĐÀ LẠT  4N3Đ: Làng Hoa Vạn Thành - Puppy Farm - Langbiang - Học Viện Don Bosco - Đồi Chè Cầu Đất', 'tour-da-lat-4n3d-langbiang-puppy-farm-doi-che-cau-dat', 'Tham quan làng hoa Vạn Thành – Nông trại cún Puppy farm – \nLangbiang – Khám phá ẩm thực Đà Lạt ', 2590000.00, 4, 'active', '2026-04-28 02:37:58', '2026-04-30 00:38:11', 'https://samtenhills.vn/wp-content/uploads/2024/01/dai-bao-thap-kinh-luan-lon-nhat-the-gioi.jpg', 'Tp.Hồ Chí Minh', 'Xe du lịch đời mới', 'Thứ 2 &5 hàng tuần', 'Vietourist', '​​NGÀY 1 | SÀI GÒN – ĐÀ LẠT ( Ăn Sáng, Trưa) \n\nSáng: Xe và hướng dẫn viên công ty Vietourist đón quý khách tại điểm hẹn khởi hành đi Đà Lạt.\n\nĐoàn ăn sáng tại khu vực Đồng Nai. Sau bữa sáng, Xe theo quốc lộ 20 đến với Đà Lạt. Trên xe Quý khách cùng hướng dẫn viên tìm hiểu về lịch sử, văn hóa từng vùng đất mà đoàn đi qua, tham gia các trò chơi vui nhộn cùng nhiều phần quà hấp dẫn.\n\nTrưa: Đoàn dùng cơm trưa tại Bảo Lộc. \n\nChiều: Đến với thác Pongour, cách quốc lộ 20 khoảng 7km. Thác Pongour Đà Lạt là thác nước nổi tiếng khu vực Tây Nguyên, nơi đây hàng năm thu hút một số lượng lớn khách du lịch đến đây tham quan. Không chỉ thế thác Pongour còn được rất nhiều khách du lịch ngoài nước biết đến là một thác nước tuyệt đẹp và thơ mộng.\n\nĐến Đà Lạt, nhận phòng khách sạn nghỉ ngơi.\n\nTối: Quý khách tự túc dùng cơm tối và tự do dạo phố đêm Đà Lạt.\n\nNGÀY 2 | LÀNG HOA VẠN THÀNH – PUPPY FARM - LANGBIANG ( Ăn Sáng, Trưa, Tối) \n\nSáng: Sau bữa sáng tại khách sạn, đoàn bắt đầu hành trình tham quan Đà lạt với những điểm tham quan hấp dẫn sau: \n\nLàng Hoa Vạn Thành\n\nLàng Hoa Vạn Thành: Nói đến hoa, chúng ta không thể không nhắc đến làng hoa Vạn Thành – một làng trồng hoa truyền thống đã làm rạng danh thương hiệu hoa Đà Lạt. Làng hoa đã được hình thành từ rất lâu, là nơi cung cấp hoa uy tín và chất lượng hàng đầu ở Đà Lạt, và trở thành địa điểm tham quan du lịch nổi tiếng.  \n\nPuppy farm: là một địa điểm chiếm được sự yêu mến của rất nhiều người, bởi đây là ngôi nhà chung của hơn 150 chú chó. Khi đến đây, bạn không chỉ chơi đùa với những chú cún dễ thương, mà còn được dạo quanh những cánh đồng hoa xinh đẹp, hay vườn nông sản được trồng theo công nghệ hiện đại. Tại trang trại luôn có đội ngũ nhân viên hướng dẫn, hỗ trợ nhiệt tình khi bạn cần.\n\nTrưa: Dùng cơm trưa tại nhà hàng.Về khách sạn nghỉ ngơi.\n\nChiều: Đoàn tiếp tục hành trình tham quan: \n\nLangbiang\n\nLangbiang: Được ví như trái tim của Đà Lạt, núi Langbiang nổi tiếng với loại hình dã ngoại, khám phá thiên nhiên, thu hút các bạn trẻ mê phượt và những nhà mạo hiểm. Nơi đây là ngôi nhà chung của nhiều loại thảo dược, thảo mộc, và chim quý. Ở Khu du lịch Langbiang Đà Lạt, hoạt động chính là tham quan ngắm cảnh. Du khách sẽ chọn được những góc chụp rất đẹp và có tầm nhìn rộng với phong cảnh núi non hùng vĩ (vé xe vận chuyển tự túc).\n\nTối: Đoàn dùng bữa tối tại nhà hàng địa phương và tham gia buổi lễ giao lưu cồng chiêng Đà Lạt, giao lưu ca hát, nhảy múa, ăn thịt nướng và uống rượu cần bên bếp lửa hồng cùng với những nghệ nhân dân tộc. Thông qua các hoạt động văn hóa sẽ giúp bạn hiểu biết và tôn trọng hơn về nét văn hóa truyền thống của người dân Tây Nguyên.\n\nNGÀY 3 | DINH I – HỒ TUYỀN LÂM - ĐỒI CHÈ CẦU ĐẤT ( Ăn Sáng, Trưa) \n\nSáng: Quý khách dùng bữa sáng tại khách sạn, quý khách tiếp tục chương trình tham quan những cảnh điểm nổi tiếng của Đà Lạt.\n\nDinh III Đà Lạt hay còn được gọi là Dinh Bảo Đại – một dinh thự sang trọng, mang đậm bản sắc châu Âu giữa lòng những đồi thông xanh. Một địa điểm du lịch hấp dẫn cho những ai yêu thích khám phá lịch sử và đắm mình trong khung cảnh lãng mạn nước Pháp nơi đây. Dinh nằm giữa rừng Ái Ân trên đỉnh đồi mà trong dự án chỉnh trang Ðà Lạt của Ernest Hébrard dành cho dinh toàn quyền. Toàn thể công trình mang đậm phong cách kiến trúc Châu Âu, điển hình là trước biệt điện và sau biệt điện đều có vườn hoa. \n\nDon Bosco Đà Lạt\n\nDon Bosco Đà Lạt hay tên đầy đủ hơn là Học viện Don Bosco Đà Lạt được thành lập vào năm 1971. Giữa không gian rộng lớn, tòa nhà Don Bosco hiện lên nguy nga và tráng lệ với sắc trắng tinh khôi cùng những đường nét mang âm hưởng kiến trúc châu Âu cổ điển. Dễ thấy nhất là những cột trụ khổng lồ, dãy hành lang trải dài, đường nét chạm khắc tinh xảo, mái chóp nhọn,...\n\nTrưa: Dùng cơm trưa tại nhà hàng địa phương.\n\nĐồi Chè Cầu Đất\n\nChiều: Đồi Chè Cầu Đất Farm Đà Lạt: là địa điểm du lịch sinh thái nổi tiếng. Từ con đường uốn lượn quanh co, cây đại thụ nghiêng mình đón nắng sớm hay hình ảnh người địa phương đang miệt mài thu hoạch lá chè, mọi khoảnh khắc tại Đồi Chè Cầu Đất Farm Đà Lạt đều ẩn chứa “ý thơ” và mang đến cho bạn trải nghiệm du lịch thư thả.\n\nXe và HDV đưa đoàn quay lại trung tâm Đà Lạt, dùng cơm tối tại nhà hàng địa phương.\n\nNGÀY 4 | QUẢNG TRƯỜNG LÂM VIÊN – SÀI GÒN ( Ăn Sáng, Trưa)\n\nQuảng trường Lâm Viên\n\nSáng: Quý khách sau khi dùng bữa sáng tại khách sạn, làm thủ tục trả phòng. Xe đưa Quý khách đến Quảng trường Lâm Viên – khu vực ấn tượng với không gian rộng lớn, thoáng mát hướng ra hồ Xuân Hương cùng công trình nghệ thuật khối bông hoa dã quỳ và khối nụ hoa Atiso khổng lồ được thiết kế bằng kính màu rất đẹp mắt. Khởi hành trở lại TPHCM. \n\nVề đến TP.Bảo Lộc, Quý khách dùng cơm trưa, thưởng thức đặc sản địa phương như trà, các loại trái cây nổi tiếng của Bảo Lộc.\n\nVề đến TP.HCM Kết thúc chuyến đi chia tay và hẹn gặp lại quý khách Trên Mọi Nẻo Đường Của Vietourist.', '3 sao', 'GIÁ TOUR BAO GỒM:\n- Xe tham quan (xe 29 chỗ, 35 chỗ, 45 chỗ) tùy theo số lượng khách thực tế trên chuyến đi..\n- Ăn các bữa ăn theo chương trình:\n+ Ăn sáng: tại nhà hàng \n+ Ăn chính: nhà hàng địa Phương.\n\nKHÁCH SẠN: Tiêu chuẩn 2, 3 khách/ phòng. Tiện nghi: tivi, nước nóng, vệ sinh\n3 SAO: DALLAS, ELC LUXURY, ... Hoặc tương đương\nChính sách phụ thu phòng ở 2,3 khách/ phòng tùy theo từng loại khách sạn \n- Hướng dẫn viên tiếng Việt nhiệt tình phục vụ đoàn ăn nghỉ suốt tuyến.\n- Vé tham quan theo chương trình.\n- Quý khách được phục vụ nước uống trên xe 01 chai/khách/ngày.\n- Quý khách được tặng 01 mũ Du Lịch Việt. \n- Bảo hiểm trọn tour mức bồi thường cao nhất 100,000,000đ/trường hợp. Không áp dụng cho khách từ 75 tuổi trở lên, phụ nữ mang thai. \n- Thuế VAT.\nKHÔNG BAO GỒM:\n- Vé tham quan, café, vận chuyển không bao gồm trong chương trình.\n- 02 bữa tối như chương trình.\n- Tiền Tip hướng dẫn viên và tài xế.\n- Phụ phí phòng đơn, phụ phí người nước ngoài.'),
(4, 'TOUR ĐÀ LẠT 3N3Đ | Quảng Trường Lâm Viên - Happy Hill - Puppy Farm', 'ha-noi-ha-long-4n3d', 'Tour kết hợp Hà Nội phố cổ và du thuyền Vịnh Hạ Long.', 999000.00, 4, 'active', '2026-04-28 02:37:58', '2026-04-30 00:35:54', 'https://statics.vinpearl.com/du-lich-ha-long-2_1632635256.jpg', 'Tp.Hồ Chí Minh  ', ' Xe du lịch  ', 'Tối thứ 5 hàng tuần  ', 'Du lịch Việt', 'NGÀY 1 | ĐÀ LẠT – QUẢNG TRƯỜNG LÂM VIÊN ĐÀ LẠT – HAPPY HILL COFFEE   (Không bao gồm ăn uống) \n05h00 Sáng: Đến Đà Lạt, đoàn vệ sinh cá nhân và tự túc dùng điểm tâm sáng. \nĐoàn checkin Quảng Trường Lâm Viên Đà Lạt - nổi tiếng bởi vẻ đẹp thiên nhiên và những công trình kiến trúc đặc sắc như: Bông hoa Dã Quỳ khổng lồ, nụ hoa Atiso bằng kính nhiều màu rất đẹp và đặc biệt là khu vui chơi, mua sắm phía dưới tầng hầm của quảng trường.\nXe đưa đoàn đến check in tại Happy Hill Coffee (Tự túc chi phí vé tham quan và đồ uống), nằm cạnh Hồ Tuyền Lâm, Happy Hill mang vẻ đẹp tự nhiên, nép mình bên mặt nước hồ xanh mướt. \nĐến đây du khách thỏa sức chụp hình sống ảo với những cảnh đẹp thơ mộng, lưu giữ khoảnh khắc đáng nhớ bên gia đình và bạn bè.\n\nNgắm nhìn đường hoa cẩm tú cầu, thả mình với thiên nhiên hữu tình nên thơ.\nVới không gian bạt ngàn rừng cà phê chính là một trong những điều kiện thuận lợi cho những con người làm ra ly cà phê đậm đà nguyên chất đến vậy.\nBuổi trưa: Quay về Trung tâm TP Đà Lạt, nhận phòng khách sạn nghỉ ngơi.\nQuy định thời gian nhận phòng khách sạn checkin lúc 14g00, có thể nhận phòng sớm hơn nếu có phòng trống.\nChiều: Quý khách tự do vi vu khám phá Thành phố Đà Lạt mộng mơ. \nTối: Đoàn tập trung tại Khách sạn, Xe đưa Quý Khách tới Chợ Đêm Âm Phủ, Quý khách tự do tham quan và khám phá Đà Lạt về đêm. \n\nNGÀY 2 | ĐÀ LẠT - PUPPY FARM (Ăn sáng) (Ăn trưa, chiều tự túc) \nSáng: Quý khách dùng điểm tâm sáng. Bắt đầu hành trình tham quan TP Đà Lạt. \nXe đưa đoàn tới PUPPY FARM điểm đến tuyệt vời cho những ai yêu thích thiên nhiên và động vật (Tự túc chi phí vé tham quan). Nằm giữa những đồi thông xanh, Puppy Farm không chỉ là nơi nuôi dưỡng và chăm sóc những chú chó dễ thương mà còn là một trang trại rộng lớn với vườn hoa rực rỡ, khu sở thú và khu vườn nông nghiệp. Với không gian thoáng đãng và phong cảnh tuyệt đẹp, Puppy Farm Đà Lạt hứa hẹn sẽ mang đến những trải nghiệm khó quên cho mọi lứa tuổi, gồm những hạng mục sau:\n\nVui chơi với những chú cún dễ thương\nTham quan vườn sở thú và trải nghiệm cho thú ăn\nTrải nghiệm trò chơi trượt phao khô đầy thú vị\nTham quan khu đồi hoa rộng lớn với nhiều góc chụp hình đẹp\nTham quan vườn dâu tây công nghệ cao\nTham quan khu vườn nông nghiệp công nghệ cao\nTham quan khu vườn sen đá với đa dạng màu sắc\nTrưa: Quý khách Về lại khách sạn nghỉ ngơi.\nChiều: Quý khách tự do vivu khám phá Thành phố Đà Lạt mộng mơ hoặc tự túc trải nghiệm, Check-in tại Tiệm cà phê Xóm Lèo – tọa lạc giữa sườn đồi với view toàn cảnh Đà Lạt siêu chill. (Tự túc chi phí di chuyển và nước uống)\nĐiểm đặc biệt làm nên sức hút của Tiệm Cà Phê Xóm Lèo:\nTầm nhìn rộng mở, ôm trọn thung lũng đèn:  Khi hoàng hôn buông xuống, cả thung lũng trước mặt bừng sáng với những ánh đèn lung linh, tạo nên khung cảnh đẹp tựa tranh vẽ. Đây chắc chắn sẽ là điểm check-in không thể bỏ lỡ dành cho những tâm hồn yêu sự lãng mạn.\nKhông gian thoáng đãng, hòa mình vào thiên nhiên: Thiết kế mở, bàn ghế gỗ mộc mạc kết hợp cùng không khí se lạnh của Đà Lạt mang đến cảm giác thư thái, giúp bạn tận hưởng từng khoảnh khắc chậm rãi và bình yên.\nMenu đa dạng, đậm chất Đà Lạt: Quán phục vụ nhiều loại cà phê, trà thơm cùng những món bánh ngọt hấp dẫn, giúp bạn có một buổi chiều thưởng thức ấm áp bên bạn bè hoặc người thân\nTối: Nghỉ đêm tại Đà Lạt. \n\nNGÀY 3 | CHỢ ĐÀ LẠT – TP. HCM (Ăn sáng) (Ăn trưa tự túc)\nSáng: Quý khách dùng điểm tâm sáng và làm thủ tục trả phòng.\nĐoàn ghé CHỢ ĐÀ LẠT - Du khách sẽ choáng ngợp bởi hàng ngàn loại rau củ, hàng trăm loại trái cây và đủ các loại hoa khoe sắc. \nTrưa: Đoàn dừng chân tại BẢO LỘC. Quý Khách tự túc bữa trưa tại đây. \nTiếp tục hành trình về TP.HCM.\nChiều: Đến TP.HCM. Kết thúc chuyến đi, chia tay đoàn và hẹn gặp lại Quý khách.', '3 sao', 'GIÁ TOUR BAO GỒM:  \n\n- Phương tiện: Xe đời mới, máy lạnh suốt tuyến, phục vụ lịch sự chu đáo, đạt chuẩn du lịch.  \n- Lưu trú: 02 đêm khách sạn 3* (tiêu chuẩn: 02 – 03 – 04 khách/phòng). Quý khách đi nhóm gia đình sẽ ưu tiên ở phòng Family.  \n- Ăn uống: 02 bữa sáng (1 tô + 1 ly) + 02 bữa trưa  \n- Bảo hiểm du lịch nội địa (Không được áp dụng bảo hiểm du lịch cho các trường hợp hợp sau: trên 80 tuổi, tiền sử các bệnh như: tim mạch, đột quỵ, …)  \n- Hướng dẫn viên kinh nghiệm, nhiệt tình, vui vẻ phục vụ đoàn suốt tuyến đi.  \n- Đoàn được phục vụ: Nước suối: 1 chai 500ml/ngày/khách.  \n\nGIÁ TOUR KHÔNG BAO GỒM:  \n\n- Chi phí cá nhân: giặt ủi, điện thoại, thức uống trong các bữa ăn, minibar… và tham quan, vận chuyển ngoài chương trình.  \n- Phụ thu phòng đơn: 700.000 VND  \n- Phụ thu ghế ưu tiên: 100.000 VND/ghế  \n- Vé tham quan Puppy Farm: 100.000 VND/khách  \n- Thức uống tại các điểm café  \n- 01 bữa ăn sáng ngày 1 + 02 bữa ăn tối + 01 bữa trưa ngày về.  \n- Tip (chi phí bồi dưỡng) cho tài xế và hướng dẫn viên.  \n- Thuế VAT  '),
(5, 'TOUR NHA TRANG 3N2Đ | “Trốn Thế Giới” | Hòn Tằm Escape', 'phu-quoc-thien-duong-3n2d', 'tour-nha-trang-3n2d-tron-the-gioi-hon-tam-escape', 2086000.00, 0, 'active', '2026-04-28 02:37:58', '2026-04-30 00:29:11', 'https://datviettour.com.vn/uploads/images/mien-trung/nha-trang/hinh-danh-thang/850px/hon-tam-850px.jpg', 'Tp.Hồ Chí Minh', 'Xe ghế ngồi', 'Thứ 6 hàng tuần', 'Du Lịch Đất Việt', 'NGÀY 1 | TP.HCM - KHÁNH HÒA - CHÙA LONG SƠN ( • Ăn sáng • Ăn trưa • Ăn tối )\n\nSáng: Xe và Hướng dẫn viên đón quý khách tại điểm hẹn, sắp xếp hành lý, gửi lời chào thân ái tới Quý khách. Đến với địa phận tỉnh Đồng Nai, quý khách dừng chân dùng điểm tâm sáng.\n\nTrưa: Đến với Bình Thuận, Quý khách dừng chân dùng bữa trưa. Đoàn dừng chân và chụp hình với Biển Cà Ná.\n\nNgoạn cảnh Cánh Đồng Muối Cà Ná được mệnh danh là “kho muối” lớn nhất nước với những hạt muối đậm đà là một trong những nguyên liệu làm ra loại mắm thơm ngon.\nTiếp tục lộ trình đến với thành phố biển Nha Trang, nhận phòng và nghỉ ngơi.\n\nChiều: Đoàn khởi hành tham quan:\n\nChùa Long Sơn: “Ai về ngắm cảnh Khánh Hoà/ Long Sơn nên ghé/ Tháp Bà đừng quên/ Kim thân Phật tổ nhớ lên/ Nhìn ông Phật trắng ngồi trên lưng trời”\nBảo Tàng Ngọc Trai Hoàng Gia: Chia sẻ về lịch sử thế giới ngọc trai, cách con trai sinh trưởng và cách nuôi cấy để có được viên ngọc trai. Xem kỹ thuật viên tiểu phẫu trai lấy ngọc mà không làm tổn thương con trai, để cùng khám phá bí mật về màu sắc độc nhất vô nhị của con trai. Du khách được tận mắt chiêm ngưỡng các Tặng phẩm quốc gia dành tặng cho Phu nhân Nguyên Thủ các nước như Tặng phẩm dành tặng cho Tổng Thống Mỹ Trump, Phu nhân Tổng Thống Mỹ Barack Obama, Phu nhân Chủ tịch TQ Tập Cận Bình, ...\nTối: Đoàn dùng bữa tối tại nhà hàng. Sau bữa tối bạn có thể tận hưởng không khí trong lành của biển cả ven đường TRẦN PHÚ hoặc vô sắm tại khu CHỢ ĐÊM,thả hồn du dương theo tiếng nhạc của các bạn nghệ sĩ đường phố.\n\nĐỐI VỚI KHÁCH HÀNG MUA GÓI VIP 2 SẼ KHÔNG BAO GỒM BỮA ĂN TỐI\n\nNGÀY 2 | HÒN TẰM - TỰ DO TẮM BIỂN ( • Ăn sáng • Ăn trưa )\n\nĐỐI VỚI KHÁCH HÀNG MUA GÓI VIP 2 - NGÀY 2 FREEDAY: TỰ DO KHÁM PHÁ NHA TRANG\n\nSáng: Đoàn dùng bữa sáng tại Khách sạn. Quý khách chuẩn bị kính bơi, kem chống nắng và áo tắm để bắt đầu hành trình Chinh Phục Đại Dương Xanh:\n\nĐoàn có mặt tại Cảng Vĩnh Trường tàu - Ca nô sẽ đưa quý khách khám phá Vịnh Nha Trang, hòa mình vào không  gian xanh mát của biển cả và đất trời, quý khách ngắm nhìn toàn cảnh các hòn nổi tiếng tại Nha Trang\n\nTrải nghiệm Tắm Bùn Hòn Tằm - Thư Giãn Giữa Thiên Nhiên Biển Đảo: Đến với Hòn Tằm, quý khách sẽ được trải nghiệm dịch vụ tắm bùn khoáng cao cấp giữa không gian biển đảo trong lành - nơi hội tụ giữa thiên nhiên và nghỉ dưỡng\n+ Miễn phí sử dụng khăn, đồ tắm, tủ gửi đồ\n+ Tự do đắm mình trong làn nước biển trong xanh Khu A+B\n+ Ngâm mình trong lớp bùn khoáng ấm mịn, giàu dưỡng chất, không chỉ giúp thư giãn cơ thể mà còn hỗ trợ làm đẹp da, phục hồi năng lượng sau những ngày làm việc căng thẳng.\n+ Hồ bơi vô cực với tầm nhìn hướng biển tuyệt đẹp.\n+ Trải nghiệm tắm thác nước nhân tạo dưới làn nước mát lành\nTrưa: Kết thúc chuyến trải nghiệm, Tàu đưa Quý khách về lại đất liền. Đoàn dùng bữa trưa tại Nhà hàng trễ. Về khách sạn nghỉ ngơi\n\nChiều: Quý khách có thể lựa chọn nghỉ ngơi tắm biển tự do tại resort hoặc:\n\nLựa chọn tự túc chi phí: Khám Phá Vinpearl Harbour với loạt “bom tấn” giải trí hấp dẫn: Đoàn di chuyển lên cáp treo, chinh phục đại dương bao la.\n\nTrải nghiệm mua sắm 24/7 \nThưởng thức ẩm thực tinh hoa đa quốc gia tại các nhà hàng cao cấp (chi phí tự túc).\nHòa mình vào Vũ hội hóa trang Monte Carlo 20 diễn viên hóa thân tạo nên không gian văn hóa Monaco đầy lôi cuốn.\nNgắm hoàng hôn, check in không gian sang trọng, đẳng cấp tại bến Cảng Harbour.\nChương Trình Nhạc Nước - Bản Giao Hưởng Ánh Sáng Và Âm Thanh\nTrải Nghiệm Tatashow - Siêu phẩm thực cảnh đa phương tiện đầu tiên tại Việt Nam vở diễn là hành trình khám phá, phiêu lưu kỳ thú của công chúa Tata và những người bạn thân thiết trong thế giới cổ tích diệu kỳ. Sử dụng công nghệ 3D mapping hiện đại nhất thế giới với trị giá hàng triệu USD đáp ứng cường độ ánh sáng laser cực khủng.\nTối: Tự do thưởng thức ẩm thực và khám phá Nha Trang về đêm.\n\nNGÀY 3 | CHIA TAY NHA TRANG - VƯỜN NHO - TP.HCM ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách dùng điểm tâm sáng. Đoàn làm thủ tục trả phòng. Đoàn di chuyển về Ninh Thuận tham quan:\n\nVườn Nho: Quý khách có cơ hội trực tiếp tìm hiểu thực tế về nghề trồng nho của người dân nơi đây. (tùy theo mùa vụ và giống nhà của từng nhà vườn)\nTrưa: Quý khách dùng bữa trưa tại nhà hàng. Đoàn khởi hành về TP.HCM. Dọc theo quốc lộ, Xe đưa Quý khách dừng chân mua sắm đặc sản địa phương tại các cơ sở uy tín.\n\nChiều: Về đến TPHCM, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYẾN THAM QUAN BỔ ÍCH!\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của Resort/ khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00.', '3-5 sao', 'TIÊU CHUẨN DỊCH VỤ\nXe du lịch 16 - 45 chỗ (phù hợp số lượng) đời mới máy lạnh, ghế bật, phục vụ đưa đón đoàn suốt tuyến.       \nLƯU TRÚ: Tiêu chuẩn 2 - 3 khách/phòng. \nTại Nha Trang:\n+ 3 sao: Ale, Edele, happylight, Tuấn Vũ….\n+ 4 sao: Poseidon, Greenbeach, Erica, TND….\n+ 5 sao: Vesna, TTC, Rigaliagold….\nTại Phú Yên\n+ 3 sao: Green Oasis, Royal khanh ….\n+ 4 sao: Kaya, Wink, Everyday….\nHệ thống lưu trú nêu tên hoặc tương đương\nĂN UỐNG: Nhà hàng địa phương tiêu chuẩn, hợp vệ sinh theo gói dịch vụ.\nHDV: Tiếng Việt thuyết minh và phục vụ Đoàn tham quan theo gói dịch vụ.\nBẢO HIỂM: Bảo hiểm du lịch theo quy định 50.000.000vnđ/vụ\nTHAM QUAN: Bao gồm phí vào cổng tại các điểm tham quan theo gói dịch vụ.\nPHỤC VỤ: Nón du lịch, khăn ướt, nước đóng chai\nVAT: Theo quy định\nDỊCH VỤ CHƯA BAO GỒM\nGói VIP 1: Tự túc bữa ăn tối tại Nha Trang.\nGói VIP 2: Tự túc bữa ăn tối tại Nha Trang - Tự túc bữa ăn tối tại Phú Yên (Giá tham khảo: 200.000vnđ/suất) - Vé cáp treo Vinpearl Harbour (Giá tham khảo: 200.000vnđ/vé)\nTiền TIP cho HDV + Tài xế địa phương\nChi phí cá nhân ngoài chương trình: giặt ủi, điện thoại, minibar…\nQUY ĐỊNH TRẺ EM:\nDưới 05 tuổi: Miễn phí (chi phí phát sinh trên tour gia đình tự chi trả). Hai người lớn chỉ được kèm theo 01 trẻ, từ trẻ thứ hai tính 50% giá tour.\nTừ 05 - dưới 10 tuổi: 70% giá tour người lớn, ngủ ghép với gia đình. Hai người lớn chỉ được kèm theo 01 trẻ, từ trẻ thứ hai tính giá như người lớn.\nTừ 10 tuổi trở lên: giá tour như người lớn.\nĐIỀU KIỆN HỦY TOUR: (Không tính thứ bảy, chủ nhật và ngày lễ)                 \nNếu hủy tour, Quý khách thanh toán các khoản lệ phí hủy tour sau:\n\nHủy tour sau khi đăng kí: 30% giá tour.\nTrước ngày đi 20 Ngày: 50% giá tour.\nTrước ngày đi 10-19 ngày: 75% giá tour.\nTrước ngày đi 0-10 Ngày: 100% giá tour.\nViệc huỷ bỏ chuyến đi phải được thông báo trực tiếp với Công ty, qua email, tin nhắn và phải được Công ty xác nhận. Việc huỷ bỏ bằng điện thoại không được chấp nhận.\nDo tính chất là đoàn ghép khách lẻ, Công ty sẽ có trách nhiệm nhận khách đăng ký cho đủ đoàn (10 khách người lớn trở lên) thì đoàn sẽ khởi hành đúng lịch trình. Nếu số lượng đoàn dưới 10 khách, công ty có trách nhiệm thông báo cho khách trước ngày khởi hành 3 ngày và sẽ thỏa thuận lại ngày khởi hành mới hoặc hoàn trả toàn bộ số tiền đã đặt cọc tour.\nTrong những trường hợp bất khả kháng như: khủng bố, bạo động, thiên tai, lũ lụt, dịch bệnh…(Có văn bản ngừng nhận khách của địa phương) Tuỳ theo tình hình thực tế và sự thuận tiện, an toàn của khách hàng, công ty Du Lịch sẽ chủ động thông báo cho khách hàng sự thay đổi như sau: huỷ hoặc thay thế bằng một chương trình mới với chi phí tương đương chương trình tham quan trước đó. Trong trường hợp chương trình mới có phát sinh thì Khách hàng sẽ thanh toán khoản phát sinh này. Tuy nhiên, mỗi bên có trách nhiệm cố gắng tối đa, giúp đỡ bên bị thiệt hại nhằm giảm thiểu các tổn thất gây ra vì lý do bất khả kháng.');
INSERT INTO `tours` (`id`, `title`, `slug`, `short_description`, `price`, `duration_days`, `status`, `created_at`, `updated_at`, `image`, `place_start`, `vehicle`, `day_start`, `host`, `long_description`, `hotel`, `note`) VALUES
(6, 'TOUR MIỀN BẮC MÙA HÈ 6N5Đ | HÀ NỘI - HẠ LONG - NINH BÌNH - SAPA', 'phu-qu', 'Tham quan thủ đô Hà Nội - Checkin đỉnh Fansipan – Ngoạn cảnh Vịnh Hạ Long – Xuôi thuyền dọc theo giữa cánh đồng lúa thăm Khu du lịch Tràng An -Viếng chùa Bái Đính  – ngôi chùa có nhiều kỷ lục nhất Việt Nam.', 11399000.00, 6, 'active', '2026-04-28 02:37:58', '2026-04-30 02:38:18', 'https://samtenhills.vn/wp-content/uploads/2024/01/dai-bao-thap-kinh-luan-lon-nhat-the-gioi.jpg', 'Tp. Hồ Chí Minh  ', 'Máy bay khứ hồi & xe du lịch đời mới', 'Thứ 3 hàng tuần', 'Du lịch Việt', 'NGÀY 1 | TP.HCM – HÀ NỘI (Ăn trưa, chiều)\nSáng: Quý khách có mặt tại ga quốc nội, sân bay Tân Sơn Nhất trước giờ bay ít nhất ba tiếng.\n\nĐại diện công ty Du Lịch Việt đón và hỗ trợ Quý Khách làm thủ tục đón chuyến bay đi Hà Nội.\nĐến sân bay Nội Bài, Hướng Dẫn Viên đón đoàn, Tham quan thủ đô với:  Đoàn viếng Lăng Chủ tịch Hồ Chí Minh (trừ thứ 2, thứ 6 bảo trì Lăng), Phủ Chủ Tịch, ao cá, nhà sàn Bác Hồ, Chùa Một Cột, Bảo Tàng Hồ Chí Minh.\n \nLăng Chủ tịch Hồ Chí Minh\n \nTrưa: Dùng bữa trưa. \n\nĐòan tiếp tục tham quan chùa Trấn Quốc, Hồ Tây, Hồ Trúc Bạch, Hồ Hoàn Kiếm.Bảo tàng quân sự Việt Nam là một trong các bảo tàng quốc gia và đứng đầu trong hệ thống Bảo tàng Quân đội, hiện đang lưu giữ, trưng bày hơn 15 vạn tài liệu, hiện vật, trong đó có nhiều sưu tập độc đáo và 4 Bảo vật Quốc gia, gồm máy bay MiG-21 số hiệu 4324, máy bay MiG-21 số hiệu 5121, Bản đồ Quyết tâm chiến dịch Hồ Chí Minh và xe tăng T-54B số hiệu 843.\nlang-bac-du-lich-viet\nTối: Dùng bữa tối. Nghỉ đêm tại Hà Nội.\n\nNGÀY 2 | HÀ NỘI – LÀO CAI – SAPA (Ăn sáng, trưa, chiều)\nSáng: Dùng buffet sáng tại khách sạn.\n\nĐoàn khởi hành đến Lào Cai trên con đường cao tốc dài nhất Việt Nam - nối liền giữa Hà Nội và các tỉnh Tây Bắc.\nTrưa: Dùng bữa trưa. \n\nĐoàn tiếp tục đến thị trấn vùng cao Sapa, tận hưởng cảnh sắc núi rừng như tranh vẽ và khám phá cuộc sống của đồng bào dân tộc ít người miền Tây Bắc.\n \nThị trấn vùng cao Sapa\nThị trấn vùng cao Sapa\n \nThăm bản Cát Cát, tìm hiểu nghề dệt nhuộm của dân tộc H’Mông và trạm thủy điện Cát Cát thời Pháp – nơi có 3 dòng nước hợp nhau thành dòng suối Mường Hoa.\n \nBản Cát Cát\nBản Cát Cát\n \nTối: Dùng bữa tối. Nghỉ đêm tại Sapa.\n\nĐoàn tự do tham dự đêm chợ Tình (nếu đi vào tối thứ 7). \nNGÀY 3 | FANSIPAN – HÀ NỘI(Ăn sáng, trưa, chiều tự túc)\nSáng: Dùng buffet sáng tại khách sạn.\n\nĐoàn khởi hành tham quan chinh phục Fansipan, ngọn núi cao nhất Việt Nam (3.143m) thuộc dãy núi Hoàng Liên Sơn, cách thị trấn Sa Pa khoảng 9km về phía tây nam.\nChinh phục Fansipan\nChinh phục Fansipan\n \nDu khách chinh phục \"Nóc nhà Đông Dương\" với hệ thống cáp treo Fansipan SaPa dài 6,2km đạt 2 kỷ lục Guinness thế giới: cáp treo ba dây có độ chênh giữa ga đi và ga đến lớn nhất thế giới: 1410m và cáp treo ba dây dài nhất thế giới: 6292.5m. Từ tuyến cáp treo, du khách có thể cảm nhận được thiên nhiên hùng vĩ, chiêm ngưỡng khung cảnh thung lũng Mường Hoa và vườn quốc gia Hoàng Liên từ trên cao. Ngoài ra, du khách còn có thể đến hành hương tại Khu du lịch tâm linh – Chùa Trình, Chùa Hạ tại ga đến (chi phí cáp treo tự túc).\n \nThung lũng Mường Hoa\nThung lũng Mường Hoa\nTrưa: Dùng bữa trưa.Đoàn trở về Hà Nội\n\nTối:. Nghỉ đêm tại Hà Nội. Buổi tối quý khách tự do khám phá ẩm thực Hà Nội, Hướng dẫn viên sẽ chia sẻ các quán ngon đến quý khách.Hoặc quý khách có thể tham gia phố đi bộ, chợ đêm, chợ Đồng Xuân, thưởng thức đặc sản và mua quà lưu niệm.\n\nNGÀY 4 | HÀ NỘI – YÊN TỬ -  HẠ LONG (Ăn  Sáng,trưa, chiều)\nSáng: Dùng buffet sáng tại khách sạn\n\nHướng dẫn viên đón đoàn Khởi hành đến Hạ Long, trên đường dừng chân tham quan núi Yên Tử - ngọn núi cao 1068 m so với mực nước biển, một thắng cảnh thiên nhiên còn lưu giữ hệ thống các di tích lịch sử văn hóa gắn với sự ra đời, hình thành và phát triển của thiền phái Trúc Lâm Yên Tử, được mệnh danh là “đất tổ Phật giáo Việt Nam”.\n \nĐỉnh núi Yên Tử\nĐỉnh núi Yên Tử\nTrưa: Dùng cơm trưa. \n\nQuý khách lên núi bằng cáp treo (chi phí cáp treo tự túc), tham quan chùa Hoa Yên, Bảo Tháp, Trúc Lâm Tam Tổ, Hàng Tùng 700 tuổi…xuống núi tham quan Thiền Viện Trúc Lâm với quả cầu Như Ý nặng 6 tấn được xếp kỷ lục guiness Việt Nam.\nĐoàn khởi hành về thành phố Hạ Long.\nChiều: Dùng bữa chiều. Nghỉ đêm tại Hạ Long.\n\nQuý khách tự do dạo phố, mua sắm tại chợ đêm hoặc tham gia khu Sunworld Hạ Long Park với tất cả khu trò chơi trong nhà, ngoài trời hoành tráng có các khu Công viên Rồng, Cáp treo Nữ Hoàng vòng quay Sun wheel…(chi phí tự túc). \nNGÀY 5 | HẠ LONG – NINH BÌNH (Ăn sáng, trưa, chiều)\nSáng: Dùng buffet sáng tại khách sạn.\n\nĐoàn xuống thuyền ngoạn cảnh Vịnh Hạ Long – Di sản thiên nhiên thế giới với hàng ngàn đảo đá có hình dạng kỳ vĩ - chiêm ngưỡng vẻ đẹp trau chuốt, lộng lẫy của động Thiên Cung, vẻ đẹp siêu nhiên của hòn Đỉnh Hương, Gà Chọi, Chó Đá… \nVịnh Hạ Long\nVịnh Hạ Long\nTrưa: Dùng bữa trưa.\n\nKhởi hành đi Ninh Bình, nơi có danh thắng Tràng An nơi được UNESCO công nhận là di sản hỗn hợp văn hóa và thiên nhiên của thế giới. \n \nDanh thắng Tràng An\nDanh thắng Tràng An\n \nTham quan chùa Bái Đính – ngôi chùa có nhiều kỷ lục nhất Việt Nam (ngôi chùa có diện tích rộng nhất – Tượng Phật bằng đồng lớn nhất Việt Nam).\nTối: Dùng bữa tối. Nghỉ đêm tại Ninh Bình.\n\nQuý khách có thể tự do dạo phố, thưởng thức các món đặc sản Ninh Binh như thịt dê núi, ốc núi, nem Yên Mạc, cơm cháy,...\nNGÀY 6 | NINH BÌNH – HÀ NỘI – TP.HCM (Ăn sáng, trưa)\nSáng: Dùng buffet sáng tại khách sạn.\n\nĐoàn xuôi thuyền đi dọc theo suối giữa cánh đồng lúa thăm Khu du lịch Tràng An nơi những dải đá vôi, thung lũng và những sông ngòi đan xen tạo nên một không gian huyền ảo, kỳ bí, quý khách đi đò thăm Hang sáng, Hang tối, Hang nấu rượu và tìm hiểu văn hóa lịch sử nơi đây.\nTrưa: Dùng bữa trưa.Đoàn trở về Hà Nội.\nHướng dẫn viên tiễn đoàn ra sân bay Nội Bài đón chuyến bay về TP.HCM. Kết thúc chương trình tham quan, chia tay và hẹn gặp lại.\nGiờ bay có thể thay đổi theo quy định của hàng không. Du Lịch Việt sẽ thay đổi chương trình cho phù hợp với giờ bay mới nhưng sẽ không chịu trách nhiệm về các khoản chi phí phát sinh.', '3-4 sao', 'GIÁ TOUR BAO GỒM:\nVé máy bay khứ hồi TP.HCM – HUẾ/ĐÀ NẴNG – TP.HCM (Vietjet…)\nXe tham quan (xe 16 chỗ, 29 chỗ, 35 chỗ, 45 chỗ) tùy theo số lượng khách thực tế trên chuyến đi.\nKhách sạn tiêu chuẩn đầy đủ tiện nghi 2 khách người lớn/ phòng. Nếu lẻ người thứ 3, đóng phụ phí phòng đơn hoặc ngủ ghép phòng 3 khách.\nKhách sạn 3 sao tại Huế: Duy Tân, Newstar, … hoặc tương đương.\nKhách sạn 4 sao tại Huế: Thanh Lịch, Cherish, … hoặc tương đương.\nKhách sạn 3 sao tại Đà Nẵng: Golden Rose, … hoặc tương đương.\nKhách sạn 4 sao tại Đà Nẵng: Hùng Anh, Paracel, …hoặc tương đương.\nKhách sạn 3, 4 sao tại Quảng Bình: Tân Bình, Vĩnh Hoàng, …. hoặc tương đương.\nĂn uống theo chương trình:\nĂn phụ: 3 bữa buffet sáng tại khách sạn. \nĂn chính: 6 bữa chính tiêu chuẩn 130.000 đồng/bữa.\nVé tham quan theo chương trình.\nHướng dẫn viên tiếng Việt vui vẻ nhiệt tình suốt chuyến đi.\nBảo hiểm với mức bồi thường tối đa 100.000.000 đồng/trường hợp. Không áp dụng cho khách từ 80 tuổi trở lên.\nQuà tặng: nón du lịch Việt, nước, khăn lạnh.\nThuế VAT.\nKHÔNG BAO GỒM\nBia hay nước ngọt trong các bữa ăn.\nTham quan ngoài chương trình.\nChi phí cá nhân: điện thoại, giặt ủi…\n01 bữa trưa ngày thứ 4 theo chương trình.\nVé cáp treo Bà Nà, công viên Châu Á, ca Huế, …\nChi phí bãi biển: dù, võng, tắm nước ngọt…\nGIÁ VÉ TRẺ EM:\n\nTrẻ em dưới 02 tuổi: miễn giá tour, giá vé máy bay theo quy định của hãng hàng không, Cha, Mẹ hoặc người thân đi kèm tự lo các chi phí ăn, ngủ, tham quan (nếu có) cho bé.\nTrẻ em từ 02 – dưới 05 tuổi: 100 % giá vé máy bay; miễn giá tour. Cha, Mẹ hoặc người thân đi kèm tự lo các chi phí ăn, ngủ, tham quan (nếu có) cho bé. Hai người lớn chỉ kèm 1 trẻ em dưới 5 tuổi, trẻ em thứ 2 trở lên phải mua ½ vé tour.\nTrẻ em từ 05 – dưới 11 tuổi: 100 % giá vé máy bay; 60% giá tour. Bao gồm các dịch vụ ăn uống, ghế ngồi trên xe và ngủ chung với gia đình. Hai người lớn chỉ được kèm 1 trẻ em từ 5 đến dưới 11 tuổi, trẻ em thứ 2 trở lên Cha, Mẹ nên mua thêm 1 suất giường đơn.\nTừ 11 tuổi trở lên: 100% giá tour và tiêu chuẩn như người lớn.\nĐĂNG KÝ HỦY VÉ:\n\nSau khi xác nhận và thanh toán (ít nhất 50% tiền cọc giữ chỗ và thanh toán đủ 100% tổng giá trị tiền tour trước 15 ngày khởi hành).\n Khi đến ngày thanh toán đủ 100% tổng giá trị tiền tour, nếu Quý khách không thanh toán đúng hạn và đúng số tiền được xem như Quý khách tự ý huỷ tour và mất hết số tiền đã đặt cọc giữ chỗ.\nVé Máy Bay / vé xe lửa / vé tàu cao tốc được xuất ngay sau khi quý khách đóng tiền và có xác nhận sự chính xác về họ, tên (đúng từng ký tự ghi trong hộ chiếu hoặc CCCD), ngày-tháng-năm sinh … của hành khách theo yêu cầu của hãng vận chuyển. Mọi sự thay đổi liên quan đến vé đã xuất: ngày giờ đi, tên hành khách, hủy vé, quý khách vui lòng chịu chi phí theo qui định sau:\n*    Ngay sau khi Quý khách đăng ký tour nếu hủy sẽ bị phạt tiền tour và vé máy bay: \n\nNgay sau khi đặt cọc hoặc thanh toán hoặc trước 15 ngày: phí hủy 30% tiền tour+ 100% Vé máy bay.\nHủy 10 ngày trước ngày khởi hành: phí hủy 50% tiền tour + 100% vé máy bay.\nHủy 07 ngày trước ngày khởi hành: phí hủy 70% tiền tour  + 100% vé máy bay.\nHủy  05 ngày trước ngày khởi hành: phí hủy 100% tiền tour  + 100% vé máy bay.\nTrường hợp quý khách đến trễ giờ khởi hành được tính là hủy 5 ngày trước ngày khởi hành.\nDo tính chất là đoàn ghép khách lẻ, Du Lịch Việt sẽ có trách nhiệm nhận khách đăng ký cho đủ đoàn (10 khách người lớn trở lên) thì đoàn sẽ khởi hành đúng lịch trình. Nếu số lượng đoàn dưới 10 khách, công ty có trách nhiệm thông báo cho khách trước ngày khởi hành 3 ngày và sẽ thỏa thuận lại ngày khởi hành mới hoặc hoàn trả toàn bộ số tiền đã đặt cọc tour.\nViệc huỷ bỏ chuyến đi phải được thông báo trực tiếp với Công ty hoặc qua fax, email, tin nhắn và phải được Công ty xác nhận. Việc huỷ bỏ bằng điện thoại không được chấp nhận.\nCác ngày đặt cọc, thanh toán, huỷ và dời tour: không tính thứ 7, Chủ Nhật.\nTrong những trường hợp bất khả kháng như: khủng bố, bạo động, thiên tai, lũ lụt… Tuỳ theo tình hình thực tế và sự thuận tiện, an toàn của khách hàng, công ty Du Lịch sẽ chủ động thông báo cho khách hàng sự thay đổi như sau: huỷ hoặc thay thế bằng một chương trình mới với chi phí tương đương chương trình tham quan trước đó. Trong trường hợp chương trình mới có phát sinh thì Khách hàng sẽ thanh toán khoản phát sinh này. Tuy nhiên, mỗi bên có trách nhiệm cố gắng tối đa, giúp đỡ bên bị thiệt hại nhằm giảm thiểu các tổn thất gây ra vì lý do bất khả kháng.…\nĐối với sự thay đổi lịch trình, giờ bay do lỗi của hãng hàng không, tàu hoả, tàu thuỷ, Du Lịch Việt sẽ không chịu trách nhiệm bất kỳ phát sinh nào do lỗi trên như: phát sinh bữa ăn, nhà hàng, khách sạn, phương tiện di chuyển, hướng dẫn viên, ….\nLƯU Ý:\n\nKhi đăng ký tour khách hàng bắt buộc phải gởi giấy tờ tùy thân cho đơn vị du lịch để vào tên xuất vé và mua bảo hiểm du lịch. Những giấy tờ cá nhân của khách hàng (CCCD hoặc Passport) thuộc về trách nhiệm của khách hàng. Mọi vấn đề như hình ảnh, thông tin giấy tờ trong bản gốc bị mờ, không rõ ràng; Passport, CCCD hết hạn,… không đúng quy định của pháp luật Việt Nam, Du Lịch Việt sẽ không chịu trách nhiệm và sẽ không hoàn trả bất cứ chi phí phát sinh nào.\nĐối với khách Nước ngoài/Việt Kiều: Khi đi tour phải mang theo đầy đủ Passport (Hộ Chiếu), Visa Việt Nam bản chính còn hạn sử dụng làm thủ tục lên máy bay theo qui định hàng không. \nTrẻ em (dưới 12 tuổi) khi đi du lịch mang theo giấy khai sinh (bản chính hoặc sao y có thị thực còn hạn sử dụng) để  làm thủ tục hàng không.\nQuý khách từ 14 tuổi bắt buộc phải có CCCD hoặc Passport (còn hạn sử dụng), KHÔNG SỬ DỤNG GIẤY KHAI SINH. Nếu Quý khách từ 14 tuổi chưa có CCCD hoặc Passport bắt buộc phải có Giấy xác nhận nhân thân (Có xác nhận chính quyền (còn hạn sử dụng)) + Giấy khai sinh.\nMột số thứ tự, chi tiết trong chương trình; giờ bay; giờ xe lửa; giờ tàu cao tốc có thể thay đổi để phù hợp với tình hình thực tế của chuyến đi (thời tiết, giao thông…)\nQui định nhận & trả phòng tại các khách sạn/resort: nhận phòng sau 14H00 và trả phòng trước 12H00 .\nHành lý và tư trang du khách tự bảo quản trong quá trình du lịch .\nVì lý do sức khỏe và an toàn vệ sinh thực phẩm, Quý Khách vui lòng không mang thực phẩm từ bên ngoài vào nhà hàng, khách sạn. Đối với thức uống khi mang vào phải có sự đồng ý của nhà hàng, khách sạn và bị tính phí nếu có.\nKÍNH CHÚC QUÝ KHÁCH CHUYẾN ĐI THÚ VỊ VÀ BỔ ÍCH !'),
(7, 'TOUR PHÚ YÊN - NHA TRANG 3N3Đ | Về Nơi Hoa Vàng - Đến Nơi Biển Xanh', 'phu-qu1', 'Khám phá Vinpearl Harbour - Tham quan Gành Đá Đĩa - Check-in Tháp Nghinh Phong', 2586000.00, 3, 'active', '2026-04-29 17:33:58', '2026-04-30 02:38:26', 'https://datviettour.com.vn/uploads/images/mien-trung/phu-yen/danh-thang/850px/phu-yen-2.jpg', 'Tp.Hồ Chí Minh', 'Xe ghế ngồi', 'Thứ 5 hàng tuần', 'Du lịch Đất Việt', 'ĐÊM 1 | TP. HCM - PHÚ YÊN\nTối: Quý khách có mặt tại điểm tập trung. Xe và hướng dẫn viên đón đoàn theo giờ đã hẹn. Chào mừng các thành viên đã đồng hành và gửi những phần quà thiết yếu cho đoàn. Quý khách nghỉ đêm trên xe.\n\nQuý khách nghỉ đêm trên xe.\n\nNGÀY 1 | PHÚ YÊN - GÀNH ĐÁ ĐĨA - NHÀ THỜ MẰNG LĂNG - THÁP NGHINH PHONG ( • Ăn sáng • Ăn trưa • Ăn tối )\n\nSáng: Qúy khách dừng chân vệ sinh cá nhân và dùng bữa sáng tại nhà hàng. Tiếp tục chương trình đoàn khởi hành tham quan:\n\nGành Đá Đĩa: là một trong sáu loại hình địa chất độc đáo trên thế giới. Muôn vàn hòn đá với đủ loại hình dạng như tròn, ngũ giác, đa giác … được xếp chồng lên nhau, hoặc dựng đứng thành hình cột như những chồng đĩa lớn, có màu đen huyền và vàng sáng trải rộng ra biển, nên nhiều người nhìn từ xa còn gọi đây là tổ ông thiên tạo khổng lồ.\nNhà Thờ Mằng Lăng: Không chỉ gây ấn tượng với kiến trúc cổ xưa, còn là nơi lưu trữ bộ sách phát hành bằng chữ Quốc ngữ đầu tiên trên Thế giới, nghe kể về câu chuyện của Thánh Anrê Phú Yên, tìm hiểu kiến trúc nhà thờ theo lối La Mã.\nTrưa: Ngoạn cảnh Đầm Ô Loan - Đập Tam Giang: Đứng trên đèo Quán Cau nhìn xuống, đầm Ô Loan rộng khoảng 1200 hecta. Nhìn từ xa, đầm có hình dáng như một con phượng xòe khoe đôi cánh. Nằm vắt ngang dòng sông Cái, đập TAM GIANG là một công trình thủy lợi vô cùng quan trọng (nếu thuận tiện đoàn dừng chụp ảnh phía trên).\n\nĐoàn dùng bữa trưa tại khu vực Đầm Ô Loan.\n\nChiều: Quý khách tham quan:\n\nQuảng Trường Nghinh Phong: Công trình tháp được chia làm hai phần, có 50 khối đá xếp liền kề. Những khối đá này chính là hình ảnh đại diện cho 100 người con trong truyền thuyết, được chia hai nửa với ý nghĩa “lên rừng - xuống biển”. Tòa tháp gây ấn tượng với điểm nhấn được tạo hình Ghềnh Đá Đĩa, lõi bằng bê tông cốt thép, bên ngoài ốp đá granite. Về đêm, nơi này được chiếu sáng với công nghệ Bobine Tesia, 3D mapping và laze cường độ cao tạo nên vũ điệu ánh sáng đa sắc màu.\nTối: Đoàn dùng bữa tối tại nhà hàng.\n\nĐối với khách hàng mua GÓI VIP 2 buổi chiều sẽ không bao gồm ăn tối.\n\nThành phố Tuy Hòa không chỉ nổi tiếng với đường bờ biển trải dài dọc theo thành phố. Về đêm quảng trường lung linh ánh đèn với nhiều hoạt động giải trí và khu chợ Đêm Tuy Hòa với nhiều hàng quán dân giã hấp dẫn như bánh hỏi lòng heo, sò huyết Đầm Ô Loan, cá ngừ đại dương với hương vị đặc trưng sẽ khiến bạn nhớ mãi.\n\nNGÀY 2 | PHÚ YÊN - NHA TRANG - NHÀ HÁT ĐÓ - VINPEARL HARBOUR ( • Ăn sáng • Ăn trưa )\n\nSáng: Đoàn dùng bữa sáng và làm thủ tục trả phòng, đoàn khởi hành đi Nha Trang. Đến nơi, quý khách tham quan:\n\nNhà Hát Đó: Được lấy cảm hứng từ một dụng cụ bắt cá dân gian của Việt Nam, nhà hát Đó Nha Trang với kiến trúc độc đáo mang đậm nét văn hoá bản địa là địa điểm check-in mới đầy hấp dẫn cho du khách khi đến với xứ biển tuyệt vời này.\nBảo Tàng Ngọc Trai Hoàng Gia: Chia sẻ về lịch sử thế giới ngọc trai, cách con trai sinh trưởng và cách nuôi cấy để có được viên ngọc trai. Xem kỹ thuật viên tiểu phẫu trai lấy ngọc mà không làm tổn thương con trai, để cùng khám phá bí mật về màu sắc độc nhất vô nhị của con trai. Du khách được tận mắt chiêm ngưỡng các Tặng phẩm quốc gia dành tặng cho Phu nhân Nguyên Thủ các nước như Tặng phẩm dành tặng cho Tổng Thống Mỹ Trump, Phu nhân Tổng Thống Mỹ Barack Obama, Phu nhân Chủ tịch TQ Tập Cận Bình, ...\nHòn Chồng: một thắng cảnh tự nhiên nằm ở bờ biển phía Bắc thành phố Nha Trang. Nơi đây du khách có thể di chuyển vài bước đã chạm đến sóng biển hoặc chân đồi. Nhiều người bảo, Hòn Chồng là nơi giao nhau giữa biển và núi. Quần thể đá Hòn Chồng từ lâu đã trở thành điểm du lịch giàu tính nhân văn. Điều kỳ thú là những tảng đá lớn nằm chồng chất lên nhau bao đời nay nhưng sóng biển và mưa gió không thể xô ngã.\nTrưa: Đoàn dùng bữa tại nhà hàng. Sau bữa trưa, đoàn về khách sạn nhận phòng nghỉ ngơi.\n\nChiều: Đoàn tham gia chương trình mới lạ - độc đáo: Khám Phá Vinpearl Harbour với loạt “bom tấn” giải trí hấp dẫn: Đoàn di chuyển lên cáp treo, chinh phục đại dương bao la.\n\nTrải nghiệm mua sắm 24/7 \nThưởng thức ẩm thực tinh hoa đa quốc gia tại các nhà hàng cao cấp (chi phí tự túc).\nHòa mình vào Vũ hội hóa trang Monte Carlo 20 diễn viên hóa thân tạo nên không gian văn hóa Monaco đầy lôi cuốn.\nNgắm hoàng hôn, check in không gian sang trọng, đẳng cấp tại bến Cảng Harbour.\nĐối với khách hàng mua GÓI VIP 2 buổi chiều sẽ không bao gồm Vinpearl Harbour\n\nTối: Tự do thưởng thức ẩm thực đặc sản Nha Trang.\n\n20:00 Đoàn về lại đất liền. Xe đón quý khách về khách sạn nghỉ ngơi.\n\nNGÀY 3 | NHA TRANG - TP.HỒ CHÍ MINH ( • Ăn sáng • Ăn trưa )\nSáng: Quý khách dùng điểm tâm sáng tại khách sạn. Làm thủ tục trả phòng, Đoàn khởi hành về TP.HCM. Dọc theo quốc lộ, Xe đưa Quý khách dừng chân mua sắm đặc sản địa phương tại các cơ sở uy tín.\n\nTrưa: Quý khách dùng bữa trưa tại nhà hàng thuận tiện. Khởi hành về TP.HCM.\n\nChiều: Về đến TPHCM, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYẾN THAM QUAN VUI VẺ - BỔ ÍCH\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của Resort/ khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00\nNếu quý khách đến sớm, Resort/ khách sạn sẽ bố trí cho nhận phòng trong trường hợp có phòng trống. Trường hợp chưa có phòng, quý khách vui lòng dùng nước mát trong thời gian chờ đợi. ', '3-5 sao', 'TIÊU CHUẨN DỊCH VỤ\nXe du lịch 16 - 45 chỗ (phù hợp số lượng) đời mới máy lạnh, ghế bật, phục vụ đưa đón đoàn suốt tuyến.       \nLƯU TRÚ: Tiêu chuẩn 2 - 3 khách/phòng. \nTại Nha Trang:\n+ 3 sao: Ale, Edele, happylight, Tuấn Vũ….\n+ 4 sao: Poseidon, Greenbeach, Erica, TND….\n+ 5 sao: Vesna, TTC, Rigaliagold….\nTại Phú Yên\n+ 3 sao: Green Oasis, Royal khanh ….\n+ 4 sao: Kaya, Wink, Everyday….\nHệ thống lưu trú nêu tên hoặc tương đương\nĂN UỐNG: Nhà hàng địa phương tiêu chuẩn, hợp vệ sinh theo gói dịch vụ.\nHDV: Tiếng Việt thuyết minh và phục vụ Đoàn tham quan theo gói dịch vụ.\nBẢO HIỂM: Bảo hiểm du lịch theo quy định 50.000.000vnđ/vụ\nTHAM QUAN: Bao gồm phí vào cổng tại các điểm tham quan theo gói dịch vụ.\nPHỤC VỤ: Nón du lịch, khăn ướt, nước đóng chai\nVAT: Theo quy định\nDỊCH VỤ CHƯA BAO GỒM\nGói VIP 1: Tự túc bữa ăn tối tại Nha Trang.\nGói VIP 2: Tự túc bữa ăn tối tại Nha Trang - Tự túc bữa ăn tối tại Phú Yên (Giá tham khảo: 200.000vnđ/suất) - Vé cáp treo Vinpearl Harbour (Giá tham khảo: 200.000vnđ/vé)\nTiền TIP cho HDV + Tài xế địa phương\nChi phí cá nhân ngoài chương trình: giặt ủi, điện thoại, minibar…\nQUY ĐỊNH TRẺ EM:\nDưới 05 tuổi: Miễn phí (chi phí phát sinh trên tour gia đình tự chi trả). Hai người lớn chỉ được kèm theo 01 trẻ, từ trẻ thứ hai tính 50% giá tour.\nTừ 05 - dưới 10 tuổi: 70% giá tour người lớn, ngủ ghép với gia đình. Hai người lớn chỉ được kèm theo 01 trẻ, từ trẻ thứ hai tính giá như người lớn.\nTừ 10 tuổi trở lên: giá tour như người lớn.\nĐIỀU KIỆN HỦY TOUR: (Không tính thứ bảy, chủ nhật và ngày lễ)                 \nNếu hủy tour, Quý khách thanh toán các khoản lệ phí hủy tour sau:\n\nHủy tour sau khi đăng kí: 30% giá tour.\nTrước ngày đi 20 Ngày: 50% giá tour.\nTrước ngày đi 10-19 ngày: 75% giá tour.\nTrước ngày đi 0-10 Ngày: 100% giá tour.\nViệc huỷ bỏ chuyến đi phải được thông báo trực tiếp với Công ty, qua email, tin nhắn và phải được Công ty xác nhận. Việc huỷ bỏ bằng điện thoại không được chấp nhận.\nDo tính chất là đoàn ghép khách lẻ, Công ty sẽ có trách nhiệm nhận khách đăng ký cho đủ đoàn (10 khách người lớn trở lên) thì đoàn sẽ khởi hành đúng lịch trình. Nếu số lượng đoàn dưới 10 khách, công ty có trách nhiệm thông báo cho khách trước ngày khởi hành 3 ngày và sẽ thỏa thuận lại ngày khởi hành mới hoặc hoàn trả toàn bộ số tiền đã đặt cọc tour.\nTrong những trường hợp bất khả kháng như: khủng bố, bạo động, thiên tai, lũ lụt, dịch bệnh…(Có văn bản ngừng nhận khách của địa phương) Tuỳ theo tình hình thực tế và sự thuận tiện, an toàn của khách hàng, công ty Du Lịch sẽ chủ động thông báo cho khách hàng sự thay đổi như sau: huỷ hoặc thay thế bằng một chương trình mới với chi phí tương đương chương trình tham quan trước đó. Trong trường hợp chương trình mới có phát sinh thì Khách hàng sẽ thanh toán khoản phát sinh này. Tuy nhiên, mỗi bên có trách nhiệm cố gắng tối đa, giúp đỡ bên bị thiệt hại nhằm giảm thiểu các tổn thất gây ra vì lý do bất khả kháng.'),
(8, 'TOUR NHA TRANG 3N2Đ | Mirae Park Bãi Sỏi - Tắm Bùn Khoáng  ', 'tour-nha-trang-3n2d-mirae-park-bai-soi-tam-bun-khoang', 'Check-in Mirae Park Bãi Sỏi - Show Thực Cảnh “Ánh Sáng Huyền Thoại”', 2286000.00, 3, 'active', '2026-04-29 17:33:58', '2026-04-30 00:10:05', 'https://datviettour.com.vn/uploads/images/mien-trung/nha-trang/hinh-danh-thang/850px/nha-trang-03-850px.png', 'Tp.Hồ Chí Minh', 'Xe du lịch đời mới', 'Thứ 5,6,7 hàng tuần', 'Du lịch Đất Việt', 'NGÀY 1 | TP. HCM - KHÁNH HÒA - TẮM BÙN KHOÁNG ( • Ăn sáng • Ăn trưa • Ăn tối )\n\nSáng: Quý khách có mặt tại điểm tập trung. Xe và hướng dẫn viên đón đoàn theo giờ đã hẹn. Chào mừng các thành viên đã đồng hành và gửi những phần quà thiết yếu cho đoàn. trên dung đường di chuyển, đoàn dung chân dùng bữa sáng tại nhà hàng, sau đó tiếp tục lộ trình đi Nha Trang. \n\nTrưa: Đoàn dùng bữa trưa tại nhà hàng. Về khách sạn nhận phòng và nghỉ ngơi tự do.\n\nChiều: Đoàn thư giãn Tắm Bùn Khoáng Tháp Bà (bao vé tắm khoáng) với nguồn bùn khoáng thiên nhiên giúp loại bỏ các độc tố bên trong cơ thể, kích thích tuần hoàn máu và thúc đẩy da sản sinh collagen. Giữa không gian yên bình, cơ thể được thư giãn và giác quan bừng tỉnh.\n\nNgâm khoáng nóng thiên nhiên (30 phút)\nMassage cơ thể bằng hệ thống ôn tuyền thủy liệu pháp và hồ Jacuzzi\nTắm hồ bơi và thác nước khoáng thiên nhiên ấm và mát\nĐồ tắm, khăn tắm, tủ giữ đồ, nước suối\nQuý khách có thể tự túc mua thêm gói dịch vụ tắm bùn, tắm thảo dược,….theo nhu cầu cá nhân\nTối: Đoàn ăn tối nhà hàng, có thể mua thêm gói thưởng thức trải nghiệm Show diễn Thực Cảnh \"Ánh Sáng Huyền Thoại\" (tự túc phí theo nhu cầu cá nhân).\n\nTại thành phố Nha Trang, từ lâu đã nổi tiếng với các một loạt các hoạt động về đêm níu giữ du khách đến đây. Bạn có thể tận hưởng không khí trong lành của biển cả ven đường TRẦN PHÚ, hoặc vô tư mua sắm tại các cung đường khu CHỢ ĐÊM, hay thả hồn du dương theo tiếng nhạc của các bạn nghệ sĩ đường phố. Tất cả tạo nên một bức tranh về THÀNH PHỐ NÁO NHIỆT hấp dẫn du khách.\n\nNGÀY 2 | MIRAE BÃI SỎI - CITY TOUR NHA TRANG ( • Ăn sáng • Ăn trưa )\n\nSáng: Đoàn dùng điểm tâm sáng, Xe đưa Đoàn đến Cảng, Đoàn đi tàu đến với Bãi Sỏi, bắt đầu khám phá cuộc sống và văn hóa của ngư dân vịnh biển Nha Trang.\n\nKhu Du Lịch Mirae - Maldives thu nhỏ giữa lòng Nha Trang. Với nhiều góc check in độc đáo, cùng bãi biển xanh. Quý khách trải nghiệm tham gia các hoạt động:\n\nSân bọt tuyết (10h00 – 11h00)\nSnake show\nSân bóng chuyền, ném phi tiêu\nKhu vận động cho trẻ em\nPhao nổi trên biển, áo phao, chèo sup\nTắm nước ngọt, vòi sen\nNước welcome và trái cây\nGhế nằm, khăn tắm\nTrưa: Đoàn dùng bữa tại nhà hàng.\n\nChiều: Xe đưa đoàn về lại khách sạn nghỉ ngơi tự do.\n\nChùa Long Sơn: “ Ai về ngắm cảnh Khánh Hoà/Long Sơn nên ghé/ Tháp Bà đừng quên/Kim thân Phật tổ nhớ lên/Nhìn ông Phật trắng ngồi trên lưng trời”  Câu ca dao len nhẹ lòng người, dẫn dắt bước chân du khách hành hương lên đồi Trại Thủy vãn cảnh chùa, chiêm bái Kim thân Phật tổ và ngắm nhìn Nha Trang vươn dài theo mép biển.\nNhà Hát Đó: Được lấy cảm hứng từ một dụng cụ bắt cá dân gian của Việt Nam, nhà hát Đó Nha Trang với kiến trúc độc đáo mang đậm nét văn hoá bản địa là địa điểm check-in mới đầy hấp dẫn cho du khách khi đến với xứ biển tuyệt vời này.\nBảo Tàng Ngọc Trai Hoàng Gia: Chia sẻ về lịch sử thế giới ngọc trai, cách con trai sinh trưởng và cách nuôi cấy để có được viên ngọc trai. Xem kỹ thuật viên tiểu phẫu trai lấy ngọc mà không làm tổn thương con trai, để cùng khám phá bí mật về màu sắc độc nhất vô nhị của con trai. Du khách được tận mắt chiêm ngưỡng các Tặng phẩm quốc gia dành tặng cho Phu nhân Nguyên Thủ các nước như Tặng phẩm dành tặng cho Tổng Thống Mỹ Trump, Phu nhân Tổng Thống Mỹ Barrack Obama, Phu nhân Chủ tịch TQ Tập Cận Bình, ...\nTối: Đoàn tự túc ăn tối trải nghiệm ẩm thực nha trang với các đặc sản nổi tiêng: Nem Nướng, Bò lạc cảnh, các loại hải sản...,\n\nQuý khách nghỉ ngơi tự do. Tại thành phố Nha Trang, từ lâu đã nổi tiếng với các một loạt các hoạt động về đêm níu giữ du khách đến đây. Bạn có thể tận hưởng không khí trong lành của biển cả ven đường TRẦN PHÚ, hoặc vô tư mua sắm tại các cung đường khu CHỢ ĐÊM, hay thả hồn du dương theo tiếng nhạc của các bạn nghệ sĩ đường phố. Tất cả tạo nên một bức tranh về THÀNH PHỐ NÁO NHIỆT hấp dẫn du khách.\n\nNGÀY 3 | CHIA TAY NHA TRANG ( • Ăn sáng • Ăn trưa )\n\nSáng: Quý khách dùng điểm tâm sáng. Làm thủ tục trả phòng, xe đưa đoàn tham quan:\n\nTrưa: Quý khách dùng bữa trưa tại Cà Ná. Đoàn khởi hành về TP.HCM. Dọc theo quốc lộ, Xe đưa Quý khách dừng chân mua sắm đặc sản địa phương tại các cơ sở uy tín.\n\nChiều: Về đến TPHCM, chia tay và hẹn gặp lại Quý khách.\n\n--o0o--\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYẾN THAM QUAN BỔ ÍCH!\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của Resort/ khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00.', '4 sao', 'GIÁ TOUR BAO GỒM: \nVẬN CHUYỂN: Sử dụng xe du lịch 16 - 45 chỗ đời mới máy lạnh, ghế bật, phục vụ đưa đón đoàn suốt tuyến tham quan.\nLƯU TRÚ: Tiêu chuẩn 2 - 3 khách/ phòng.  Hotel 4*: TND Hotel (hoặc tương đương)\nĂN UỐNG: Nhà hàng địa phương tiêu chuẩn, hợp vệ sinh.\nHDV:  Đoàn có HDV tiếng Việt thuyết minh và phục vụ Đoàn tham quan suốt tuyến.\nBẢO HIỂM: Bảo hiểm du lịch theo quy định 50.000.000vnđ/vụ\nTHAM QUAN: Bao gồm phí vào cổng tại các điểm tham quan theo chương trình.\nQUÀ TẶNG: Nón du lịch, khăn ướt, nước đóng chai\nVAT theo quy định\nGIÁ TOUR KHÔNG BAO GỒM: \nTiền TIP cho HDV + Tài xế địa phương\nChi phí cá nhân ngoài chương trình: giặt ủi, điện thoại, minibar…\nTự túc 1 bữa ăn tối.\nVé tắm bùn, thảo dược,… xem show\nQUY ĐỊNH TRẺ EM:\nDưới 05 tuổi: Miễn phí (chi phí phát sinh trên tour gia đình tự chi trả). Hai người lớn chỉ được kèm theo 01 trẻ, từ trẻ thứ hai tính 50% giá tour.\nTừ 05 - dưới 10 tuổi: giá theo quy định, ngủ ghép với gia đình. Hai người lớn chỉ được kèm theo 01 trẻ, từ trẻ thứ hai tính giá như người lớn.\nTừ 10 tuổi trở lên: giá tour như người lớn.\nĐIỀU KIỆN  HỦY TOUR: (Không tính thứ bảy, chủ nhật và ngày lễ)                 \nViệc huỷ bỏ chuyến đi không được chấp nhận, DO TÍNH CHẤT TOUR KHUYẾN MÃI KÍCH CẦU NGÀY HỘI DU LỊCH HCM 2026.\nViệc huỷ bỏ chuyến đi phải được thông báo trực tiếp với Công ty, qua email, tin nhắn và phải được Công ty xác nhận. Việc huỷ bỏ bằng điện thoại không được chấp nhận.\nDo tính chất là đoàn ghép khách lẻ, công ty du lịch sẽ có trách nhiệm nhận khách đăng ký cho đủ đoàn (10 khách người lớn trở lên) thì đoàn sẽ khởi hành đúng lịch trình. Nếu số lượng đoàn dưới 10 khách, công ty có trách nhiệm thông báo cho khách trước ngày khởi hành 3 ngày và sẽ thỏa thuận lại ngày khởi hành mới hoặc hoàn trả toàn bộ số tiền đã đặt cọc tour.\nTrong những trường hợp bất khả kháng như: khủng bố, bạo động, thiên tai, lũ lụt, dịch bệnh……(Có văn bản ngừng nhận khách của địa phương) Tuỳ theo tình hình thực tế và sự thuận tiện, an toàn của khách hàng, công ty Du Lịch sẽ chủ động thông báo cho khách hàng sự thay đổi như sau: huỷ hoặc thay thế bằng một chương trình mới với chi phí tương đương chương trình tham quan trước đó. Trong trường hợp chương trình mới có phát sinh thì Khách hàng sẽ thanh toán khoản phát sinh này. Tuy nhiên, mỗi bên có trách nhiệm cố gắng tối đa, giúp đỡ bên bị thiệt hại nhằm giảm thiểu các tổn thất gây ra vì lý do bất khả kháng.…');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_comments`
--

CREATE TABLE `tour_comments` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_comments`
--

INSERT INTO `tour_comments` (`id`, `tour_id`, `user_id`, `content`, `rating`, `status`, `created_at`) VALUES
(1, 1, 2, 'Tour Đà Nẵng - Hội An rất vui, HDV nhiệt tình, lịch trình hợp lý.', 5, 'approved', '2026-04-20 02:37:58'),
(2, 1, 3, 'Khách sạn ổn, nhưng đồ ăn chưa đa dạng lắm.', 4, 'approved', '2026-04-21 02:37:58'),
(3, 3, 4, 'Đà Lạt đẹp, thời tiết dễ chịu. Sẽ quay lại cùng MyWeb Tour.', 5, 'approved', '2026-04-24 02:37:58'),
(4, 2, 2, 'Mình muốn hỏi thêm về chi phí phát sinh trong tour Nha Trang?', NULL, 'pending', '2026-04-27 02:37:58'),
(5, 4, 3, 'Lịch trình Hà Nội - Hạ Long khá dày, hơi mệt với người lớn tuổi.', 3, 'approved', '2026-04-26 02:37:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `role_id`, `full_name`, `email`, `password_hash`, `avatar`, `created_at`) VALUES
(1, 1, 'Nguyễn Quản Trị', 'admin@mywebtour.com', '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle', NULL, '2026-04-28 02:37:58'),
(2, 2, 'Trần Khách Hàng', 'tran.khach@example.com', '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle', NULL, '2026-04-28 02:37:58'),
(3, 2, 'Lê Du Lịch', 'le.dulich@example.com', '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle', NULL, '2026-04-28 02:37:58'),
(4, 2, 'Phạm Tham Quan', 'pham.thamquan@example.com', '$2y$10$s/sr/yIq3NJIbg9R9ZGjhOUhvF0LY2X3eUia2aWMkjcvpo2G46lle', NULL, '2026-04-28 02:37:58'),
(5, 1, 'Trang Trịnh', 'admin1@travelco.com', '$2y$10$iUDnqnQr7.6kHRg5TAYbaOWhmwECjb1MVQJpJ/twZ4TXIVxXjiuPi', NULL, '2026-04-28 04:08:05'),
(6, 2, 'Trang Trịnh', 'admin@travelco.com', '$2y$10$8O.ccNYjxOF/iDoCqkvkwuB/BTb1SAbP/b4t2sdLUJ/OwvUER7bsC', NULL, '2026-04-28 11:58:36');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `about_content`
--
ALTER TABLE `about_content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_about_content_user` (`updated_by`);

--
-- Chỉ mục cho bảng `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contact_messages_handler` (`handled_by`);

--
-- Chỉ mục cho bảng `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_order` (`display_order`),
  ADD KEY `fk_faqs_user` (`created_by`);

--
-- Chỉ mục cho bảng `my_orders`
--
ALTER TABLE `my_orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`),
  ADD KEY `fk_orders_tour` (`tour_id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_posts_category` (`category_id`),
  ADD KEY `fk_posts_author` (`author_id`);

--
-- Chỉ mục cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_post_comments_post` (`post_id`),
  ADD KEY `fk_post_comments_user` (`user_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `tour_comments`
--
ALTER TABLE `tour_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tour_comments_tour` (`tour_id`),
  ADD KEY `fk_tour_comments_user` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `about_content`
--
ALTER TABLE `about_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `my_orders`
--
ALTER TABLE `my_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `tour_comments`
--
ALTER TABLE `tour_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `about_content`
--
ALTER TABLE `about_content`
  ADD CONSTRAINT `fk_about_content_user` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `fk_contact_messages_handler` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `fk_faqs_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `post_categories` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `fk_post_comments_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tour_comments`
--
ALTER TABLE `tour_comments`
  ADD CONSTRAINT `fk_tour_comments_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tour_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
