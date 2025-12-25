# Dự Án Ứng Dụng Web Bán Hàng

## Mô Tả Dự Án

Đây là một ứng dụng web bán hàng được phát triển bằng PHP, cung cấp các tính năng quản lý sản phẩm, giỏ hàng, thanh toán trực tuyến, và hệ thống quản trị cho admin, distributor, và shipper.

## Tính Năng Chính

- **Quản lý sản phẩm**: Thêm, sửa, xóa sản phẩm và loại sản phẩm
- **Giỏ hàng và đặt hàng**: Quản lý giỏ hàng, đặt hàng, và theo dõi đơn hàng
- **Thanh toán trực tuyến**: Tích hợp VNPay để thanh toán an toàn
- **Hệ thống chat**: Chat bot và chat giữa người dùng
- **Quản lý người dùng**: Đăng ký, đăng nhập, và phân quyền (Admin, Distributor, Shipper, User)
- **Khuyến mãi và voucher**: Quản lý khuyến mãi và voucher
- **Bình luận và đánh giá**: Hệ thống bình luận cho sản phẩm
- **Thống kê**: Báo cáo và thống kê cho admin
- **Hỗ trợ**: Hệ thống ticket hỗ trợ khách hàng

## Yêu Cầu Hệ Thống

- PHP >= 7.4
- MySQL hoặc MariaDB
- Composer
- Web server (Apache/Nginx) với mod_rewrite
- Node.js (cho frontend assets nếu cần)

## Cài Đặt

1. **Clone repository**:
   ```bash
   git clone <repository-url>
   cd B
   ```

2. **Cài đặt dependencies**:
   ```bash
   composer install
   ```

3. **Cấu hình database**:
   - Tạo database mới trong MySQL
   - Import các file migration trong thư mục `db_migrations/`
   - Cập nhật thông tin database trong file `app/config.php`

4. **Cấu hình VNPay**:
   - Cập nhật thông tin VNPay trong file `vnpay_php/config.php`

5. **Cấu hình email (PHPMailer)**:
   - Cập nhật thông tin SMTP trong file cấu hình PHPMailer

## Chạy Ứng Dụng

1. Đảm bảo web server trỏ đến thư mục `public/` hoặc cấu hình để `index.php` là entry point
2. Truy cập ứng dụng qua trình duyệt: `http://localhost/B`

## Cấu Trúc Thư Mục

```
B/
├── app/                    # Logic ứng dụng chính
│   ├── App.php            # Lớp ứng dụng chính
│   ├── Auth.php           # Xử lý xác thực
│   ├── config.php         # Cấu hình ứng dụng
│   ├── Controller.php     # Lớp controller cơ sở
│   ├── DB.php             # Kết nối database
│   └── MessageHelper.php  # Helper cho tin nhắn
├── cmap/                  # Thư viện Google Maps API
├── controllers/           # Controllers xử lý request
├── data/                  # Dữ liệu tĩnh (từ cấm)
├── db_migrations/         # Scripts migration database
├── models/                # Models tương tác database
├── public/                # Thư mục public (CSS, JS, images)
├── vendor/                # Dependencies từ Composer
├── views/                 # Templates giao diện
├── vnpay_php/             # Tích hợp VNPay
└── composer.json          # Cấu hình Composer
```

## Công Nghệ Sử Dụng

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Email**: PHPMailer
- **Maps**: Google Maps API
- **Payment**: VNPay
- **Dependency Management**: Composer

## API Endpoints

Ứng dụng sử dụng cấu trúc MVC với các controllers xử lý các endpoint khác nhau:

- `/admin/*` - Quản trị hệ thống
- `/auth/*` - Xác thực người dùng
- `/product/*` - Quản lý sản phẩm
- `/cart/*` - Giỏ hàng
- `/order/*` - Đơn hàng
- `/chat/*` - Chat system

## Bảo Mật

- Sử dụng prepared statements để tránh SQL injection
- Xác thực và phân quyền người dùng
- Bảo vệ CSRF
- Mã hóa mật khẩu
- Lọc từ cấm trong bình luận

## Phát Triển

Để phát triển thêm tính năng:

1. Tạo model mới trong thư mục `models/`
2. Tạo controller trong thư mục `controllers/`
3. Tạo view trong thư mục `views/`
4. Cập nhật routes trong `app/App.php`

## Đóng Góp

Nếu bạn muốn đóng góp cho dự án:

1. Fork repository
2. Tạo branch mới cho tính năng
3. Commit changes
4. Push và tạo Pull Request

## Giấy Phép

Dự án này sử dụng giấy phép MIT. Xem file LICENSE để biết thêm chi tiết.

## Liên Hệ

Nếu có câu hỏi hoặc cần hỗ trợ, vui lòng tạo issue trong repository hoặc liên hệ với maintainer.