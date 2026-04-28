-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 28, 2026 lúc 03:46 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


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
(1, 'Về chúng tôi - GoTour', 'GoTour là công ty du lịch hàng đầu tại Việt Nam, chuyên cung cấp các tour du lịch trong nước và quốc tế với chất lượng dịch vụ cao cấp. Với đội ngũ nhân viên giàu kinh nghiệm và tận tâm, chúng tôi cam kết mang đến cho khách hàng những trải nghiệm du lịch tuyệt vời nhất.', 'Sứ mệnh của chúng tôi là kết nối mọi người với thế giới thông qua những chuyến du lịch ý nghĩa, an toàn và đáng nhớ.', 'Trở thành công ty du lịch hàng đầu khu vực Đông Nam Á, mang đến trải nghiệm du lịch đẳng cấp thế giới cho mọi khách hàng.', 'assets/images/about-us.jpg', '2026-04-27 19:37:58', NULL);

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
(1, 'Làm thế nào để đặt tour du lịch?', 'Bạn có thể đặt tour trực tiếp trên website của chúng tôi bằng cách chọn tour mong muốn, điền thông tin và thanh toán. Hoặc liên hệ hotline: 1900-xxxx để được tư vấn và hỗ trợ đặt tour.', 'Đặt tour', 1, 1, '2026-04-27 19:37:58', '2026-04-27 19:37:58', 1),
(2, 'Tôi có thể hủy tour đã đặt không?', 'Có, bạn có thể hủy tour theo chính sách hủy của chúng tôi. Tùy vào thời gian hủy, bạn sẽ được hoàn lại một phần hoặc toàn bộ chi phí. Vui lòng xem chi tiết trong mục Điều khoản và Điều kiện.', 'Đặt tour', 2, 1, '2026-04-27 19:37:58', '2026-04-27 19:37:58', 1),
(3, 'Các hình thức thanh toán nào được chấp nhận?', 'Chúng tôi chấp nhận thanh toán qua thẻ ATM, thẻ tín dụng (Visa/Mastercard), chuyển khoản ngân hàng, ví điện tử (MoMo, ZaloPay) và thanh toán trực tiếp tại văn phòng.', 'Thanh toán', 3, 1, '2026-04-27 19:37:58', '2026-04-27 19:37:58', 1),
(4, 'Tour có bao gồm bảo hiểm du lịch không?', 'Có, tất cả các tour của chúng tôi đều bao gồm bảo hiểm du lịch cơ bản. Bạn cũng có thể mua thêm gói bảo hiểm mở rộng nếu muốn.', 'Dịch vụ', 4, 1, '2026-04-27 19:37:58', '2026-04-27 19:37:58', 1),
(5, 'Tôi cần chuẩn bị gì trước khi đi tour?', 'Bạn cần chuẩn bị giấy tờ tùy thân (CMND/Passport), quần áo phù hợp với thời tiết, thuốc men cá nhân và một số vật dụng cần thiết khác. Chúng tôi sẽ gửi danh sách chi tiết sau khi bạn đặt tour.', 'Chuẩn bị', 5, 1, '2026-04-27 19:37:58', '2026-04-27 19:37:58', 1),
(6, 'Trẻ em có được giảm giá không?', 'Có, trẻ em dưới 5 tuổi được miễn phí (không tính ghế riêng), từ 5-10 tuổi được giảm 50% và từ 11 tuổi trở lên tính như người lớn.', 'Giá cả', 6, 1, '2026-04-27 19:37:58', '2026-04-27 19:37:58', 1),
(7, 'Tôi có thể thay đổi lịch trình tour không?', 'Tùy thuộc vào loại tour và thời gian thay đổi, bạn có thể được phép thay đổi lịch trình với một khoản phí nhất định. Vui lòng liên hệ với chúng tôi càng sớm càng tốt.', 'Đặt tour', 7, 1, '2026-04-27 19:37:58', '2026-04-27 19:37:58', 1),
(8, 'Hướng dẫn viên có nói được tiếng Anh không?', 'Có, chúng tôi có hướng dẫn viên nói tiếng Anh cho các tour quốc tế và tour trong nước theo yêu cầu. Vui lòng thông báo trước khi đặt tour.', 'Dịch vụ', 8, 1, '2026-04-27 19:37:58', '2026-04-27 19:37:58', 1);

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
) ;

--
-- Đang đổ dữ liệu cho bảng `post_comments`
--

INSERT INTO `post_comments` (`id`, `post_id`, `user_id`, `content`, `rating`, `status`, `created_at`) VALUES
(1, 2, 2, 'Bài viết rất hữu ích, mình chuẩn bị đi Đà Lạt nên áp dụng luôn.', 5, 'approved', '2026-04-17 02:37:58'),
(2, 3, 3, 'Lịch trình gợi ý khá hợp lý, nhưng có thể thêm một vài điểm ăn uống.', 4, 'approved', '2026-04-20 02:37:58'),
(3, 4, 4, 'Mình muốn hỏi thời gian áp dụng khuyến mãi là đến ngày nào?', NULL, 'pending', '2026-04-23 02:37:58'),
(4, 1, 2, 'Chúc mừng MyWeb Tour khai trương, mong sớm có thêm nhiều tour mới.', 5, 'approved', '2026-04-14 02:37:58'),
(5, 2, 4, 'Mình thấy nên bổ sung thêm gợi ý khách sạn giá rẻ.', 4, 'approved', '2026-04-18 02:37:58'),
(6, 5, 2, 'tuyệt', 5, 'approved', '2026-04-28 04:26:15');

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
  `long_description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tours`
--

INSERT INTO `tours` (`id`, `title`, `slug`, `short_description`, `price`, `duration_days`, `status`, `created_at`, `updated_at`, `image`, `place_start`, `vehicle`, `long_description`) VALUES
(1, 'TOUR ĐÀ LẠT  3N3Đ | Thiên Đường Săn Mây - Langbiang Land - Tiệc BBQ', 'da-nang-hoi-an-3n2d', 'Tour khám phá Đà Nẵng, phố cổ Hội An, check-in Cầu Rồng.', 2586000.00, 3, 'active', '2026-04-28 02:37:58', '2026-04-28 03:36:17', 'https://bazantravel.com/cdn/medias/uploads/21/21380-cap-treo-tour-da-nang-hoi-an-4-ngay-3-dem-670x446.jpg', 'Tp.Hồ Chí Minh', 'DU LỊCH VIỆT', 'NGÀY 1 | THIÊN ĐƯỜNG SĂN MÂY – ĐỒI CHÈ CẦU ĐẤT - TRẠM KÍ ỨC – CÁP TREO ĐỒI ROBIN - THIỀN VIỆN TRÚC LÂM (Ăn sáng, ăn trưa, BBQ tối)\n05h00: Đến Cầu Đất, Đoàn đến với khu vực:\n\n• THIÊN ĐƯỜNG SĂN MÂY - Cầu Đất Panorama để đón bình mình lên, ngắm và chụp hình với những đám mây trắng bồng bềnh. Quý khách sẽ được thỏa sức check in với những tiểu cảnh đẹp mê người nơi đây. Ngoài ra Quý khách cũng có thể thuê những bộ trang phục theo nhiều phong cách: Cổ trang, Mông Cổ…để chụp những bộ ảnh độc đáo (chi phí thuê trang phục tự túc).\n06h30: Quý khách dùng điểm tâm sáng tại nhà hàng Panorama.\n07h30: Tiếp tục khám phá những điểm tham quan hấp dẫn tại khu vực Cầu Đất:\n\n• ĐỒI CHÈ CẦU ĐẤT: Với độ cao 1650 mét so với mực nước biển cùng những dãy chè xanh tươi nối dài bất tận, đồi chè Cầu Đất đẹp đến ngỡ ngàng dưới làn sương sớm của bình mình chớm nở. \nCheck – in với CÁNH ĐỒNG ĐIỆN GIÓ trên đồi chè xanh mát.\nĐoàn di chuyển về trung tâm Thành Phố Đà Lạt, ghé tham quan :\n\n• TRẠM KÝ ỨC- NGÔI LÀNG CỔ CHÂU ÂU là bức tranh hoàn mỹ, nơi từng đường nét khắc họa lên đường nét vẻ đẹp yên bình. Không chỉ đơn giản là một nơi để ghé thăm, mà nó là một hành trình – đưa bạn trở về với những giấc mơ thuở ấu thơ.\nTrưa:    Dùng bữa trưa tại nhà hàng. Đoàn về khách sạn nhận phòng nghỉ ngơi.\nChiều:  Đoàn di chuyển tham quan\n\n• CÁP TREO XUYÊN RỪNG THÔNG – ĐẦU TIÊN TẠI ĐÀ LẠT mang đến trải nghiệm ngắm cảnh tuyệt vời, cho phép du khách chiêm ngưỡng toàn cảnh thành phố, rừng thông bạt ngàn và hồ Tuyền Lâm từ trên cao (chi phí tự túc).\n• THIỀN VIỆN TRÚC LÂM : Tọa lạc trên núi Phụng Hoàng, nhìn xuống hồ Tuyền Lâm xanh biếc, đây là thiền viện lớn và đẹp bậc nhất Đà Lạt.\nTối:       Quý khách dùng bữa tối BBQ hoành tráng tại nhà hàng trong tiết trời se lạnh và không gian cực thư giãn của Đà Lạt. \n\nNGÀY 2 | LANGBIANG LAND- GIAO LƯU VĂN HOÁ CỒNG CHIÊNG- FRESH GARDEN (Ăn sáng, trưa) (Tối tự túc) \nSáng: Quý khách dùng điểm tâm sáng. Bắt đầu hành trình tham quan TP Đà Lạt:\n\n• QUẢNG TRƯỜNG LÂM VIÊN : Tọa lạc giữa \"trái tim\" của thành phố hoa, hướng ra hồ Xuân Hương, ấn tượng với công trình nghệ thuật khổng lồ với khối bông hoa dã quỳ và khối nụ hoa được thiết kế bằng kính màu lạ mắt.\n• LANGBIANG LAND Trú ngụ dưới chân núi Langbiang yên bình – nơi mang đậm giá trị văn hóa thiêng liêng của người đồng bào K’Ho. Là điểm tham quan mang giá trị tinh hoa của núi rừng Tây Nguyên, gồm những hạng mục sau:\n- Tham quan Thác hoa đào,\n- Vườn đào lông, vườn dâu Nhật\n- Tham quan Công viên khủng Long\n- Tham quan Vườn thú cưng\n- Trò chơi Trượt phao kỳ thú\n- Trò chơi Trượt máng cầu vồng\n- Trò chơi chạy xe Greenline Luge\n- Tham quan Tượng \"Vũ điệu Langbiangland\" trên hồ vô cực\n- Tham quan Cầu bán nguyệt\n• Quý khách trải nghiệm GIAO LƯU VĂN HOÁ CỒNG CHIÊNG : tiếng chiêng vang vọng, ánh lửa bập bùng. Điệu múa uyển chuyển của các chàng trai, cô gái Tây Nguyên tái hiện lại những nghi lễ linh thiêng, từ lễ cúng bến nước, lễ mừng lúa mới đến những ngày hội của buôn làng.\nTrưa: Quý khách dùng bữa trưa tại nhà hàng. Về khách sạn nghỉ ngơi.\nChiều: Xe đưa Đoàn đi tham quan.\n\n- FRESH GARDEN là một trong những nơi sở hữu cánh đồng hoa đa dạng nhất Đà Lạt. Từ lavender tím mộng mơ, hoa hướng dương rực rỡ, đến cẩm tú cầu, hoa sao nhái…bên cạnh đó còn có nhưng hạng mục hấp dẫn:\n- Cối xay gió và ngôi nhà phủ đầy hoa\n- Hồ vô cực với view rừng thông\nThác nước nhân tạo ảo diệu\n- Cổng trời Châu Âu\nĐộng băng thiên thần….\n- FRESH ZOO được thiết kế theo mô hình sở thú trong nhà, mang đến cho bạn trải nghiệm gần gũi và an toàn khi tương tác với các loài thú. Quý khách sẽ có cơ hội tiếp xúc trực tiếp, cho ăn và chụp ảnh cùng những loài động vật như: ngựa lùn, bò lùn, dê lùn, vẹt, thỏ, chuột lang, sóc Bắc Mỹ, lạc đà Alpaca… \nTối: Quý khách dùng bữa tối tự túc, tự do vui chơi. Quý khách vui chơi với CHỢ ĐÊM ÂM PHỦ, hãy sắm cho mình những chiếc khăn choàng và nón được đan từ những đôi bàn tay tài hoa nhưng không kém phần tỉ mỉ. Xung quanh chợ hoặc các khu phố, dễ dàng bắt gặp hình ảnh quay quần bên nhau cùng ly sữa nóng, chiếc bánh rán nóng hổi. \n\nNGÀY 3 | CHỢ ĐÀ LẠT - MUA SẮM NÔNG SẢN – TP.HCM (Ăn sáng, trưa) \nSáng: Quý khách dùng điểm tâm sáng và làm thủ tục trả phòng.\n\nCHỢ ĐÀ LẠT - Du khách sẽ choáng ngợp bởi hàng ngàn loại rau củ, hàng trăm loại trái cây và đủ các loại hoa khoe sắc.\nTrưa: Đoàn dừng chân dùng bữa trưa tại BẢO LỘC. Tiếp tục hành trình về TP.HCM.\nChiều: Về đến điểm đón ban đầu, chia tay và hẹn gặp lại Quý khách.\n\nKÍNH CHÚC QUÝ KHÁCH CÓ CHUYẾN THAM QUAN BỔ ÍCH!\n\nThứ tự các điểm tham quan có thể thay đổi cho phù hợp với tình hình thực tế nhưng vẫn đảm bảo thực hiện đầy đủ nội dung chương trình.\nQuy định của khách sạn: giờ nhận phòng: 14h00 – 15h00. Giờ trả phòng 12h00\n'),
(2, 'Nha Trang Biển Xanh 4N3Đ', 'nha-trang-bien-xanh-4n3d', 'Tour nghỉ dưỡng Nha Trang, tham quan VinWonders, tắm biển.', 4590000.00, 4, 'active', '2026-04-28 02:37:58', '2026-04-28 03:54:35', 'https://static.vinwonders.com/production/bai-bien-nha-trang-topbanner.jpg', '', '', 'NGÀY 1 | THIÊN ĐƯỜNG SĂN MÂY – ĐỒI CHÈ CẦU ĐẤT - TRẠM KÍ ỨC – CÁP TREO ĐỒI ROBIN - THIỀN VIỆN TRÚC LÂM (Ăn sáng, ăn trưa, BBQ tối)\n05h00: Đến Cầu Đất, Đoàn đến với khu vực:\n\nTHIÊN ĐƯỜNG SĂN MÂY - Cầu Đất Panorama để đón bình mình lên, ngắm và chụp hình với những đám mây trắng bồng bềnh. Quý khách sẽ được thỏa sức check in với những tiểu cảnh đẹp mê người nơi đây. Ngoài ra Quý khách cũng có thể thuê những bộ trang phục theo nhiều phong cách: Cổ trang, Mông Cổ…để chụp những bộ ảnh độc đáo (chi phí thuê trang phục tự túc).\n06h30: Quý khách dùng điểm tâm sáng tại nhà hàng Panorama.\n07h30: Tiếp tục khám phá những điểm tham quan hấp dẫn tại khu vực Cầu Đất:\n\nĐỒI CHÈ CẦU ĐẤT: Với độ cao 1650 mét so với mực nước biển cùng những dãy chè xanh tươi nối dài bất tận, đồi chè Cầu Đất đẹp đến ngỡ ngàng dưới làn sương sớm của bình mình chớm nở. \nCheck – in với CÁNH ĐỒNG ĐIỆN GIÓ trên đồi chè xanh mát.\nĐoàn di chuyển về trung tâm Thành Phố Đà Lạt, ghé tham quan :\n\nTRẠM KÝ ỨC- NGÔI LÀNG CỔ CHÂU ÂU là bức tranh hoàn mỹ, nơi từng đường nét khắc họa lên đường nét vẻ đẹp yên bình. Không chỉ đơn giản là một nơi để ghé thăm, mà nó là một hành trình – đưa bạn trở về với những giấc mơ thuở ấu thơ.\nTrưa:    Dùng bữa trưa tại nhà hàng. Đoàn về khách sạn nhận phòng nghỉ ngơi.\nChiều:  Đoàn di chuyển tham quan\n\nCÁP TREO XUYÊN RỪNG THÔNG – ĐẦU TIÊN TẠI ĐÀ LẠT mang đến trải nghiệm ngắm cảnh tuyệt vời, cho phép du khách chiêm ngưỡng toàn cảnh thành phố, rừng thông bạt ngàn và hồ Tuyền Lâm từ trên cao (chi phí tự túc).\nTHIỀN VIỆN TRÚC LÂM : Tọa lạc trên núi Phụng Hoàng, nhìn xuống hồ Tuyền Lâm xanh biếc, đây là thiền viện lớn và đẹp bậc nhất Đà Lạt.\nTối:       Quý khách dùng bữa tối BBQ hoành tráng tại nhà hàng trong tiết trời se lạnh và không gian cực thư giãn của Đà Lạt. '),
(3, 'TOUR ĐÀ LẠT  4N3Đ: Làng Hoa Vạn Thành - Puppy Farm - Langbiang - Học Viện Don Bosco - Đồi Chè Cầu Đất', 'da-lat-mong-mo-3n2d', 'Tour Đà Lạt ngắm hoa, săn mây, tham quan các địa điểm nổi tiếng.', 3590000.00, 4, 'active', '2026-04-28 02:37:58', '2026-04-28 04:02:10', 'https://samtenhills.vn/wp-content/uploads/2024/01/dai-bao-thap-kinh-luan-lon-nhat-the-gioi.jpg', 'Tp.Hồ Chí Minh', 'Vietourist', '​​NGÀY 1 | SÀI GÒN – ĐÀ LẠT ( Ăn Sáng, Trưa) \n\nSáng: Xe và hướng dẫn viên công ty Vietourist đón quý khách tại điểm hẹn khởi hành đi Đà Lạt.\n\nĐoàn ăn sáng tại khu vực Đồng Nai. Sau bữa sáng, Xe theo quốc lộ 20 đến với Đà Lạt. Trên xe Quý khách cùng hướng dẫn viên tìm hiểu về lịch sử, văn hóa từng vùng đất mà đoàn đi qua, tham gia các trò chơi vui nhộn cùng nhiều phần quà hấp dẫn.\n\nTrưa: Đoàn dùng cơm trưa tại Bảo Lộc. \n\nChiều: Đến với thác Pongour, cách quốc lộ 20 khoảng 7km. Thác Pongour Đà Lạt là thác nước nổi tiếng khu vực Tây Nguyên, nơi đây hàng năm thu hút một số lượng lớn khách du lịch đến đây tham quan. Không chỉ thế thác Pongour còn được rất nhiều khách du lịch ngoài nước biết đến là một thác nước tuyệt đẹp và thơ mộng.\n\nĐến Đà Lạt, nhận phòng khách sạn nghỉ ngơi.\n\nTối: Quý khách tự túc dùng cơm tối và tự do dạo phố đêm Đà Lạt.\n\nNGÀY 2 | LÀNG HOA VẠN THÀNH – PUPPY FARM - LANGBIANG ( Ăn Sáng, Trưa, Tối) \n\nSáng: Sau bữa sáng tại khách sạn, đoàn bắt đầu hành trình tham quan Đà lạt với những điểm tham quan hấp dẫn sau: \n\nLàng Hoa Vạn Thành\n\nLàng Hoa Vạn Thành: Nói đến hoa, chúng ta không thể không nhắc đến làng hoa Vạn Thành – một làng trồng hoa truyền thống đã làm rạng danh thương hiệu hoa Đà Lạt. Làng hoa đã được hình thành từ rất lâu, là nơi cung cấp hoa uy tín và chất lượng hàng đầu ở Đà Lạt, và trở thành địa điểm tham quan du lịch nổi tiếng.  \n\nPuppy farm: là một địa điểm chiếm được sự yêu mến của rất nhiều người, bởi đây là ngôi nhà chung của hơn 150 chú chó. Khi đến đây, bạn không chỉ chơi đùa với những chú cún dễ thương, mà còn được dạo quanh những cánh đồng hoa xinh đẹp, hay vườn nông sản được trồng theo công nghệ hiện đại. Tại trang trại luôn có đội ngũ nhân viên hướng dẫn, hỗ trợ nhiệt tình khi bạn cần.\n\nTrưa: Dùng cơm trưa tại nhà hàng.Về khách sạn nghỉ ngơi.\n\nChiều: Đoàn tiếp tục hành trình tham quan: \n\nLangbiang\n\nLangbiang: Được ví như trái tim của Đà Lạt, núi Langbiang nổi tiếng với loại hình dã ngoại, khám phá thiên nhiên, thu hút các bạn trẻ mê phượt và những nhà mạo hiểm. Nơi đây là ngôi nhà chung của nhiều loại thảo dược, thảo mộc, và chim quý. Ở Khu du lịch Langbiang Đà Lạt, hoạt động chính là tham quan ngắm cảnh. Du khách sẽ chọn được những góc chụp rất đẹp và có tầm nhìn rộng với phong cảnh núi non hùng vĩ (vé xe vận chuyển tự túc).\n\nTối: Đoàn dùng bữa tối tại nhà hàng địa phương và tham gia buổi lễ giao lưu cồng chiêng Đà Lạt, giao lưu ca hát, nhảy múa, ăn thịt nướng và uống rượu cần bên bếp lửa hồng cùng với những nghệ nhân dân tộc. Thông qua các hoạt động văn hóa sẽ giúp bạn hiểu biết và tôn trọng hơn về nét văn hóa truyền thống của người dân Tây Nguyên.\n\nNGÀY 3 | DINH I – HỒ TUYỀN LÂM - ĐỒI CHÈ CẦU ĐẤT ( Ăn Sáng, Trưa) \n\nSáng: Quý khách dùng bữa sáng tại khách sạn, quý khách tiếp tục chương trình tham quan những cảnh điểm nổi tiếng của Đà Lạt.\n\nDinh III Đà Lạt hay còn được gọi là Dinh Bảo Đại – một dinh thự sang trọng, mang đậm bản sắc châu Âu giữa lòng những đồi thông xanh. Một địa điểm du lịch hấp dẫn cho những ai yêu thích khám phá lịch sử và đắm mình trong khung cảnh lãng mạn nước Pháp nơi đây. Dinh nằm giữa rừng Ái Ân trên đỉnh đồi mà trong dự án chỉnh trang Ðà Lạt của Ernest Hébrard dành cho dinh toàn quyền. Toàn thể công trình mang đậm phong cách kiến trúc Châu Âu, điển hình là trước biệt điện và sau biệt điện đều có vườn hoa. \n\nDon Bosco Đà Lạt\n\nDon Bosco Đà Lạt hay tên đầy đủ hơn là Học viện Don Bosco Đà Lạt được thành lập vào năm 1971. Giữa không gian rộng lớn, tòa nhà Don Bosco hiện lên nguy nga và tráng lệ với sắc trắng tinh khôi cùng những đường nét mang âm hưởng kiến trúc châu Âu cổ điển. Dễ thấy nhất là những cột trụ khổng lồ, dãy hành lang trải dài, đường nét chạm khắc tinh xảo, mái chóp nhọn,...\n\nTrưa: Dùng cơm trưa tại nhà hàng địa phương.\n\nĐồi Chè Cầu Đất\n\nChiều: Đồi Chè Cầu Đất Farm Đà Lạt: là địa điểm du lịch sinh thái nổi tiếng. Từ con đường uốn lượn quanh co, cây đại thụ nghiêng mình đón nắng sớm hay hình ảnh người địa phương đang miệt mài thu hoạch lá chè, mọi khoảnh khắc tại Đồi Chè Cầu Đất Farm Đà Lạt đều ẩn chứa “ý thơ” và mang đến cho bạn trải nghiệm du lịch thư thả.\n\nXe và HDV đưa đoàn quay lại trung tâm Đà Lạt, dùng cơm tối tại nhà hàng địa phương.\n\nNGÀY 4 | QUẢNG TRƯỜNG LÂM VIÊN – SÀI GÒN ( Ăn Sáng, Trưa)\n\nQuảng trường Lâm Viên\n\nSáng: Quý khách sau khi dùng bữa sáng tại khách sạn, làm thủ tục trả phòng. Xe đưa Quý khách đến Quảng trường Lâm Viên – khu vực ấn tượng với không gian rộng lớn, thoáng mát hướng ra hồ Xuân Hương cùng công trình nghệ thuật khối bông hoa dã quỳ và khối nụ hoa Atiso khổng lồ được thiết kế bằng kính màu rất đẹp mắt. Khởi hành trở lại TPHCM. \n\nVề đến TP.Bảo Lộc, Quý khách dùng cơm trưa, thưởng thức đặc sản địa phương như trà, các loại trái cây nổi tiếng của Bảo Lộc.\n\nVề đến TP.HCM Kết thúc chuyến đi chia tay và hẹn gặp lại quý khách Trên Mọi Nẻo Đường Của Vietourist.'),
(4, 'Hà Nội - Hạ Long 4N3Đ', 'ha-noi-ha-long-4n3d', 'Tour kết hợp Hà Nội phố cổ và du thuyền Vịnh Hạ Long.', 5690000.00, 4, 'active', '2026-04-28 02:37:58', '2026-04-28 02:37:58', 'https://statics.vinpearl.com/du-lich-ha-long-2_1632635256.jpg', '', '', NULL),
(5, 'Phú Quốc Thiên Đường Nghỉ Dưỡng 3N2Đ', 'phu-quoc-thien-duong-3n2d', 'Tour Phú Quốc tắm biển, câu cá, tham quan GrandWorld.', 4990000.00, 3, 'inactive', '2026-04-28 02:37:58', '2026-04-28 02:37:58', 'https://samtenhills.vn/wp-content/uploads/2024/01/dai-bao-thap-kinh-luan-lon-nhat-the-gioi.jpg', '', '', NULL);

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
) ;

--
-- Đang đổ dữ liệu cho bảng `tour_comments`
--

INSERT INTO `tour_comments` (`id`, `tour_id`, `user_id`, `content`, `rating`, `status`, `created_at`) VALUES
(1, 1, 2, 'Tour Đà Nẵng - Hội An rất vui, HDV nhiệt tình, lịch trình hợp lý.', 5, 'approved', '2026-04-20 02:37:58'),
(2, 1, 3, 'Khách sạn ổn, nhưng đồ ăn chưa đa dạng lắm.', 4, 'approved', '2026-04-21 02:37:58'),
(3, 3, 4, 'Đà Lạt đẹp, thời tiết dễ chịu. Sẽ quay lại cùng MyWeb Tour.', 5, 'approved', '2026-04-24 02:37:58'),
(4, 2, 2, 'Mình muốn hỏi thêm về chi phí phát sinh trong tour Nha Trang?', NULL, 'pending', '2026-04-27 02:37:58'),
(5, 4, 3, 'Lịch trình Hà Nội - Hạ Long khá dày, hơi mệt với người lớn tuổi.', 3, 'approved', '2026-04-26 02:37:58'),
(21, 1, 5, 'tôi thích', 5, 'approved', '2026-04-28 13:02:57'),
(22, 1, 6, 'thu', 5, 'approved', '2026-04-28 13:03:46');

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
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tour_comments`
--
ALTER TABLE `tour_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
