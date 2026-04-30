# GoTour - Hệ thống đặt tour du lịch trực tuyến

GoTour là một nền tảng web cho phép khách hàng tìm kiếm, đặt tour du lịch, quản lý đơn hàng, bình luận, đọc tin tức và liên hệ với công ty. Hệ thống cung cấp giao diện quản trị (admin) để quản lý người dùng, tour, đơn hàng, tin tức, câu hỏi thường gặp (FAQ), nội dung giới thiệu, chi nhánh và thông tin liên hệ.

## Công nghệ sử dụng

- **Backend**: PHP 8.2 (procedural, không framework)
- **Frontend**: HTML5, CSS3, JavaScript (thuần), Bootstrap 5, Tabler Admin Template
- **Database**: MySQL 8.0
- **Web Server**: Apache (mod_rewrite)
- **Container**: Docker & Docker Compose
- **Khác**: phpMyAdmin, AJAX (Fetch API)

## Yêu cầu hệ thống

- Docker & Docker Compose (khuyến nghị)
- Hoặc môi trường PHP 8.2+ với MySQL và Apache (nếu chạy thủ công)

## Cài đặt với Docker (khuyến nghị)

1. **Clone repository** (hoặc giải nén mã nguồn vào thư mục dự án).
2. Mở terminal tại thư mục gốc dự án (chứa `docker-compose.yml`).
   ```bash
   cd .\Downloads\LAB_WEB-final\LAB_WEB-final\
   ```
3. Chạy lệnh:
   ```bash
   docker-compose up -d
   ```
4. Chờ các container khởi tạo. Lần đầu có thể mất vài phút.
5. Truy cập:
   - **Trang người dùng**: http://localhost:8080/frontend/
   - **Trang quản trị**: http://localhost:8080/frontend/admin/dashboard.php
   - **phpMyAdmin**: http://localhost:8081 (server: `db`, user: `root`, password: để trống)

6. Dữ liệu mẫu được tự động khởi tạo từ file `backend/db.sql`.

### Tài khoản mặc định

| Vai trò | Email               | Mật khẩu  |
| ------- | ------------------- | --------- |
| Admin   | tuanuser@gmail.com  | tuanuser  |
| Member  | tuanadmin@gmail.com | tuanadmin |


## Cài đặt thủ công (không Docker)

1. Đảm bảo PHP 8.2+, MySQL 8.0, Apache đã được cài đặt.
2. Import file `db.sql` vào cơ sở dữ liệu MySQL.
3. Cấu hình kết nối database trong `backend/db.php`:
   ```php
   $servername = "localhost"; // hoặc IP máy chủ MySQL
   $username = "root";
   $password = "";
   $dbname = "gotour";
   ```
4. Đặt document root trỏ đến thư mục `frontend/` (hoặc cấu hình virtual host).
5. Bảo đảm mod_rewrite được bật (cho đường dẫn đẹp).
6. Khởi động Apache và MySQL.

## Cấu trúc thư mục chính

```
├.
├── backend/
│   ├── auth/                # Đăng nhập, đăng ký, check_role
│   ├── config/              # Di chuyển db.php vào đây cho gọn
│   ├── core/                # (Mới) Chứa các hàm dùng chung, helper
│   ├── models/              # Logic truy vấn DB (Nên gộp file thừa)
│   ├── modules/             # (Thay cho actions & news) Gom theo nghiệp vụ
│   │   ├── tour/            # CRUD Tour, Cart, Order
│   │   ├── news/            # CRUD News, Comments
│   │   ├── user/            # Profile, Password
│   │   └── company/         # Branches, Contact
│   └── .htaccess            # Chặn truy cập trực tiếp vào thư mục backend
├── frontend/
│   ├── admin/               # Giao diện quản trị (Tabler)
│   ├── assets/              # CSS, JS, Images tĩnh của giao diện
│   ├── partials/            # Header, Footer, Sidebar
│   ├── src/                 # (Mới) Tách logic xử lý giao diện nếu cần
│   ├── index.php
│   └── ... (các file .php khác)
├── uploads/                 # Lưu trữ file động (avatars, news images)
├── db.sql
├── docker-compose.yml
├── Dockerfile
└── .gitignore
```

## Các tính năng chính

### Người dùng (khách & thành viên)
- Xem danh sách tour, tìm kiếm, lọc.
- Đặt tour (thêm vào giỏ hàng) – yêu cầu đăng nhập.
- Quản lý đơn hàng: xem danh sách, chi tiết, hủy đơn (nếu cho phép).
- Bình luận và đánh giá tour (cần được admin duyệt).
- Đăng ký, đăng nhập, đổi mật khẩu, cập nhật thông tin cá nhân, upload avatar.
- Đọc tin tức, bình luận bài viết.
- Xem FAQ, gửi liên hệ, xem thông tin chi nhánh.

### Quản trị viên (Admin)
- **Dashboard** – Thống kê nhanh.
- **Quản lý người dùng** – Xem, sửa, xóa, đặt lại mật khẩu, phân quyền (admin/member).
- **Quản lý tour** – Thêm, sửa, xóa, tìm kiếm tour.
- **Quản lý đơn hàng** – Xem danh sách, chi tiết, xác nhận, hủy, xóa đơn, lọc theo trạng thái.
- **Quản lý tin tức** – Thêm, sửa, xóa bài viết, duyệt/xóa bình luận.
- **Quản lý liên hệ** – Xem tin nhắn, đánh dấu trạng thái (mới, đang xử lý, đã xử lý), xóa.
- **Quản lý FAQ** – Thêm, sửa, xóa, bật/tắt hiển thị câu hỏi.
- **Quản lý trang Giới thiệu** – Cập nhật nội dung, sứ mệnh, tầm nhìn, hình ảnh banner.
- **Cài đặt hệ thống** – Cập nhật thông tin công ty (tên, logo, website, email), quản lý chi nhánh theo khu vực.

## Ghi chú phát triển

- **Phân quyền**: Dựa trên bảng `roles` (1 = admin, 2 = member). Các trang admin được bảo vệ bởi `check_role.php`.
- **AJAX**: Hầu hết các thao tác (đăng nhập, đăng ký, CRUD, lọc) đều dùng Fetch API, trả về JSON.
- **CSRF & XSS**: Chưa được xử lý đầy đủ trong phiên bản hiện tại. Cần bổ sung khi triển khai thực tế.
- **Error handling**: Đang ở chế độ hiển thị lỗi (development). Tắt `display_errors` khi lên production.
- **SEO**: Một số trang đã có thẻ meta cơ bản.

## Môi trường production

- Tắt `display_errors` trong `php.ini`.
- Đặt lại mật khẩu database mạnh.
- Sử dụng HTTPS (cấu hình SSL).
- Thiết lập quyền thư mục `uploads/` cho phép ghi.
- Điều chỉnh các đường dẫn tuyệt đối nếu cần.
- Kiểm tra lại các file `.htaccess` để đảm bảo bảo mật.

## Tác giả

Dự án được phát triển cho mục đích học tập và demo. Mọi đóng góp vui lòng liên hệ qua email hỗ trợ của GoTour.