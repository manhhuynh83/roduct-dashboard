📦 Project: Product Management Dashboard (PHP)
📌 Giới thiệu

Đây là dự án web quản lý sản phẩm được xây dựng bằng PHP (thuần) kết hợp với MySQL, giao diện chia thành nhiều file include để dễ tái sử dụng.
Hệ thống cho phép:

Thêm sản phẩm

Chỉnh sửa sản phẩm

Xoá sản phẩm

Upload hình ảnh

Hiển thị danh sách sản phẩm

Điều hướng bằng sidebar

Cấu trúc dự án được tối ưu hóa cho admin dashboard nhỏ gọn.

📁 Cấu trúc thư mục
index2/
│── add_product.php         # Form thêm sản phẩm
│── edit_product.php        # Form chỉnh sửa sản phẩm
│── delete_product.php      # Xóa sản phẩm
│── dashboard.php           # Dashboard admin
│── config.php              # Kết nối CSDL
│── session.php             # Quản lý session đăng nhập
│── fix_image_paths.php     # Script xử lý đường dẫn ảnh
│
├── css/                    # File CSS
├── js/                     # File Javascript
├── scss/                   # File SCSS
├── vendors/                # Thư viện bên thứ 3
├── fonts/                  # Font chữ
├── images/                 # Ảnh giao diện
├── uploads/                # Ảnh sản phẩm upload
├── uploaded_img/           # Ảnh khác trong hệ thống
│
├── inc_headcss.php         # Include CSS
├── inc_scripts.php         # Include JS
├── inc_footer.php          # Footer chung
├── inc_sidebarnav.php      # Sidebar menu
└── inc_searchbar.php       # Thanh tìm kiếm

⚙️ Cài đặt & chạy dự án
1. Yêu cầu hệ thống

PHP 7.4+

MySQL

XAMPP / Laragon / WAMP đều dùng được

Trình duyệt bất kỳ

2. Cách chạy

Giải nén dự án vào thư mục web:

Với XAMPP: htdocs/index2/

Với Laragon: www/index2/

Tạo database trong phpMyAdmin

Import file SQL (nếu có) hoặc tự tạo bảng sản phẩm như mẫu:

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price INT,
    image VARCHAR(255),
    description TEXT
);


Mở file config.php và sửa thông tin kết nối:

$connection = mysqli_connect("localhost", "root", "", "tendatabase");


Chạy web bằng trình duyệt:

http://localhost/index2/dashboard.php

📝 Tính năng chính
✔️ Thêm sản phẩm

Nhập tên, giá, mô tả

Upload hình ảnh

Kiểm tra dữ liệu trước khi lưu

✔️ Chỉnh sửa / Xoá sản phẩm

Form chỉnh sửa có dữ liệu cũ

Xác nhận xóa

✔️ Quản lý hình ảnh

Lưu ảnh vào thư mục /uploads

Tự động xử lý đường dẫn sai (fix_image_paths.php)

✔️ Dashboard

Giao diện admin có sidebar

Thanh tìm kiếm

Bảng sản phẩm

🧩 Công nghệ sử dụng

PHP thuần

MySQL

HTML/CSS/JS

SCSS

Vendor (plugin UI template)

🧑‍💻 Tác giả

Dự án được chuẩn bị để đưa lên GitHub và tiếp tục phát triển.
