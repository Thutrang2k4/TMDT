# Cấu trúc Backend API - GoTour Project

## 📁 Tổ chức thư mục

```
backend/
├── db.php                          # Kết nối database
├── config.php                      # Cấu hình project
│
├── auth/                           # Xác thực & Phân quyền
│   ├── login.php                   # Đăng nhập
│   ├── logout.php                  # Đăng xuất
│   └── check_role.php              # Kiểm tra quyền admin
│
├── models/                         # Models (Tầng dữ liệu)
│   ├── user_model.php              # Model user cơ bản
│   ├── user_profile_model.php      # Model profile user
│   ├── tour_model.php              # Model tour
│   ├── order_model.php             # Model đơn hàng
│   ├── news_model.php              # Model tin tức
│   └── content_model.php           # Model nội dung trang
│
├── actions/                        # API Endpoints (Tầng xử lý)
│   │
│   ├── user/                       # APIs liên quan User
│   │   ├── get_profile.php         ✅ Lấy thông tin profile
│   │   ├── update_profile.php      # Cập nhật thông tin
│   │   ├── upload_avatar.php       # Upload avatar
│   │   └── admin_user.php          # Quản lý users (admin)
│   │
│   ├── tour/                       # APIs liên quan Tour
│   │   ├── get_tour.php            # Lấy danh sách tour
│   │   ├── get_tour_by_id.php      # Chi tiết tour
│   │   └── ...
│   │
│   ├── order/                      # APIs liên quan Đơn hàng
│   │   ├── create_order.php        # Tạo đơn hàng
│   │   ├── get_orders.php          # Lấy danh sách
│   │   └── ...
│   │
│   └── company/                    # APIs liên quan Công ty
│       ├── get_company.php         # Thông tin công ty
│       └── ...
│
└── news/                           # APIs Tin tức (Relocated)
    ├── get_all.php                 # Danh sách tin
    ├── get_by_id.php               # Chi tiết tin
    ├── create.php                  # Tạo tin mới
    ├── delete.php                  # Xóa tin
    ├── get_comments.php            # Lấy bình luận
    └── add_comment.php             # Thêm bình luận
```

## 🎯 Quy tắc đặt tên

### API Endpoints
- **GET data:** `get_*.php` (VD: `get_profile.php`, `get_all.php`)
- **CREATE:** `create_*.php` hoặc `add_*.php`
- **UPDATE:** `update_*.php`
- **DELETE:** `delete_*.php`
- **UPLOAD:** `upload_*.php`

### Models
- Tên file: `<entity>_model.php` (VD: `user_model.php`)
- Functions: `get_*()`, `create_*()`, `update_*()`, `delete_*()`

## 📝 Lưu ý khi code

### 1. API phải trả về JSON
```php
<?php
header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'data' => $result
]);
?>
```

### 2. Luôn require model cần dùng
```php
require_once __DIR__ . '/../../models/user_model.php';
```

### 3. Kiểm tra session khi cần
```php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit();
}
```

## ⚠️ Files đã XÓA (Duplicate)

### `backend/get_profile.php` ❌ 
- **Lý do xóa:** Trùng với `backend/actions/user/get_profile.php`
- **Nguồn gốc:** Merge code từ thành viên khác
- **Không ảnh hưởng:** Không có file nào gọi đến

## 🔄 Lịch sử thay đổi cấu trúc

### Phase 1: Tổ chức ban đầu
- Tất cả API ở `backend/actions/`

### Phase 2: Merge code (FINAL MERGER)
- Pull code từ nhánh khác
- Xuất hiện duplicate files

### Phase 3: Cleanup (Hiện tại)
- Xóa file duplicate
- Chuẩn hóa đường dẫn
- Tài liệu hóa cấu trúc

## 💡 Tips cho thành viên mới

1. **Tạo API mới:** Đặt trong thư mục phù hợp (`actions/user/`, `actions/tour/`,...)
2. **Gọi API:** Dùng đường dẫn tương đối từ frontend
3. **Sửa API:** Tìm theo tên file (VD: search `get_profile.php`)
4. **Test API:** Có thể gọi trực tiếp URL trong browser hoặc Postman

## 📞 File nào để làm gì?

| Mục đích | File |
|----------|------|
| Lấy thông tin user đang login | `backend/actions/user/get_profile.php` |
| Cập nhật profile | `backend/actions/user/update_profile.php` |
| Upload avatar | `backend/actions/user/upload_avatar.php` |
| Lấy danh sách tour | `backend/actions/tour/get_tour.php` |
| Lấy tin tức | `backend/news/get_all.php` |
| Thông tin công ty | `backend/actions/company/get_company.php` |
