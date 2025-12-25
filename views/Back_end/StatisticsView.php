<?php
// Make sure $summary exists to avoid undefined variable warnings when view
// is rendered without controller-provided data (use safe defaults).
if (!isset($summary) || !is_array($summary)) {
    $summary = [
        'total_revenue' => 0,
        'total_orders' => 0,
        'today_revenue' => 0,
        'today_orders' => 0,
        'month_revenue' => 0,
        'month_orders' => 0,
        'total_products' => 0,
        'low_stock_products' => 0
    ];
}

// --- Normalize / sanitize totals to avoid double-formatted strings ---
// Prefer rawTotals if available (authoritative DB aggregation).
$totals = [
    'total_revenue'     => 0,
    'total_orders'      => 0,
    'today_revenue'     => 0,
    'today_orders'      => 0,
    'month_revenue'     => 0,
    'month_orders'      => 0,
    'total_products'    => 0,
    'low_stock_products' => 0
];

// Helper: safe money formatter (round to integer dong)
function fmt_money($v) {
    if (!is_numeric($v)) return number_format(0, 0, ',', '.');
    return number_format(round($v), 0, ',', '.');
}

// If rawTotals from DB exists and looks valid, use that for total revenue / orders
if (isset($rawTotals) && is_array($rawTotals) && count($rawTotals) > 0) {
    $raw = $rawTotals[0];
    if (isset($raw['sum_total']) && is_numeric($raw['sum_total'])) {
        $totals['total_revenue'] = (float)$raw['sum_total'];
    }
    if (isset($raw['count_orders']) && is_numeric($raw['count_orders'])) {
        $totals['total_orders'] = (int)$raw['count_orders'];
    }
}

// Fallback to $summary values (cast safely)
$totals['total_revenue']     = $totals['total_revenue'] ?: (is_numeric($summary['total_revenue'] ?? 0) ? (float)$summary['total_revenue'] : 0);
$totals['total_orders']      = $totals['total_orders'] ?: (is_numeric($summary['total_orders'] ?? 0) ? (int)$summary['total_orders'] : 0);
$totals['today_revenue']     = is_numeric($summary['today_revenue'] ?? 0) ? (float)$summary['today_revenue'] : 0;
$totals['today_orders']      = is_numeric($summary['today_orders'] ?? 0) ? (int)$summary['today_orders'] : 0;
$totals['month_revenue']     = is_numeric($summary['month_revenue'] ?? 0) ? (float)$summary['month_revenue'] : 0;
$totals['month_orders']      = is_numeric($summary['month_orders'] ?? 0) ? (int)$summary['month_orders'] : 0;
$totals['total_products']    = is_numeric($summary['total_products'] ?? 0) ? (int)$summary['total_products'] : 0;
$totals['low_stock_products'] = is_numeric($summary['low_stock_products'] ?? 0) ? (int)$summary['low_stock_products'] : 0;
?>
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-4">📊 Bảng điều khiển thống kê</h1>
        </div>
    </div>

    <!-- 📊 Tóm tắt chung -->
    <?php if (isset($rawTotals) && is_array($rawTotals) && count($rawTotals) > 0): ?>
        <div class="alert alert-info">Raw DB totals: <strong><?php echo fmt_money($rawTotals[0]['sum_total'] ?? 0); ?> đ</strong> — Orders: <strong><?php echo (int)($rawTotals[0]['count_orders'] ?? 0); ?></strong></div>
    <?php endif; ?>

    <?php if (isset($sampleOrders) && is_array($sampleOrders) && count($sampleOrders) > 0): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Debug: Recent orders (for inspecting total_amount)</h5>
                <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr><th>ID</th><th>Order Code</th><th>total_amount</th><th>user_email</th><th>created_at</th><th>transaction_info</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($sampleOrders as $o): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($o['id'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($o['order_code'] ?? ''); ?></td>
                                <td>
                                    <?php
                                        // show raw numeric formatted for easier comparison
                                        if (isset($o['total_amount']) && is_numeric($o['total_amount'])) {
                                            echo fmt_money($o['total_amount']) . ' đ';
                                        } else {
                                            echo htmlspecialchars($o['total_amount'] ?? '');
                                        }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($o['user_email'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($o['created_at'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($o['transaction_info'] ?? ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">💰 Tổng doanh thu</h5>
                    <p class="card-text display-6"><?php echo fmt_money($totals['total_revenue']); ?> đ</p>
                    <small><?php echo number_format($totals['total_orders'] ?? 0, 0, ',', '.'); ?> đơn hàng</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">📈 Doanh thu hôm nay</h5>
                    <p class="card-text display-6"><?php echo fmt_money($totals['today_revenue']); ?> đ</p>
                    <small><?php echo number_format($totals['today_orders'] ?? 0, 0, ',', '.'); ?> đơn hàng</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">📅 Doanh thu tháng này</h5>
                    <p class="card-text display-6"><?php echo fmt_money($totals['month_revenue']); ?> đ</p>
                    <small><?php echo number_format($totals['month_orders'] ?? 0, 0, ',', '.'); ?> đơn hàng</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">⚠️ Sắp hết hàng</h5>
                    <p class="card-text display-6"><?php echo number_format($totals['low_stock_products'], 0, ',', '.'); ?></p>
                    <small>trên <?php echo number_format($totals['total_products'], 0, ',', '.'); ?> sản phẩm</small>
                </div>
            </div>
        </div>
    </div>

    <!-- rest of template unchanged -->
    <!-- 📑 Các tab thống kê -->
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="statsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab">Dashboard</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="revenue-tab" data-bs-toggle="tab" data-bs-target="#revenue" type="button" role="tab">💰 Doanh thu</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">📦 Sản phẩm & Danh mục</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab">📦 Tồn kho</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="growth-tab" data-bs-toggle="tab" data-bs-target="#growth" type="button" role="tab">📈 Tăng trưởng</button>
                </li>
            </ul>

            <div class="tab-content" id="statsTabContent">
                <!-- Dashboard Tab -->
                <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Biểu đồ doanh thu</h5>
                            <canvas id="revenueChart" height="80"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Revenue Tab -->
                <div class="tab-pane fade" id="revenue" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Doanh thu theo thời gian</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="revenueType">Loại thống kê:</label>
                                    <select class="form-select" id="revenueType" onchange="updateRevenueChart()">
                                        <option value="day">Theo ngày (tháng hiện tại)</option>
                                        <option value="month" selected>Theo tháng (năm hiện tại)</option>
                                        <option value="year">Theo năm (5 năm gần nhất)</option>
                                    </select>
                                </div>
                                <div class="col-md-3" id="yearFilter" style="display:none;">
                                    <label for="revenueYear">Năm:</label>
                                    <input type="number" class="form-control" id="revenueYear" value="<?php echo date('Y'); ?>" min="2020" max="2099" onchange="updateRevenueChart()">
                                </div>
                            </div>
                            <canvas id="revenueTrendChart" height="60"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade" id="products" role="tabpanel">
                    <div class="row mt-3">
                        <!-- Doanh thu từng sản phẩm -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">🏆 Top 10 Sản phẩm bán chạy</h5>
                                    <canvas id="productChart" height="80"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Doanh thu từng danh mục -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">📂 Doanh thu theo danh mục</h5>
                                    <canvas id="categoryChart" height="80"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng chi tiết sản phẩm -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">📋 Chi tiết doanh thu sản phẩm</h5>
                            <table class="table table-striped table-hover" id="productRevenueTable">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng bán</th>
                                        <th>Doanh thu</th>
                                        <th>Số đơn hàng</th>
                                    </tr>
                                </thead>
                                <tbody id="productRevenueBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Inventory Tab -->
                <div class="tab-pane fade" id="inventory" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">📦 Tồn kho sản phẩm</h5>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="inventorySearch" placeholder="Tìm kiếm sản phẩm...">
                                </div>
                            </div>
                            <table class="table table-striped table-hover" id="inventoryTable">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Mã sản phẩm</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng tồn</th>
                                        <th>Giá nhập</th>
                                        <th>Giá bán</th>
                                        <th>Giá trị tồn kho</th>
                                    </tr>
                                </thead>
                                <tbody id="inventoryBody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sản phẩm sắp hết hàng -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">⚠️ Sản phẩm sắp hết hàng (Số lượng < 5)</h5>
                            <table class="table table-danger table-striped" id="lowStockTable">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng tồn</th>
                                        <th>Giá bán</th>
                                    </tr>
                                </thead>
                                <tbody id="lowStockBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Growth Tab -->
                <div class="tab-pane fade" id="growth" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">📈 Tăng trưởng doanh thu</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="growthYear">Năm:</label>
                                    <input type="number" class="form-control" id="growthYear" value="<?php echo date('Y'); ?>" min="2020" max="2099" onchange="updateGrowth()">
                                </div>
                                <div class="col-md-3">
                                    <label for="growthMonth">Tháng:</label>
                                    <select class="form-select" id="growthMonth" onchange="updateGrowth()">
                                        <?php for($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php echo $i == date('n') ? 'selected' : ''; ?>>Tháng <?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div id="growthContent">
                                <div class="alert alert-info">Chọn năm và tháng để xem tăng trưởng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>

<!-- The rest of your existing JS/CSS remains unchanged -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<!-- ... (keep your original script and style blocks here) ... -->

<script>
let charts = {};

// Khởi tạo biểu đồ doanh thu
function initRevenueChart() {
    fetch('<?php echo APP_URL; ?>/Admin/chartData/month/<?php echo date('Y'); ?>')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(d => 'Tháng ' + d.month);
            const revenues = data.map(d => d.revenue);
            
            const ctx = document.getElementById('revenueChart').getContext('2d');
            if (charts.revenue) charts.revenue.destroy();
            charts.revenue = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu (đ)',
                        data: revenues,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' đ';
                                }
                            }
                        }
                    }
                }
            });
        });
}

// Cập nhật biểu đồ doanh thu theo loại
function updateRevenueChart() {
    const type = document.getElementById('revenueType').value;
    const year = document.getElementById('revenueYear').value || new Date().getFullYear();
    
    // Hiển thị/ẩn filter năm
    document.getElementById('yearFilter').style.display = type === 'month' ? 'block' : 'none';
    
    fetch(`<?php echo APP_URL; ?>/Admin/chartData/${type}/${year}`)
        .then(response => response.json())
        .then(data => {
            let labels = [];
            if (type === 'day') {
                labels = data.map(d => d.date.split('-')[2] + '/' + d.date.split('-')[1]);
            } else if (type === 'month') {
                labels = data.map(d => 'Tháng ' + d.month);
            } else if (type === 'year') {
                labels = data.map(d => 'Năm ' + d.year);
            }
            
            const revenues = data.map(d => d.revenue);
            
            const ctx = document.getElementById('revenueTrendChart').getContext('2d');
            if (charts.revenueTrend) charts.revenueTrend.destroy();
            charts.revenueTrend = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu (đ)',
                        data: revenues,
                        backgroundColor: '#28a745',
                        borderColor: '#1e7e34',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' đ';
                                }
                            }
                        }
                    }
                }
            });
        });
}

// Khởi tạo biểu đồ sản phẩm
function initProductCharts() {
    // Top 10 sản phẩm
    fetch('<?php echo APP_URL; ?>/Admin/revenueByProduct')
        .then(response => response.json())
        .then(data => {
            const topProducts = data.slice(0, 10);
            const labels = topProducts.map(p => p.tensp);
            const revenues = topProducts.map(p => p.product_revenue);
            
            const ctx = document.getElementById('productChart').getContext('2d');
            if (charts.product) charts.product.destroy();
            charts.product = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu (đ)',
                        data: revenues,
                        backgroundColor: '#ffc107',
                        borderColor: '#e0a800',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' đ';
                                }
                            }
                        }
                    }
                }
            });
            
            // Populate product revenue table
            const tbody = document.getElementById('productRevenueBody');
            tbody.innerHTML = '';
            data.forEach(p => {
                tbody.innerHTML += `
                    <tr>
                        <td><img src="${'<?php echo APP_URL; ?>/public/images/' + p.hinhanh}" alt="${p.tensp}" width="50" height="50"></td>
                        <td>${p.tensp}</td>
                        <td>${p.total_quantity}</td>
                        <td>${p.product_revenue.toLocaleString('vi-VN')} đ</td>
                        <td>${p.order_count}</td>
                    </tr>
                `;
            });
        });

    // Doanh thu theo danh mục
    fetch('<?php echo APP_URL; ?>/Admin/revenueByCategory')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(c => c.tenLoaiSP);
            const revenues = data.map(c => c.category_revenue);
            
            const ctx = document.getElementById('categoryChart').getContext('2d');
            if (charts.category) charts.category.destroy();
            charts.category = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: revenues,
                        backgroundColor: [
                            '#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff',
                            '#ff9f40', '#ff6384', '#c9cbcf', '#4bc0c0', '#ff9f40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
}

// Khởi tạo bảng tồn kho
function initInventory() {
    fetch('<?php echo APP_URL; ?>/Admin/inventory')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('inventoryBody');
            tbody.innerHTML = '';
            data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td><img src="${'<?php echo APP_URL; ?>/public/images/' + item.hinhanh}" alt="${item.tensp}" width="50" height="50"></td>
                        <td>${item.masp}</td>
                        <td>${item.tensp}</td>
                        <td><strong>${item.soluong}</strong></td>
                        <td>${item.giaNhap.toLocaleString('vi-VN')} đ</td>
                        <td>${item.giaXuat.toLocaleString('vi-VN')} đ</td>
                        <td><strong>${item.inventory_value.toLocaleString('vi-VN')} đ</strong></td>
                    </tr>
                `;
            });
        });

    // Sản phẩm sắp hết hàng
    fetch('<?php echo APP_URL; ?>/Admin/lowStock')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('lowStockBody');
            tbody.innerHTML = '';
            data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td><img src="${'<?php echo APP_URL; ?>/public/images/' + item.hinhanh}" alt="${item.tensp}" width="50" height="50"></td>
                        <td>${item.tensp}</td>
                        <td><span class="badge bg-danger">${item.soluong}</span></td>
                        <td>${item.giaXuat.toLocaleString('vi-VN')} đ</td>
                    </tr>
                `;
            });
        });
}

// Cập nhật tăng trưởng
function updateGrowth() {
    const year = document.getElementById('growthYear').value;
    const month = document.getElementById('growthMonth').value;
    
    fetch(`<?php echo APP_URL; ?>/Admin/revenueGrowth/${year}/${month}`)
        .then(response => response.json())
        .then(data => {
            const growthPercent = data.growth_percent;
            const growthClass = growthPercent >= 0 ? 'success' : 'danger';
            const growthIcon = growthPercent >= 0 ? '📈' : '📉';
            
            document.getElementById('growthContent').innerHTML = `
                <div class="alert alert-${growthClass}">
                    <h5>${growthIcon} Tăng trưởng: <strong>${growthPercent > 0 ? '+' : ''}${growthPercent}%</strong></h5>
                    <p>
                        <strong>Tháng hiện tại (${data.current_month}):</strong> ${data.current_revenue.toLocaleString('vi-VN')} đ<br>
                        <strong>Tháng trước (${data.prev_month}):</strong> ${data.prev_revenue.toLocaleString('vi-VN')} đ<br>
                        <strong>Thay đổi:</strong> ${(data.current_revenue - data.prev_revenue).toLocaleString('vi-VN')} đ
                    </p>
                </div>
            `;
        });
}

// Khởi tạo khi tải trang
document.addEventListener('DOMContentLoaded', function() {
    initRevenueChart();
    initProductCharts();
    initInventory();
    updateGrowth();
    updateRevenueChart();
});

// Tìm kiếm tồn kho
document.getElementById('inventorySearch').addEventListener('keyup', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.getElementById('inventoryBody').getElementsByTagName('tr');
    
    Array.from(rows).forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-body {
    padding: 1.5rem;
}

.nav-tabs .nav-link.active {
    background-color: #f8f9fa;
    border-bottom: 3px solid #007bff;
    color: #007bff;
    font-weight: bold;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #007bff;
    color: #007bff;
}

table {
    font-size: 0.9rem;
}

.bg-primary { background-color: #007bff !important; }
.bg-success { background-color: #28a745 !important; }
.bg-info { background-color: #17a2b8 !important; }
.bg-warning { background-color: #ffc107 !important; }
</style>
