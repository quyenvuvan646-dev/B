<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
  .landing-hero {
    background: radial-gradient(circle at 10% 20%, rgba(59, 130, 246, .25) 0, transparent 25%),
      radial-gradient(circle at 90% 10%, rgba(16, 185, 129, .2) 0, transparent 20%),
      radial-gradient(circle at 20% 80%, rgba(236, 72, 153, .18) 0, transparent 22%),
      linear-gradient(135deg, #0f172a 0%, #0b1020 55%, #0f172a 100%);
    color: #e2e8f0;
  }

  .landing-hero h1 {
    color: #f8fafc;
  }

  .landing-hero .lead {
    color: #cbd5e1;
  }

  .pill-chip {
    background: #111827;
    border: 1px solid #1f2937;
    color: #e2e8f0;
    padding: 10px 14px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 4px;
  }

  .pill-chip i {
    color: #60a5fa;
  }

  .section-surface {
    background: #0f172a;
    color: #e2e8f0;
    border-top: 1px solid #1f2937;
    border-bottom: 1px solid #1f2937;
  }

  .card-dark {
    background: #0b1220;
    border: 1px solid #1f2937;
    color: #e2e8f0;
  }

  .card-dark .card-text,
  .card-dark li {
    color: #cbd5e1;
  }

  .feature-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #111827;
    color: #60a5fa;
  }

  .feature-card {
    background: #0b1220;
    border: 1px solid #1f2937;
    border-radius: 12px;
    padding: 16px;
    color: #e2e8f0;
  }

  .cta-banner {
    background: linear-gradient(90deg, rgba(37, 99, 235, .25), rgba(59, 130, 246, .05));
    border: 1px solid rgba(59, 130, 246, .2);
    border-radius: 16px;
    color: #e2e8f0;
    padding: 24px;
    box-shadow: 0 8px 24px rgba(59, 130, 246, .25);
  }

  .timeline {
    border-left: 2px solid #1f2937;
    padding-left: 18px;
  }

  .timeline .step {
    position: relative;
    margin-bottom: 18px;
  }

  .timeline .step::before {
    content: "";
    position: absolute;
    left: -10px;
    top: 4px;
    width: 10px;
    height: 10px;
    background: #2563eb;
    border-radius: 50%;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, .25);
  }

  .logo-text {
    letter-spacing: .08em;
    text-transform: uppercase;
    font-weight: 700;
    color: #93c5fd;
  }

  .section-subtitle {
    color: #cbd5e1;
  }

  .section-subtitle li {
    margin-bottom: 6px;
  }

  .stat-card {
    background: #0b1220;
    border: 1px solid #1f2937;
    border-radius: 14px;
    padding: 18px;
    color: #e2e8f0;
    text-align: center;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #93c5fd;
  }

  .category-card {
    background: #0b1220;
    border: 1px solid #1f2937;
    border-radius: 12px;
    padding: 16px;
    height: 100%;
    color: #e2e8f0;
  }

  .badge-soft {
    background: rgba(59, 130, 246, .15);
    color: #bfdbfe;
    border: 1px solid rgba(59, 130, 246, .3);
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
  }

  .faq-card {
    background: #0b1220;
    border: 1px solid #1f2937;
    border-radius: 12px;
    padding: 14px;
    color: #e2e8f0;
  }
</style>

<div class="landing-hero py-5">
  <div class="container py-4">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <span class="logo-text">Phương Nam Marketplace</span>
        <h1 class="fw-bold display-5 mt-2">Nền tảng kết nối mua - bán - phân phối</h1>
        <p class="lead">Mua sắm nhanh, giao hàng đa lựa chọn, thanh toán linh hoạt. Nhà phân phối đăng ký, ký hợp đồng điện tử và bán ngay.</p>
        <div class="d-flex flex-wrap gap-2 mt-3">
          <a href="<?php echo APP_URL; ?>/AuthController/ShowLogin" class="btn btn-primary btn-lg">Đăng ký thành viên</a>
          <a href="<?php
                    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
                    echo isset($_SESSION['user']) ? APP_URL . '/Home/distributorRegister' : APP_URL . '/AuthController/ShowLogin?redirect=' . urlencode(APP_URL . '/Home/distributorRegister');
                    ?>" class="btn btn-outline-light btn-lg">Đăng ký kinh doanh (Distributor)</a>
          <a href="<?php echo APP_URL; ?>/Support" class="btn btn-warning btn-lg position-relative">
            <i class="bi bi-headset"></i> Hỗ trợ
            <span class="support-response-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.65rem;">
              0
            </span>
          </a>
          <a href="<?php echo APP_URL; ?>/Home/products" class="btn btn-link text-light">Xem sản phẩm</a>
        </div>
        <div class="d-flex flex-wrap mt-3">
          <span class="pill-chip"><i class="bi bi-shield-check"></i> Thanh toán an toàn</span>
          <span class="pill-chip"><i class="bi bi-truck"></i> Ship nhanh - hỏa tốc</span>
          <span class="pill-chip"><i class="bi bi-percent"></i> Voucher đa tầng</span>
          <span class="pill-chip"><i class="bi bi-chat-dots"></i> Chat & thông báo</span>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <img src="<?php echo APP_URL; ?>/public/images/landing-hero.png" alt="Ecommerce" class="img-fluid" onerror="this.style.display='none'">
      </div>
    </div>
  </div>
</div>

<div class="section-surface py-5 border-top">
  <div class="container">
    <div class="row g-3">
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-value">50K+</div>
          <div class="section-subtitle">Đơn hàng xử lý</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-value">1K+</div>
          <div class="section-subtitle">Nhà phân phối</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-value">24/7</div>
          <div class="section-subtitle">Hỗ trợ trực tuyến</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-value">4.8/5</div>
          <div class="section-subtitle">Đánh giá hài lòng</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section-surface py-5 border-top">
  <div class="container">
    <div class="row g-4 align-items-start">
      <div class="col-lg-5">
        <h4 class="text-white fw-bold">Danh mục nổi bật</h4>
        <p class="section-subtitle">Hàng mới mỗi ngày, ưu đãi theo mùa và theo khu vực giao hàng.</p>
        <div class="d-flex flex-wrap gap-2">
          <span class="badge-soft">Đồ gia dụng</span>
          <span class="badge-soft">Mẹ & bé</span>
          <span class="badge-soft">Thời trang</span>
          <span class="badge-soft">Sức khỏe</span>
          <span class="badge-soft">Đồ uống</span>
          <span class="badge-soft">Điện tử</span>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="row g-3">
          <div class="col-md-4">
            <div class="category-card">
              <h6 class="text-white">Ưu đãi độc quyền</h6>
              <p class="section-subtitle mb-0">Combo giá tốt cho đơn khu vực.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="category-card">
              <h6 class="text-white">Giao nhanh nội thành</h6>
              <p class="section-subtitle mb-0">Ship trong ngày hoặc hỏa tốc.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="category-card">
              <h6 class="text-white">Giá sỉ cho distributor</h6>
              <p class="section-subtitle mb-0">Chiết khấu theo sản lượng.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section-surface py-5 border-top">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card card-dark h-100">
          <div class="card-body">
            <h5 class="text-white">Quyền lợi cho Distributor</h5>
            <ul class="section-subtitle ps-3 mb-0">
              <li>Ký hợp đồng điện tử, quản lý công nợ minh bạch.</li>
              <li>Dashboard doanh thu, biên lợi nhuận theo thời gian.</li>
              <li>Tuỳ chỉnh phí ship, voucher, mô tả sản phẩm.</li>
              <li>Hỗ trợ marketing, push banner theo chiến dịch.</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card card-dark h-100">
          <div class="card-body">
            <h5 class="text-white">Quyền lợi cho Người mua</h5>
            <ul class="section-subtitle ps-3 mb-0">
              <li>Tìm kiếm nhanh, voucher x2 (admin + distributor).</li>
              <li>Thanh toán an toàn: COD, VNPAY.</li>
              <li>Theo dõi hành trình giao hàng và thông báo realtime.</li>
              <li>Chính sách đổi trả rõ ràng, hỗ trợ 24/7.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section-surface py-5 border-top">
  <div class="container">
    <h4 class="text-white fw-bold mb-3">Câu hỏi thường gặp</h4>
    <div class="row g-3">
      <div class="col-md-6">
        <div class="faq-card">
          <h6 class="text-white mb-1">Làm sao để trở thành distributor?</h6>
          <p class="section-subtitle mb-0">Đăng ký tài khoản, gửi hồ sơ kinh doanh, ký hợp đồng điện tử và chờ duyệt. Sau khi duyệt, bạn có thể đăng sản phẩm và mở bán.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="faq-card">
          <h6 class="text-white mb-1">Tôi có thể kết hợp nhiều voucher?</h6>
          <p class="section-subtitle mb-0">Hệ thống cho phép áp dụng đồng thời voucher admin và voucher của distributor nếu điều kiện thỏa mãn.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="faq-card">
          <h6 class="text-white mb-1">Phí vận chuyển được tính thế nào?</h6>
          <p class="section-subtitle mb-0">Phí ship dựa trên khoảng cách thực tế và phương án giao (nhanh / hỏa tốc) mà bạn chọn khi đặt hàng.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="faq-card">
          <h6 class="text-white mb-1">Có hỗ trợ thanh toán không thành công?</h6>
          <p class="section-subtitle mb-0">Nếu thanh toán VNPAY thất bại, đơn sẽ giữ ở trạng thái chờ. Bạn có thể thanh toán lại hoặc chuyển sang COD.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section-surface py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card card-dark h-100 shadow-sm border-0">
          <div class="card-body">
            <div class="feature-icon mb-3"><i class="bi bi-stars"></i></div>
            <h5 class="card-title text-white">Giới thiệu</h5>
            <p class="card-text">Nền tảng giúp người bán và nhà phân phối tiếp cận khách hàng nhanh chóng, quản lý đơn hàng, vận chuyển và thanh toán trong một nơi duy nhất.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-dark h-100 shadow-sm border-0">
          <div class="card-body">
            <div class="feature-icon mb-3"><i class="bi bi-shield-lock"></i></div>
            <h5 class="card-title text-white">Điều khoản sử dụng</h5>
            <ul class="mb-0 ps-3">
              <li>Không đăng bán hàng cấm, hàng giả.</li>
              <li>Tuân thủ chính sách bảo mật và thanh toán an toàn.</li>
              <li>Nhà phân phối ký hợp đồng và được admin duyệt.</li>
              <li>Tuân thủ quy định giao nhận và hoàn trả.</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-dark h-100 shadow-sm border-0">
          <div class="card-body">
            <div class="feature-icon mb-3"><i class="bi bi-diagram-3"></i></div>
            <h5 class="card-title text-white">Quy trình đăng ký Distributor</h5>
            <ol class="mb-0 ps-3">
              <li>Đăng ký tài khoản / đăng nhập.</li>
              <li>Nộp thông tin kinh doanh, ký hợp đồng trực tuyến.</li>
              <li>Chờ admin xét duyệt hợp đồng.</li>
              <li>Đăng sản phẩm, cấu hình vận chuyển, nhận đơn.</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section-surface py-5 border-top">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-7">
        <h4 class="fw-bold text-white">Tính năng nổi bật</h4>
        <ul class="section-subtitle mb-3">
          <li>Quản lý voucher admin và distributor.</li>
          <li>Giỏ hàng, thanh toán COD và VNPAY.</li>
          <li>Tính phí vận chuyển theo khoảng cách.</li>
          <li>Chat và thông báo đơn hàng tự động.</li>
        </ul>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="feature-card h-100">
              <div class="feature-icon mb-2"><i class="bi bi-graph-up"></i></div>
              <h6 class="text-white mb-1">Thống kê trực quan</h6>
              <p class="section-subtitle mb-0">Dashboard cho admin và distributor.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="feature-card h-100">
              <div class="feature-icon mb-2"><i class="bi bi-bag-check"></i></div>
              <h6 class="text-white mb-1">Quy trình đơn hàng</h6>
              <p class="section-subtitle mb-0">Từ đặt hàng, thanh toán đến giao nhận.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="cta-banner">
          <h4 class="mb-2">Bắt đầu ngay</h4>
          <p class="mb-3">Đăng ký tài khoản để mua sắm hoặc trở thành distributor để bán hàng. Mọi quy trình được số hóa và minh bạch.</p>
          <div class="d-flex gap-2">
            <a href="<?php echo APP_URL; ?>/AuthController/ShowLogin" class="btn btn-primary">Đăng ký / Đăng nhập</a>
            <a href="<?php echo APP_URL; ?>/Home/products" class="btn btn-outline-light">Xem sản phẩm</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Lộ trình dịch vụ -->
<div class="section-surface py-5">
  <div class="container">
    <div class="row g-4 align-items-start">
      <div class="col-lg-6">
        <h4 class="text-white fw-bold mb-3">Hành trình mua sắm & phân phối</h4>
        <div class="timeline">
          <div class="step">
            <h6 class="text-white mb-1">Khám phá & chọn sản phẩm</h6>
            <p class="section-subtitle mb-0">Lọc, tìm kiếm, áp voucher để tối ưu chi phí.</p>
          </div>
          <div class="step">
            <h6 class="text-white mb-1">Thanh toán linh hoạt</h6>
            <p class="section-subtitle mb-0">COD hoặc VNPAY, xác nhận đơn tức thì.</p>
          </div>
          <div class="step">
            <h6 class="text-white mb-1">Giao hàng tối ưu</h6>
            <p class="section-subtitle mb-0">Tính phí ship theo khoảng cách, chọn phương án nhanh / hỏa tốc.</p>
          </div>
          <div class="step">
            <h6 class="text-white mb-1">Hậu mãi & hỗ trợ</h6>
            <p class="section-subtitle mb-0">Chat, thông báo, hoàn trả theo chính sách minh bạch.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <h4 class="text-white fw-bold mb-3">Ưu đãi & bảo chứng</h4>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="feature-card h-100">
              <div class="feature-icon mb-2"><i class="bi bi-shield-check"></i></div>
              <h6 class="text-white mb-1">Bảo vệ người mua</h6>
              <p class="section-subtitle mb-0">Hoàn tiền theo quy định khi có sự cố.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="feature-card h-100">
              <div class="feature-icon mb-2"><i class="bi bi-gift"></i></div>
              <h6 class="text-white mb-1">Voucher đa tầng</h6>
              <p class="section-subtitle mb-0">Kết hợp voucher admin và distributor.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="feature-card h-100">
              <div class="feature-icon mb-2"><i class="bi bi-geo"></i></div>
              <h6 class="text-white mb-1">Định tuyến giao hàng</h6>
              <p class="section-subtitle mb-0">Tính quãng đường, phí ship hợp lý.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="feature-card h-100">
              <div class="feature-icon mb-2"><i class="bi bi-clock-history"></i></div>
              <h6 class="text-white mb-1">Hỗ trợ 24/7</h6>
              <p class="section-subtitle mb-0">Đội ngũ hỗ trợ phản hồi nhanh chóng.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="feature-card h-100">
              <div class="feature-icon mb-2"><i class="bi bi-chat-left-text"></i></div>
              <h6 class="text-white mb-1">Trung tâm trợ giúp</h6>
              <p class="section-subtitle mb-0">Chat AI, liên hệ admin, xem FAQ.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Load support response badge count for logged-in users
  function loadSupportResponseCount() {
    fetch('<?php echo APP_URL; ?>/?url=Support/getUnreadResponseCount')
      .then(response => response.json())
      .then(data => {
        const badge = document.querySelector('.support-response-badge');
        if (badge && data.count > 0) {
          badge.textContent = data.count;
          badge.style.display = 'block';
        } else if (badge) {
          badge.style.display = 'none';
        }
      })
      .catch(err => console.error('Error loading support response count:', err));
  }

  // Load on page load if user is logged in
  <?php if (isset($_SESSION['user'])): ?>
    document.addEventListener('DOMContentLoaded', function() {
      loadSupportResponseCount();
      // Auto-refresh every 30 seconds
      setInterval(loadSupportResponseCount, 30000);
    });
  <?php endif; ?>
</script>