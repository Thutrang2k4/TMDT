WEB_TOUR_APP/
├── backend/                  <-- TẦNG LOGIC & DỮ LIỆU (Cần bảo vệ bằng .htaccess)
│   ├── db.php                <-- Kết nối CSDL DUY NHẤT ($conn)
│   ├── auth/                 <-- Logic XÁC THỰC & PHÂN QUYỀN
│   │   ├── auth_functions.php  <-- Hàm handle_login, handle_register (đã sửa đổi)
│   │   └── check_role.php      <-- Hàm protect_page, is_admin
│   │
│   ├── models/               <-- LOGIC NGHIỆP VỤ & TRUY VẤN DỮ LIỆU (CRUD)
│   │   ├── user_model.php      <-- Quản lý User (Thay đổi TT cá nhân, QL Admin User)
│   │   ├── tour_model.php      <-- Quản lý Sản phẩm/Tour
│   │   ├── news_model.php      <-- Quản lý Tin tức/Bài viết
│   │   ├── content_model.php   <-- Quản lý Nội dung public (Liên hệ, Giới thiệu, Hỏi/đáp)
│   │   ├── order_model.php     <-- Quản lý Giỏ hàng/Đơn hàng
│   │   └── comment_model.php   <-- Quản lý Bình luận/Đánh giá
│   │
│   ├── actions/              <-- ĐIỀU PHỐI (Nhận POST/AJAX, gọi hàm từ models/, và REDIRECT)
│   │   ├── user/               <-- Xử lý liên quan đến User
│   │   │   ├── process_update_profile.php  <-- Thay đổi thông tin cá nhân/avatar (Thành viên)
│   │   │   └── process_admin_user.php      <-- Reset mật khẩu, khóa người dùng (Admin)
│   │   │
│   │   ├── tour/               <-- Xử lý liên quan đến Tour
│   │   │   ├── process_add_to_cart.php
│   │   │   └── process_admin_tour.php      <-- Thêm/Sửa/Xóa Tour (Admin)
│   │   │
│   │   └── cms/                <-- Xử lý Quản lý Nội dung (CMS)
│   │       ├── process_admin_content.php   <-- Sửa nội dung public (Giới thiệu, Liên hệ)
│   │       └── process_admin_contact.php   <-- Xử lý Form Liên hệ, đánh dấu trạng thái
│   └── .htaccess             <-- NGĂN CHẶN TRUY CẬP TRỰC TIẾP
│
├── frontend/                 <-- TẦNG TRÌNH BÀY/GIAO DIỆN (Presentation)
└── README.md

# TMDT
# TMDT
