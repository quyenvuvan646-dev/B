<div class="container-fluid py-4">
    <h2 class="mb-4">📊 Thống kê toàn hệ thống</h2>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="controller" value="Admin">
                <input type="hidden" name="action" value="statistics">
                
                <div class="col-md-3">
                    <label class="form-label">Lọc theo</label>
                    <select name="filterType" id="filterType" class="form-select" onchange="toggleFilterInputs()">
                        <option value="all" <?= $data['filterType'] === 'all' ? 'selected' : '' ?>>Tất cả thời gian</option>
                        <option value="day" <?= $data['filterType'] === 'day' ? 'selected' : '' ?>>Ngày</option>
                        <option value="month" <?= $data['filterType'] === 'month' ? 'selected' : '' ?>>Tháng</option>
                        <option value="year" <?= $data['filterType'] === 'year' ? 'selected' : '' ?>>Năm</option>
                    </select>
                </div>
                
                <div class="col-md-3" id="filterDayWrapper" style="display: <?= $data['filterType']==='day' ? 'block' : 'none' ?>;">
                    <label class="form-label">Chọn ngày</label>
                    <input type="date" name="day" class="form-control" value="<?= htmlspecialchars($data['filterType']==='day' ? ($data['filterValue'] ?? '') : '') ?>">
                </div>

                <div class="col-md-3" id="filterMonthWrapper" style="display: <?= $data['filterType']==='month' ? 'block' : 'none' ?>;">
                    <label class="form-label">Chọn tháng</label>
                    <input type="month" name="month" class="form-control" value="<?= htmlspecialchars($data['filterType']==='month' ? ($data['filterValue'] ?? '') : '') ?>">
                </div>

                <div class="col-md-3" id="filterYearWrapper" style="display: <?= $data['filterType']==='year' ? 'block' : 'none' ?>;">
                    <label class="form-label">Chọn năm</label>
                    <input type="number" name="year" min="2020" max="2099" class="form-control" value="<?= htmlspecialchars($data['filterType']==='year' ? ($data['filterValue'] ?? date('Y')) : date('Y')) ?>">
                </div>

                <div class="col-md-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">🔍 Lọc</button>
                    <a class="btn btn-outline-secondary" href="<?= APP_URL ?>/Admin/statistics">Xóa lọc</a>
                    <?php if ($data['filterType'] !== 'all'): ?>
                        <span class="badge bg-info text-dark align-self-center">Đang lọc: <?= htmlspecialchars($data['filterType']) ?> = <?= htmlspecialchars($data['filterValue'] ?? '') ?></span>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <!-- Doanh thu tổng -->
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">💰 Doanh thu tổng</h6>
                    <h3><?= number_format($data['totalRevenue']['total_revenue'] ?? 0, 0, ',', '.') ?>₫</h3>
                    <small><?= ($data['totalRevenue']['order_count'] ?? 0) ?> đơn hàng</small>
                </div>
            </div>
        </div>

        <!-- Tổng đơn hàng -->
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">📦 Tổng đơn hàng</h6>
                    <h3><?= $data['orderStats']['total_orders'] ?? 0 ?></h3>
                    <small>✓ <?= $data['orderStats']['completed_orders'] ?? 0 ?> | ⏳ <?= $data['orderStats']['pending_orders'] ?? 0 ?></small>
                </div>
            </div>
        </div>

        <!-- Tổng người dùng -->
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">👥 Tổng người dùng</h6>
                    <h3><?= $data['userStats']['total_users'] ?? 0 ?></h3>
                    <small>👤 <?= $data['userStats']['buyers'] ?? 0 ?> | 🏪 <?= $data['userStats']['distributors'] ?? 0 ?> | 🚚 <?= $data['userStats']['shippers'] ?? 0 ?></small>
                </div>
            </div>
        </div>

        <!-- Tổng sản phẩm -->
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">📚 Tổng sản phẩm</h6>
                    <h3><?= $data['productStats']['total_products'] ?? 0 ?></h3>
                    <small><?= $data['productStats']['total_types'] ?? 0 ?> loại | <?= $data['productStats']['total_distributors'] ?? 0 ?> distributor</small>
                </div>
            </div>
        </div>

        <!-- Đơn hàng hết hàng -->
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-danger text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">🚨 Đơn hàng hết hàng</h6>
                    <h3><?= $data['outOfStockOrders'] ?></h3>
                    <small>Cần xử lý ngay</small>
                </div>
            </div>
        </div>

    <!-- Tài chính: Thu / Chi / Lãi / Thuế -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-1">💵 Tổng thu</h6>
                    <h4 class="mb-0"><?= number_format($data['financials']['revenue'] ?? 0, 0, ',', '.') ?>₫</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-dark text-white" style="background: linear-gradient(135deg,#3a6073 0%,#16222a 100%);">
                <div class="card-body">
                    <h6 class="card-subtitle mb-1">💸 Tổng chi</h6>
                    <h4 class="mb-0"><?= number_format($data['financials']['cost'] ?? 0, 0, ',', '.') ?>₫</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-1">📈 Lãi</h6>
                    <h4 class="mb-0"><?= number_format($data['financials']['profit'] ?? 0, 0, ',', '.') ?>₫</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-1">🧾 Tổng thuế (1% lãi)</h6>
                    <h4 class="mb-0"><?= number_format($data['totalTax'] ?? 0, 0, ',', '.') ?>₫</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Trạng thái đơn hàng -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">📊 Trạng thái đơn hàng</h6>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Phương thức thanh toán -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">💳 Phương thức thanh toán</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <canvas id="paymentMethodChart" height="110"></canvas>
                    </div>
                    <div class="row g-2">
                        <?php foreach ($data['paymentStats'] as $stat): ?>
                            <div class="col-md-6">
                                <div class="card border-start border-4 border-primary shadow-sm">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($stat['payment_method'] ?? 'N/A') ?></h6>
                                                <small class="text-muted"><?= $stat['count'] ?> đơn</small>
                                            </div>
                                            <div class="text-end">
                                                <div class="fs-6 fw-bold text-success"><?= number_format($stat['total_amount'] ?? 0, 0, ',', '.') ?>₫</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê nâng cao -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-gradient-primary text-white">
                    <h6 class="mb-0">📦 Thống kê theo loại sản phẩm</h6>
                </div>
                <div class="card-body table-responsive" style="max-height: 320px;">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Mã loại</th>
                                <th>Tên loại</th>
                                <th class="text-end">Doanh thu</th>
                                <th class="text-end">SL bán</th>
                                <th class="text-end">Đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['statsByType'] as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['maloaisp']) ?></td>
                                    <td><?= htmlspecialchars($row['type_name']) ?></td>
                                    <td class="text-end text-success fw-bold"><?= number_format($row['revenue'] ?? 0, 0, ',', '.') ?>₫</td>
                                    <td class="text-end"><?= (int)($row['total_sold'] ?? 0) ?></td>
                                    <td class="text-end"><?= (int)($row['order_count'] ?? 0) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-gradient-info text-white">
                    <h6 class="mb-0">🛒 Thống kê theo sản phẩm</h6>
                </div>
                <div class="card-body table-responsive" style="max-height: 320px;">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Distributor</th>
                                <th class="text-end">Doanh thu</th>
                                <th class="text-end">SL bán</th>
                                <th class="text-end">Đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['statsByProduct'] as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['product_name'] ?? $row['product_id']) ?></td>
                                    <td><?= htmlspecialchars($row['distributor_email'] ?? '') ?></td>
                                    <td class="text-end text-success fw-bold"><?= number_format($row['revenue'] ?? 0, 0, ',', '.') ?>₫</td>
                                    <td class="text-end"><?= (int)($row['total_sold'] ?? 0) ?></td>
                                    <td class="text-end"><?= (int)($row['order_count'] ?? 0) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-gradient-success text-white">
                    <h6 class="mb-0">🏪 Thống kê theo Distributor</h6>
                </div>
                <div class="card-body table-responsive" style="max-height: 320px;">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Tên</th>
                                <th class="text-end">Doanh thu</th>
                                <th class="text-end">Lợi nhuận</th>
                                <th class="text-end">Đơn</th>
                                <th class="text-end">SL bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['statsByDistributor'] as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['fullname'] ?? '') ?></td>
                                    <td class="text-end text-success fw-bold"><?= number_format($row['revenue'] ?? 0, 0, ',', '.') ?>₫</td>
                                    <td class="text-end text-info fw-bold"><?= number_format(($row['profit'] ?? 0), 0, ',', '.') ?>₫</td>
                                    <td class="text-end"><?= (int)($row['order_count'] ?? 0) ?></td>
                                    <td class="text-end"><?= (int)($row['total_sold'] ?? 0) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-gradient-dark text-white" style="background: linear-gradient(135deg,#0f172a,#1e293b);">
                    <h6 class="mb-0">🧑‍💻 Thống kê theo người mua</h6>
                </div>
                <div class="card-body table-responsive" style="max-height: 320px;">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Tên</th>
                                <th class="text-end">Doanh thu</th>
                                <th class="text-end">Đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['statsByBuyer'] as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['user_email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['fullname'] ?? '') ?></td>
                                    <td class="text-end text-success fw-bold"><?= number_format($row['revenue'] ?? 0, 0, ',', '.') ?>₫</td>
                                    <td class="text-end"><?= (int)($row['order_count'] ?? 0) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Distributor -->
    <div class="mb-4">
        <h5 class="mb-3">🏆 Top 10 Distributor (Lãi cao nhất)</h5>
        <div class="row g-3">
            <?php $i = 1; foreach ($data['topDistributors'] as $dist): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-2">
                                <div class="badge bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.2rem; font-weight: 700;"><?= $i++ ?></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1 fw-bold"><?= htmlspecialchars($dist['fullname'] ?? 'N/A') ?></h6>
                                    <small class="text-muted d-block mb-2"><?= htmlspecialchars($dist['email']) ?></small>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Tổng tiền bán</small>
                                    <div class="fw-bold text-info"><?= number_format($dist['total_revenue'] ?? 0, 0, ',', '.') ?>₫</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Chi phí</small>
                                    <div class="fw-bold text-secondary"><?= number_format($dist['total_cost'] ?? 0, 0, ',', '.') ?>₫</div>
                                </div>
                                <div class="col-12 mt-2 pt-2 border-top">
                                    <small class="text-muted d-block">Lãi</small>
                                    <div class="fs-6 fw-bold text-success"><?= number_format($dist['profit'] ?? 0, 0, ',', '.') ?>₫</div>
                                </div>
                                <div class="col-12 mt-1">
                                    <small class="text-muted"><?= $dist['order_count'] ?> đơn hàng</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Thuế 1% theo distributor -->
    <div class="mb-4">
        <h5 class="mb-3">🧾 Thuế 1% / distributor</h5>
        <div class="row g-3">
            <?php $i = 1; foreach ($data['distributorTaxes'] as $dist): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-start border-4 border-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($dist['fullname'] ?? 'N/A') ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($dist['email'] ?? '') ?></small>
                                </div>
                                <span class="badge bg-secondary">#<?= $i++ ?></span>
                            </div>
                            <hr class="my-2">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Doanh thu</small>
                                    <div class="fw-bold text-success"><?= number_format($dist['revenue'] ?? 0, 0, ',', '.') ?>₫</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Lãi</small>
                                    <div class="fw-bold"><?= number_format($dist['profit'] ?? 0, 0, ',', '.') ?>₫</div>
                                </div>
                                <div class="col-12 mt-2">
                                    <small class="text-muted d-block">Thuế (1%)</small>
                                    <div class="fs-5 fw-bold text-danger"><?= number_format($dist['tax'] ?? 0, 0, ',', '.') ?>₫</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sản phẩm bán chạy nhất -->
    <div class="mb-4">
        <h5 class="mb-3">🔥 Top 10 Sản phẩm bán chạy nhất</h5>
        <div class="row g-3">
            <?php $i = 1; foreach ($data['bestSellingProducts'] as $prod): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-2">
                                <div class="badge bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: 700;"><?= $i++ ?></div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1 fw-bold"><?= htmlspecialchars($prod['product_name'] ?? 'N/A') ?></h6>
                                    <small class="text-muted d-block mb-2"><?= htmlspecialchars($prod['distributor_name'] ?? 'N/A') ?></small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <div>
                                    <small class="text-muted d-block">Đã bán</small>
                                    <div class="fw-bold text-primary"><?= $prod['total_sold'] ?> sp</div>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">Doanh thu</small>
                                    <div class="fw-bold text-success"><?= number_format($prod['total_revenue'] ?? 0, 0, ',', '.') ?>₫</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sản phẩm được yêu thích (>2 favorites) -->
    <div class="mb-4">
        <h5 class="mb-3">❤️ Sản phẩm được yêu thích nhiều (>2 lần)</h5>
        <div class="row g-3">
            <?php if (!empty($data['favoriteProducts'])): ?>
                <?php $i = 1; foreach ($data['favoriteProducts'] as $prod): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm hover-lift border-start border-4 border-pink">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="badge bg-pink rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: 700; background: linear-gradient(135deg, #ff69b4, #ff1493) !important;"><?= $i++ ?></div>
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-1 fw-bold"><?= htmlspecialchars($prod['product_name'] ?? 'N/A') ?></h6>
                                        <small class="text-muted d-block mb-2"><?= htmlspecialchars($prod['distributor_name'] ?? 'N/A') ?></small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <div>
                                        <small class="text-muted d-block">❤️ Yêu thích</small>
                                        <div class="fw-bold text-danger"><?= $prod['favorite_count'] ?> người</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">Chưa có sản phẩm được yêu thích từ 2 người trở lên</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sản phẩm được đánh giá cao (>4 sao) -->
    <div class="mb-4">
        <h5 class="mb-3">⭐ Sản phẩm được đánh giá cao (>4 sao)</h5>
        <div class="row g-3">
            <?php if (!empty($data['highlyRatedProducts'])): ?>
                <?php $i = 1; foreach ($data['highlyRatedProducts'] as $prod): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm hover-lift border-start border-4 border-warning">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="badge bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: 700; color: #000;"><?= $i++ ?></div>
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-1 fw-bold"><?= htmlspecialchars($prod['product_name'] ?? 'N/A') ?></h6>
                                        <small class="text-muted d-block mb-2"><?= htmlspecialchars($prod['distributor_name'] ?? 'N/A') ?></small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <div>
                                        <small class="text-muted d-block">⭐ Đánh giá</small>
                                        <div class="fw-bold text-success"><?= $prod['avg_rating'] ?>/5</div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">Bình luận</small>
                                        <div class="fw-bold"><?= $prod['review_count'] ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">Chưa có sản phẩm được đánh giá >4 sao</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Đơn hàng hết hàng (out of stock) -->
    <div class="mb-4">
        <h5 class="mb-3">🚨 Đơn hàng hết hàng</h5>
        <div class="row g-3">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-start border-4 border-danger">
                    <div class="card-body text-center">
                        <h6 class="card-subtitle mb-2 text-muted">Số đơn hàng chứa sản phẩm hết stock</h6>
                        <h2 class="text-danger fw-bold"><?= $data['outOfStockOrders'] ?> đơn</h2>
                        <p class="text-muted small mb-0">Cần xử lý hoặc liên hệ khách hàng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Doanh thu theo loại sản phẩm -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0">📈 Doanh thu theo loại sản phẩm</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <canvas id="revenueByTypeChart" height="180"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="soldByTypeChart" height="180"></canvas>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <?php foreach ($data['revenueByType'] as $type): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-start border-4 border-info">
                            <div class="card-body">
                                <h6 class="mb-3 fw-bold"><?= htmlspecialchars($type['type_name'] ?? 'N/A') ?></h6>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <small class="text-muted d-block">Doanh thu</small>
                                        <div class="fs-5 fw-bold text-success"><?= number_format($type['total_revenue'] ?? 0, 0, ',', '.') ?>₫</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Đã bán</small>
                                        <div class="fw-bold"><?= $type['total_sold'] ?> sp</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Số đơn</small>
                                        <div class="fw-bold"><?= $type['order_count'] ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Chart doanh thu hàng ngày -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0">📊 Doanh thu 30 ngày qua</h6>
        </div>
        <div class="card-body">
            <canvas id="dailyRevenueChart"></canvas>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Toggle visible filter control based on selected type
function toggleFilterInputs() {
    const type = document.getElementById('filterType')?.value;
    const day = document.getElementById('filterDayWrapper');
    const month = document.getElementById('filterMonthWrapper');
    const year = document.getElementById('filterYearWrapper');
    if (!day || !month || !year) return;
    day.style.display = type === 'day' ? 'block' : 'none';
    month.style.display = type === 'month' ? 'block' : 'none';
    year.style.display = type === 'year' ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleFilterInputs);

// Biểu đồ trạng thái đơn hàng
const orderStatusCtx = document.getElementById('orderStatusChart');
if (orderStatusCtx) {
    new Chart(orderStatusCtx, {
        type: 'bar',
        data: {
            labels: ['Hoàn thành', 'Đã giao DVVC', 'Chờ xác nhận', 'Đã trả hàng'],
            datasets: [{
                label: 'Số đơn hàng',
                data: [
                    <?= $data['orderStats']['completed_orders'] ?? 0 ?>,
                    <?= $data['orderStats']['processing_orders'] ?? 0 ?>,
                    <?= $data['orderStats']['pending_orders'] ?? 0 ?>,
                    <?= $data['orderStats']['returned_orders'] ?? 0 ?>
                ],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#6c757d'],
                borderWidth: 0,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Biểu đồ doanh thu & đơn hàng 30 ngày qua (dual axes)
const dailyRevenueCtx = document.getElementById('dailyRevenueChart');
if (dailyRevenueCtx) {
    const dailyData = <?= json_encode($data['dailyRevenue']) ?>;
    const labels = dailyData.map(d => d.date);
    const revenues = dailyData.map(d => Number(d.revenue || 0));
    const orders = dailyData.map(d => Number(d.order_count || 0));
    
    new Chart(dailyRevenueCtx, {
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Số đơn',
                    data: orders,
                    backgroundColor: 'rgba(34,197,94,0.35)',
                    borderColor: '#22c55e',
                    borderWidth: 1,
                    yAxisID: 'yOrders'
                },
                {
                    type: 'line',
                    label: 'Doanh thu (₫)',
                    data: revenues,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.15)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    yAxisID: 'yRevenue'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: true }
            },
            scales: {
                yRevenue: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => value.toLocaleString() + '₫'
                    }
                },
                yOrders: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: {
                        callback: (value) => value.toLocaleString()
                    }
                }
            }
        }
    });
}

// Biểu đồ phương thức thanh toán
const paymentMethodCtx = document.getElementById('paymentMethodChart');
if (paymentMethodCtx) {
    const paymentData = <?= json_encode($data['paymentStats']) ?>;
    const labels = paymentData.map(p => p.payment_method || 'N/A');
    const counts = paymentData.map(p => Number(p.count || 0));
    const amounts = paymentData.map(p => Number(p.total_amount || 0));
    new Chart(paymentMethodCtx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Số đơn',
                    data: counts,
                    backgroundColor: '#3b82f6',
                    borderRadius: 6,
                    yAxisID: 'yCount'
                },
                {
                    label: 'Doanh thu (₫)',
                    data: amounts,
                    backgroundColor: '#22c55e',
                    borderRadius: 6,
                    yAxisID: 'yAmount'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                yCount: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: 'Số đơn' }
                },
                yAmount: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: {
                        callback: (value) => value.toLocaleString() + '₫'
                    },
                    title: { display: true, text: 'Doanh thu' }
                }
            }
        }
    });
}

// Biểu đồ doanh thu & số lượng theo loại sản phẩm
const revenueByTypeCtx = document.getElementById('revenueByTypeChart');
const soldByTypeCtx = document.getElementById('soldByTypeChart');
if (revenueByTypeCtx || soldByTypeCtx) {
    const typeData = <?= json_encode($data['revenueByType']) ?>;
    const labels = typeData.map(t => t.type_name || 'N/A');
    const revenues = typeData.map(t => Number(t.total_revenue || 0));
    const sold = typeData.map(t => Number(t.total_sold || 0));
    if (revenueByTypeCtx) {
        new Chart(revenueByTypeCtx, {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Doanh thu (₫)', data: revenues, backgroundColor: '#6366f1' }] },
            options: {
                indexAxis: 'y',
                scales: { x: { ticks: { callback: (v) => v.toLocaleString() + '₫' } } },
                plugins: { legend: { display: false } }
            }
        });
    }
    if (soldByTypeCtx) {
        new Chart(soldByTypeCtx, {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Số lượng bán', data: sold, backgroundColor: '#22c55e' }] },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } }
            }
        });
    }
}
</script>
