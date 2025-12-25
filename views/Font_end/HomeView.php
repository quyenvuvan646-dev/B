<?php 
$products = $data["productList"] ?? [];
$banners = $data["banners"] ?? [];
?>

<!-- Banner Slider -->
<?php if (!empty($banners)): ?>
<section class="banner-slider-section" style="background: #0f172a; padding: 0;">
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($banners as $index => $banner): ?>
                <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="<?= $index ?>" 
                        class="<?= $index === 0 ? 'active' : '' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($banners as $index => $banner): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <?php if (!empty($banner['link_url'])): ?>
                        <a href="<?= APP_URL . htmlspecialchars($banner['link_url']) ?>">
                            <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($banner['image_path']) ?>" 
                                 class="d-block w-100 banner-image" 
                                 alt="<?= htmlspecialchars($banner['title']) ?>"
                                 onerror="this.src='<?= APP_URL ?>/public/images/default-banner.jpg'">
                        </a>
                    <?php else: ?>
                        <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($banner['image_path']) ?>" 
                             class="d-block w-100 banner-image" 
                             alt="<?= htmlspecialchars($banner['title']) ?>"
                             onerror="this.src='<?= APP_URL ?>/public/images/default-banner.jpg'">
                    <?php endif; ?>
                    <?php if (!empty($banner['title'])): ?>
                        <div class="carousel-caption d-none d-md-block">
                            <h3 class="banner-title"><?= htmlspecialchars($banner['title']) ?></h3>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<style>
.banner-slider-section {
    margin-top: -1px; /* Remove gap between navbar and slider */
}

.banner-image {
    height: 500px;
    object-fit: cover;
    object-position: center;
}

.banner-title {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
    padding: 15px 30px;
    border-radius: 10px;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    display: inline-block;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.5);
}

.carousel-indicators button.active {
    background-color: #667eea;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(102, 126, 234, 0.8);
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    padding: 0.5rem;
}

.carousel-control-prev:hover .carousel-control-prev-icon,
.carousel-control-next:hover .carousel-control-next-icon {
    background-color: rgba(102, 126, 234, 1);
}

.search-result-header .text-info {
    color: #60a5fa !important;
    text-shadow: 0 0 10px rgba(96, 165, 250, 0.5);
}

.search-result-header .badge-soft {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    animation: pulse-glow 2s infinite;
}

@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
    }
    50% {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
    }
}

@media (max-width: 768px) {
    .banner-image {
        height: 250px;
    }
    
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 2rem;
        height: 2rem;
    }
}
</style>
<?php endif; ?>

<!-- Hero section -->
<section class="hero-dark py-5 products-page">
    <div class="container py-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <?php if (!empty($data['searchTerm'])): ?>
                    <div class="search-result-header mb-4">
                        <span class="badge badge-soft mb-2">
                            <i class="bi bi-search"></i> Kết quả tìm kiếm
                        </span>
                        <h1 class="display-5 fw-bold mb-3">
                            Tìm thấy <?= count($products) ?> sản phẩm
                        </h1>
                        <p class="lead subtitle-muted mb-4">
                            Kết quả cho từ khóa: <strong class="text-info">"<?= htmlspecialchars($data['searchTerm']) ?>"</strong>
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="<?php echo APP_URL; ?>/Home/products" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-arrow-left"></i> Xem tất cả sản phẩm
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <span class="badge badge-soft mb-3">Phương Nam Marketplace</span>
                    <h1 class="display-5 fw-bold mb-3">Bộ sưu tập mùa lễ hội — Ưu đãi độc quyền</h1>
                    <p class="lead subtitle-muted mb-4">Khám phá hàng nghìn sản phẩm, thanh toán linh hoạt, giao hàng nhanh. Áp dụng voucher admin và distributor để tối ưu chi phí.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?php echo APP_URL; ?>/Home/products" class="btn btn-glow btn-lg">Xem sản phẩm</a>
                        <a href="<?php echo APP_URL; ?>/Home/checkout" class="btn btn-outline-light btn-lg">Thanh toán nhanh</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 text-center">
                <div class="card product-card p-4">
                    <h5 class="mb-3">Lợi ích khi mua sắm</h5>
                    <ul class="list-unstyled text-start subtitle-muted mb-0">
                        <li class="mb-2">• Thanh toán COD hoặc VNPAY</li>
                        <li class="mb-2">• Tính phí ship theo khoảng cách</li>
                        <li class="mb-2">• Voucher admin + distributor</li>
                        <li class="mb-2">• Chat và thông báo đơn hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chips categories -->
<section class="section-dark py-4 products-page">
    <div class="container">
        <div class="d-flex flex-wrap gap-2">
            <span class="pill-chip">Chó</span>
            <span class="pill-chip">Mèo</span>
            <span class="pill-chip">Chim</span>
            <span class="pill-chip">Thú dữ</span>
            <span class="pill-chip">Phụ kiện</span>
            <span class="pill-chip">Thời trang</span>
            <span class="pill-chip">Laptop</span>
            <span class="pill-chip">Màn hình</span>
            <span class="pill-chip">Gaming</span>
        </div>
    </div>
</section>

<!-- Feature stats -->
<section class="section-dark py-5 products-page">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="feature-card text-center">
                    <h3 class="text-white">5.0/5</h3>
                    <p class="subtitle-muted mb-0">Đánh giá người mua</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card text-center">
                    <h3 class="text-white">24/7</h3>
                    <p class="subtitle-muted mb-0">Hỗ trợ trực tuyến</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card text-center">
                    <h3 class="text-white">-30%</h3>
                    <p class="subtitle-muted mb-0">Ưu đãi voucher</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card text-center">
                    <h3 class="text-white">3 phương án</h3>
                    <p class="subtitle-muted mb-0">Ship tiêu chuẩn / nhanh / hỏa tốc</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product grid -->
<div class="product-wrap py-5 products-page">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <p class="subtitle-muted mb-1 text-uppercase" style="letter-spacing: .1em;">Bộ sưu tập</p>
                <h2 class="fw-bold text-white mb-0">Sản phẩm nổi bật (<?= count($products) ?> sản phẩm)</h2>
            </div>
            
            <!-- Filter Button -->
            <button class="btn btn-outline-light d-flex align-items-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                <i class="bi bi-funnel"></i> Lọc & Sắp xếp
            </button>
        </div>

        <div class="row g-4">
            <?php if (empty($products)): ?>
                <div class="col-12">
                    <div class="alert alert-dark-info">Không tìm thấy sản phẩm phù hợp.</div>
                </div>
            <?php else: ?>
                <?php foreach ($products as $v): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100 p-3 rounded-4">
                            <a href="<?php echo APP_URL; ?>/Home/detail/<?= $v['masp'] ?>" class="text-decoration-none text-white">
                                <div class="product-img mb-3">
                                    <img src="<?php echo APP_URL; ?>/public/images/<?= htmlspecialchars($v['hinhanh']) ?>" class="w-100" style="height: 180px; object-fit: contain;" alt="<?= htmlspecialchars($v['tensp']) ?>" onerror="this.style.display='none'">
                                </div>
                                <h5 class="fw-semibold mb-2" style="min-height:48px;"><?= htmlspecialchars($v['tensp']) ?></h5>
                            </a>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <?php $rating = $v['avg_rating'] ?? 0; $rc = $v['rating_count'] ?? 0; ?>
                                <div class="d-flex align-items-center gap-1 text-warning" style="font-size:14px;">
                                    <?php
                                        $full = floor($rating);
                                        $half = ($rating - $full) >= 0.5 ? 1 : 0;
                                        for ($i = 0; $i < $full; $i++) echo '<i class="bi bi-star-fill"></i>';
                                        if ($half) echo '<i class="bi bi-star-half"></i>';
                                        for ($i = 0; $i < 5 - $full - $half; $i++) echo '<i class="bi bi-star"></i>';
                                    ?>
                                    <span class="text-muted ms-1" style="font-size:13px;">(<?= $rating ?> / <?= $rc ?>)</span>
                                </div>
                                <span class="badge text-bg-dark border border-secondary">Còn: <?= (int)$v['soluong'] ?></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="price-tag fs-5"><?= number_format($v['giaXuat'], 0, ',', '.') ?> ₫</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="<?php echo APP_URL; ?>/Home/detail/<?= $v['masp'] ?>" class="btn btn-outline-light w-50">Xem chi tiết</a>
                                <a href="<?php echo APP_URL; ?>/Home/addtocard/<?= $v['masp'] ?>" class="btn btn-glow w-50">Thêm giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Filter Offcanvas -->
<div class="offcanvas offcanvas-end filter-offcanvas" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title" id="filterOffcanvasLabel">
            <i class="bi bi-funnel"></i> Lọc & Sắp xếp
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="filterForm" method="GET" action="<?= APP_URL ?>/Home/products">
            
            <!-- Sort by -->
            <div class="mb-4">
                <label class="filter-label">Sắp xếp theo</label>
                <div class="filter-options">
                    <div class="form-check filter-check">
                        <input class="form-check-input" type="radio" name="sort" id="sort_default" value="default" checked>
                        <label class="form-check-label" for="sort_default">
                            Mặc định
                        </label>
                    </div>
                    <div class="form-check filter-check">
                        <input class="form-check-input" type="radio" name="sort" id="sort_rating" value="rating">
                        <label class="form-check-label" for="sort_rating">
                            <i class="bi bi-star-fill"></i> Bán chạy nhất
                        </label>
                    </div>
                    <div class="form-check filter-check">
                        <input class="form-check-input" type="radio" name="sort" id="sort_price_low" value="price_low">
                        <label class="form-check-label" for="sort_price_low">
                            <i class="bi bi-arrow-down"></i> Giá thấp đến cao
                        </label>
                    </div>
                    <div class="form-check filter-check">
                        <input class="form-check-input" type="radio" name="sort" id="sort_price_high" value="price_high">
                        <label class="form-check-label" for="sort_price_high">
                            <i class="bi bi-arrow-up"></i> Giá cao đến thấp
                        </label>
                    </div>
                    <div class="form-check filter-check">
                        <input class="form-check-input" type="radio" name="sort" id="sort_newest" value="newest">
                        <label class="form-check-label" for="sort_newest">
                            <i class="bi bi-lightning-fill"></i> Sản phẩm mới
                        </label>
                    </div>
                </div>
            </div>

            <!-- Price range filter -->
            <div class="mb-4">
                <label class="filter-label">Khoảng giá</label>
                <div class="filter-options">
                    <div class="form-check filter-check">
                        <input class="form-check-input price-filter" type="checkbox" name="price" value="0-500000" id="price1">
                        <label class="form-check-label" for="price1">
                            Dưới 500K
                        </label>
                    </div>
                    <div class="form-check filter-check">
                        <input class="form-check-input price-filter" type="checkbox" name="price" value="500000-1000000" id="price2">
                        <label class="form-check-label" for="price2">
                            500K - 1 triệu
                        </label>
                    </div>
                    <div class="form-check filter-check">
                        <input class="form-check-input price-filter" type="checkbox" name="price" value="1000000-5000000" id="price3">
                        <label class="form-check-label" for="price3">
                            1 - 5 triệu
                        </label>
                    </div>
                    <div class="form-check filter-check">
                        <input class="form-check-input price-filter" type="checkbox" name="price" value="5000000-" id="price4">
                        <label class="form-check-label" for="price4">
                            Trên 5 triệu
                        </label>
                    </div>
                </div>
            </div>

            <!-- Stock filter -->
            <div class="mb-4">
                <label class="filter-label">Trạng thái kho</label>
                <div class="filter-options">
                    <div class="form-check filter-check">
                        <input class="form-check-input" type="checkbox" name="stock" value="instock" id="stock_in" checked>
                        <label class="form-check-label" for="stock_in">
                            <i class="bi bi-check-circle"></i> Còn hàng
                        </label>
                    </div>
                </div>
            </div>

            <!-- Rating filter -->
            <div class="mb-4">
                <label class="filter-label">Đánh giá</label>
                <div class="filter-options">
                    <div class="form-check filter-check">
                        <input class="form-check-input" type="checkbox" name="rating" value="4" id="rating_4plus">
                        <label class="form-check-label" for="rating_4plus">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i> Từ 4.0 sao
                        </label>
                    </div>
                    <div class="form-check filter-check">
                        <input class="form-check-input" type="checkbox" name="rating" value="3" id="rating_3plus">
                        <label class="form-check-label" for="rating_3plus">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i> Từ 3.0 sao
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Áp dụng lọc
                </button>
                <button type="reset" class="btn btn-outline-light w-100">
                    <i class="bi bi-arrow-clockwise"></i> Đặt lại
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.filter-offcanvas {
    --bs-offcanvas-bg: #0f172a;
    border-left: 1px solid #334155;
}

.filter-offcanvas .offcanvas-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
    color: #f1f5f9;
}

.filter-offcanvas .offcanvas-header .btn-close {
    filter: brightness(0) invert(1);
}

.filter-label {
    color: #94a3b8;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
    margin-bottom: 1rem;
    display: block;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.filter-check {
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.filter-check:hover {
    border-color: #3b5998;
    background: #1e3a5f;
}

.filter-check .form-check-input {
    border-color: #334155;
    accent-color: #3b5998;
}

.filter-check .form-check-label {
    color: #f1f5f9;
    cursor: pointer;
    margin: 0;
    font-weight: 500;
}

.filter-check i {
    color: #fbbf24;
    font-size: 0.875rem;
    margin-right: 0.25rem;
}
</style>

<script>
// Handle form submission - build proper URL with query params
document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const params = new URLSearchParams();
    
    // Get selected sort
    const sortRadio = document.querySelector('input[name="sort"]:checked');
    if (sortRadio && sortRadio.value !== 'default') {
        params.append('sort', sortRadio.value);
    }
    
    // Get selected prices
    const selectedPrices = Array.from(document.querySelectorAll('input[name="price"]:checked'));
    selectedPrices.forEach(price => {
        params.append('price', price.value);
    });
    
    // Get selected ratings
    const selectedRatings = Array.from(document.querySelectorAll('input[name="rating"]:checked'));
    selectedRatings.forEach(rating => {
        params.append('rating', rating.value);
    });
    
    // Get stock filter
    const stockCheck = document.querySelector('input[name="stock"]:checked');
    if (stockCheck) {
        params.append('stock', stockCheck.value);
    }
    
    const filterUrl = '<?= APP_URL ?>/Home/products' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = filterUrl;
});

// Auto-submit on sort change
document.querySelectorAll('input[name="sort"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Auto-update form when checkboxes change (visual feedback)
document.querySelectorAll('.form-check-input').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (this.name === 'stock') {
            // Stock checkbox stays checked/unchecked as is
        }
    });
});
</script>

<!-- Info Cards Section -->
<section class="py-5 products-page" style="background: #0f172a;">
    <div class="container">
        <div class="row g-4">
            <!-- Card 1: Định tuyến giao hàng -->
            <div class="col-md-4">
                <div class="info-card-modern">
                    <div class="info-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h5 class="text-white mb-2">Định tuyến giao hàng</h5>
                    <p class="text-white-50 mb-0" style="font-size: 0.9rem;">Tính quãng đường, phí ship hợp lý.</p>
                </div>
            </div>
            
            <!-- Card 2: Hỗ trợ 24/7 -->
            <div class="col-md-4">
                <div class="info-card-modern">
                    <div class="info-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <h5 class="text-white mb-2">Hỗ trợ 24/7</h5>
                    <p class="text-white-50 mb-0" style="font-size: 0.9rem;">Đội ngũ hỗ trợ phản hồi nhanh chóng.</p>
                </div>
            </div>
            
            <!-- Card 3: Trung tâm trợ giúp -->
            <div class="col-md-4">
                <div class="info-card-modern">
                    <div class="info-icon">
                        <i class="bi bi-chat-square-text-fill"></i>
                    </div>
                    <h5 class="text-white mb-2">Trung tâm trợ giúp</h5>
                    <p class="text-white-50 mb-0" style="font-size: 0.9rem;">Chat AI, liên hệ admin, xem FAQ.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Info Cards Modern Style */
.info-card-modern {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border: 1px solid rgba(148, 163, 184, 0.1);
    border-radius: 12px;
    padding: 2rem;
    transition: all 0.3s ease;
    height: 100%;
}

.info-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.3);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
}

.info-icon i {
    color: white;
    font-size: 1.5rem;
}

@media (max-width: 768px) {
    .info-card-modern {
        padding: 1.5rem;
    }
}
</style>


