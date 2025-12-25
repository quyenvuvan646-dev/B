<?php $products = $data["productList"] ?? []; $favoritesMap = $data["favoritesMap"] ?? []; ?>

<style>
.product-header {
    background: radial-gradient(circle at 10% 10%, rgba(96,165,250,0.12), transparent 25%),
                radial-gradient(circle at 90% 20%, rgba(59,130,246,0.14), transparent 30%),
                linear-gradient(140deg, #0b1220 0%, #102040 45%, #0a0f1d 100%);
    border-bottom: 1px solid #1f2a44;
    padding: 2.5rem 0 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 45px rgba(0,0,0,0.25);
}

.product-title {
    color: #e2e8f0;
    font-size: 2.15rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    margin-bottom: 0.5rem;
}

.product-subtitle {
    color: #94a3b8;
    margin: 0;
}

.pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 0.8rem;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 700;
    border: 1px solid rgba(148,163,184,0.25);
    background: rgba(148,163,184,0.12);
    color: #cbd5e1;
}

.pill.accent {
    border-color: rgba(59,130,246,0.35);
    background: rgba(59,130,246,0.16);
    color: #93c5fd;
}

.pill.success {
    border-color: rgba(16,185,129,0.35);
    background: rgba(16,185,129,0.12);
    color: #6ee7b7;
}

.product-filter-btn {
    background: linear-gradient(135deg, #3b5998 0%, #1e3a5f 100%);
    border: 1px solid #3b5998;
    color: #f1f5f9;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.product-filter-btn:hover {
    background: linear-gradient(135deg, #2d4478 0%, #0a1219 100%);
    box-shadow: 0 8px 24px rgba(59, 89, 152, 0.3);
}

.products-grid {
    display: flex;
    flex-direction: column;
    gap: 0.9rem;
}

.product-card {
    background: linear-gradient(135deg, #111827 0%, #0f172a 100%);
    border: 1px solid #1f2a44;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: row;
    align-items: stretch;
    min-height: 120px;
    padding: 0.75rem;
    gap: 0.9rem;
}

.product-card:hover {
    transform: translateY(-2px);
    border-color: #3b82f6;
    box-shadow: 0 12px 32px rgba(59, 130, 246, 0.18);
}

.product-image-wrapper {
    flex: 0 0 130px;
    height: 110px;
    background: #0b1220;
    border: 1px solid #1f2a44;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.promotion-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.75rem;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.product-content {
    padding: 0.35rem 0.35rem 0.35rem 0;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.product-name {
    color: #f8fafc;
    font-weight: 700;
    font-size: 0.95rem;
    margin-bottom: 0.2rem;
    min-height: auto;
    display: -webkit-box;
    line-clamp: 2;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.product-stars {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #fbbf24;
    font-size: 0.875rem;
}

.product-rating-count {
    color: #94a3b8;
    font-size: 0.75rem;
}

.product-stock {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: rgba(16, 185, 129, 0.08);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #86efac;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.product-price {
    color: #93c5fd;
    font-size: 1.05rem;
    font-weight: 800;
    margin: 0;
}

.product-actions {
    display: flex;
    gap: 0.4rem;
    margin-top: auto;
}

.product-btn {
    flex: 1;
    padding: 0.55rem 0.75rem;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.82rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
}

.btn-fav {
    background: rgba(244, 114, 182, 0.12);
    border: 1px solid rgba(236, 72, 153, 0.35);
    color: #f9a8d4;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
}

.btn-fav.active {
    background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
    border-color: #f472b6;
    color: #0b1220;
    box-shadow: 0 10px 30px rgba(244, 114, 182, 0.25);
}

.btn-detail {
    background: rgba(59, 89, 152, 0.15);
    border: 1px solid #3b5998;
    color: #93c5fd;
}

.btn-detail:hover {
    background: #3b5998;
    color: #f1f5f9;
}

.btn-add-cart {
    background: linear-gradient(135deg, #3b5998 0%, #1e3a5f 100%);
    color: #f1f5f9;
}

.btn-add-cart:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 89, 152, 0.3);
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
    border: 1px solid #334155;
    border-radius: 12px;
}

.empty-state-icon {
    font-size: 3rem;
    color: #3b5998;
    margin-bottom: 1rem;
}

.empty-state-text {
    color: #94a3b8;
    font-size: 1.125rem;
}

.product-count {
    color: #94a3b8;
    font-size: 0.875rem;
    margin-left: 1rem;
}

@media (max-width: 768px) {
    .product-title {
        font-size: 1.5rem;
    }

    .product-card {
        flex-direction: column;
        padding: 0.85rem;
    }

    .product-image-wrapper {
        flex: none;
        width: 100%;
        height: 160px;
    }

    .product-actions {
        flex-wrap: wrap;
    }
}
</style>

<!-- Product Header -->
<div class="product-header">
    <div class="container-fluid px-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-wrap gap-2">
                    <span class="pill accent"><i class="bi bi-search"></i> <?php echo !empty($data['searchTerm']) ? 'Kết quả tìm kiếm' : 'Khám phá sản phẩm'; ?></span>
                    <span class="pill success"><i class="bi bi-box"></i> <?php echo count($products); ?> sản phẩm</span>
                    <?php if (!empty($data['searchTerm'])): ?>
                        <span class="pill"><i class="bi bi-quote"></i> "<?= htmlspecialchars($data['searchTerm']) ?>"</span>
                    <?php endif; ?>
                </div>
                <h1 class="product-title">
                    <i class="bi bi-box2"></i>
                    <?php if (!empty($data['searchTerm'])): ?>
                        Tìm thấy <?= count($products) ?> sản phẩm
                    <?php else: ?>
                        Danh sách sản phẩm
                    <?php endif; ?>
                </h1>
                <p class="product-subtitle">
                    <?php if (!empty($data['searchTerm'])): ?>
                        Kết quả cho từ khóa <strong style="color: #60a5fa;">"<?= htmlspecialchars($data['searchTerm']) ?>"</strong>
                    <?php else: ?>
                        Lựa chọn nổi bật, sắp xếp theo đánh giá và mức độ liên quan.
                    <?php endif; ?>
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <?php if (!empty($data['searchTerm'])): ?>
                    <a href="<?= APP_URL ?>/Home/products" class="product-filter-btn" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); border-color: #6b7280;">
                        <i class="bi bi-x-circle"></i> Xóa tìm kiếm
                    </a>
                <?php endif; ?>
                <a href="<?= APP_URL ?>/Favorite" class="product-filter-btn" style="background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%); border-color: #f472b6;">
                    <i class="bi bi-heart-fill"></i> Danh sách yêu thích
                </a>
                <button class="product-filter-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                    <i class="bi bi-funnel"></i> Lọc & Sắp xếp
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="container-fluid px-4 py-5">
    <div class="products-grid">
        <?php if (empty($products)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <p class="empty-state-text">Không tìm thấy sản phẩm phù hợp.</p>
                <p class="subtitle-muted mt-2">
                    <a href="<?= APP_URL ?>/Home/products" class="text-decoration-none">Xem tất cả sản phẩm</a>
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $v): ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="<?php echo APP_URL; ?>/public/images/<?= htmlspecialchars($v['hinhanh']) ?>" 
                             class="product-image" 
                             alt="<?= htmlspecialchars($v['tensp']) ?>" 
                             onerror="this.style.display='none'">
                        <?php if ($v['has_promotion'] ?? false): ?>
                            <div class="promotion-badge">KM</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-content">
                        <h3 class="product-name">
                            <?= htmlspecialchars($v['tensp']) ?>
                        </h3>
                        
                        <!-- Rating -->
                        <?php $rating = $v['avg_rating'] ?? 0; $rc = $v['rating_count'] ?? 0; ?>
                        <div class="product-rating">
                            <div class="product-stars">
                                <?php
                                    $full = floor($rating);
                                    $half = ($rating - $full) >= 0.5 ? 1 : 0;
                                    for ($i = 0; $i < $full; $i++) echo '<i class="bi bi-star-fill"></i>';
                                    if ($half) echo '<i class="bi bi-star-half"></i>';
                                    for ($i = 0; $i < 5 - $full - $half; $i++) echo '<i class="bi bi-star"></i>';
                                ?>
                            </div>
                            <span class="product-rating-count">(<?= $rating ?> / <?= $rc ?>)</span>
                        </div>
                        
                        <!-- Stock -->
                        <div class="product-stock">
                            Còn: <?= (int)$v['soluong'] ?> sản phẩm
                        </div>
                        
                        <!-- Price -->
                        <?php 
                            $hasPromo = ($v['has_promotion'] ?? false) && (($v['discount_percent'] ?? 0) > 0);
                            if ($hasPromo) {
                                $old = (float)$v['giaXuat'];
                                $pct = (float)$v['discount_percent'];
                                $new = $old * (1 - $pct / 100.0);
                            }
                        ?>
                        <div class="product-price">
                            <?php if ($hasPromo): ?>
                                <span style="color:#94a3b8; text-decoration: line-through; margin-right:8px;">
                                    <?= number_format($old, 0, ',', '.') ?> ₫
                                </span>
                                <span style="color:#22c55e; font-weight:700;">
                                    <?= number_format($new, 0, ',', '.') ?> ₫
                                </span>
                                <span class="ms-2" style="color:#ef4444; font-weight:700;">-<?= (int)$pct ?>%</span>
                            <?php else: ?>
                                <?= number_format($v['giaXuat'], 0, ',', '.') ?> ₫
                            <?php endif; ?>
                        </div>
                        
                        <!-- Actions -->
                        <div class="product-actions">
                                <button type="button" class="product-btn btn-fav <?= isset($favoritesMap[$v['masp']]) ? 'active' : '' ?>" 
                                    data-product="<?= $v['masp'] ?>" 
                                    data-active="<?= isset($favoritesMap[$v['masp']]) ? '1' : '0' ?>">
                                <i class="bi <?= isset($favoritesMap[$v['masp']]) ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                                <span><?= isset($favoritesMap[$v['masp']]) ? 'Đã thích' : 'Yêu thích' ?></span>
                            </button>
                            <a href="<?php echo APP_URL; ?>/Home/detail/<?= $v['masp'] ?>" class="product-btn btn-detail">
                                <i class="bi bi-eye"></i> Xem chi tiết
                            </a>
                            <a href="<?php echo APP_URL; ?>/Home/addtocard/<?= $v['masp'] ?>" class="product-btn btn-add-cart">
                                <i class="bi bi-cart-plus"></i> Thêm giỏ
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Filter Offcanvas (từ HomeView) -->
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

            <!-- Product Type filter -->
            <div class="mb-4">
                <label class="filter-label">Loại sản phẩm</label>
                <div class="filter-options">
                    <?php 
                    // Lấy danh sách loại sản phẩm từ database
                    $productTypes = $data['allProductTypes'] ?? [];
                    foreach ($productTypes as $type):
                    ?>
                        <div class="form-check filter-check">
                            <input class="form-check-input" type="checkbox" name="product_type" 
                                   value="<?= htmlspecialchars($type['maloaisp'] ?? '') ?>" 
                                   id="type_<?= htmlspecialchars($type['maloaisp'] ?? '') ?>">
                            <label class="form-check-label" for="type_<?= htmlspecialchars($type['maloaisp'] ?? '') ?>">
                                <?= htmlspecialchars($type['tenloaisp'] ?? '') ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
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
// Toggle favorite
document.querySelectorAll('.btn-fav').forEach(btn => {
    btn.addEventListener('click', async () => {
        const code = btn.dataset.product;
        try {
            const res = await fetch(`<?= APP_URL ?>/Favorite/toggle/${code}`, { method: 'POST' });
            if (res.status === 401) {
                alert('Vui lòng đăng nhập để lưu yêu thích');
                window.location.href = '<?= APP_URL ?>/?url=Home/login';
                return;
            }
            const data = await res.json();
            if (data.success) {
                const active = data.favorited;
                btn.dataset.active = active ? '1' : '0';
                btn.classList.toggle('active', active);
                const icon = btn.querySelector('i');
                const label = btn.querySelector('span');
                if (icon) icon.className = active ? 'bi bi-heart-fill' : 'bi bi-heart';
                if (label) label.textContent = active ? 'Đã thích' : 'Yêu thích';
            } else if (data.message) {
                alert(data.message);
            }
        } catch (e) {
            alert('Không thể cập nhật yêu thích');
        }
    });
});

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
</script>
