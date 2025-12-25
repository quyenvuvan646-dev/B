<div class="container-fluid py-4">
    <h2 class="mb-4 text-primary">📊 Thống kê kinh doanh</h2>

    <!-- Bộ lọc -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Loại thống kê</label>
                    <select name="filter" class="form-select" id="filterTypeSelect">
                        <option value="all" <?= ($data['filterType'] ?? 'all') === 'all' ? 'selected' : '' ?>>Tất cả thời gian</option>
                        <option value="day" <?= ($data['filterType'] ?? '') === 'day' ? 'selected' : '' ?>>Theo ngày</option>
                        <option value="month" <?= ($data['filterType'] ?? '') === 'month' ? 'selected' : '' ?>>Theo tháng</option>
                        <option value="year" <?= ($data['filterType'] ?? '') === 'year' ? 'selected' : '' ?>>Theo năm</option>
                    </select>
                </div>
                <?php if (($data['filterType'] ?? 'all') !== 'all'): ?>
                    <div class="col-md-3">
                        <label class="form-label">
                            <?php 
                                if (($data['filterType'] ?? '') === 'day') echo 'Chọn ngày';
                                elseif (($data['filterType'] ?? '') === 'month') echo 'Chọn tháng';
                                else echo 'Chọn năm';
                            ?>
                        </label>
                        <input type="<?= ($data['filterType'] ?? '') === 'year' ? 'number' : 'date' ?>" 
                               name="filterValue" class="form-control" id="filterValueInput"
                               value="<?= htmlspecialchars($data['filterValue'] ?? '') ?>"
                               <?= ($data['filterType'] ?? '') === 'year' ? 'min="2020" max="' . date('Y') . '"' : '' ?>
                        >
                    </div>
                <?php endif; ?>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Thẻ 1: Đánh giá sao trung bình -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-star-fill text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title text-muted mb-2">Đánh giá sao TB</h6>
                    <h3 class="mb-0">
                        <?php 
                            $rating = $data['stats']['avg_rating'] ?? 0;
                            echo number_format($rating, 2);
                        ?>
                        <small style="font-size: 0.6em;"> / 5</small>
                    </h3>
                </div>
            </div>
        </div>

        <!-- Thẻ 2: Số sản phẩm đã bán -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-bag-check-fill text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title text-muted mb-2">Sản phẩm đã bán</h6>
                    <h3 class="mb-0">
                        <?php echo number_format($data['stats']['sold_quantity'] ?? 0, 0, ',', '.'); ?>
                    </h3>
                    <small class="text-muted">
                        (<?php echo number_format($data['stats']['sold_count'] ?? 0, 0, ',', '.'); ?> đơn)
                    </small>
                </div>
            </div>
        </div>

        <!-- Thẻ 3: Số sản phẩm bị trả -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-arrow-return-left text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title text-muted mb-2">Sản phẩm trả lại</h6>
                    <h3 class="mb-0">
                        <?php echo number_format($data['stats']['returned_quantity'] ?? 0, 0, ',', '.'); ?>
                    </h3>
                    <small class="text-muted">
                        (<?php echo number_format($data['stats']['returned_count'] ?? 0, 0, ',', '.'); ?> đơn)
                    </small>
                </div>
            </div>
        </div>

        <!-- Thẻ 4: Tổng tiền bán -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-coin text-info" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title text-muted mb-2">Tổng tiền bán</h6>
                    <h3 class="mb-0">
                        <?php echo number_format($data['stats']['total_revenue'] ?? 0, 0, ',', '.'); ?>
                    </h3>
                    <small class="text-muted">₫</small>
                </div>
            </div>
        </div>

        <!-- Thẻ 5: Thuế -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-calculator text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title text-muted mb-2">Thuế (1%)</h6>
                    <h3 class="mb-0" style="color: #dc3545;">
                        <?php echo number_format($data['stats']['tax'] ?? 0, 0, ',', '.'); ?>
                    </h3>
                    <small class="text-muted">₫</small>
                </div>
            </div>
        </div>

        <!-- Thẻ 6: Lãi ròng -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-cash-coin text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="card-title text-muted mb-2">Lãi ròng</h6>
                    <h3 class="mb-0" style="color: <?php echo ($data['stats']['profit'] ?? 0) >= 0 ? '#28a745' : '#dc3545'; ?>">
                        <?php 
                            $profit = $data['stats']['profit'] ?? 0;
                            echo ($profit >= 0 ? '+' : '') . number_format($profit, 0, ',', '.');
                        ?>
                    </h3>
                    <small class="text-muted">₫</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ -->
    <?php if (!empty($data['chartData'])): ?>
    <div class="card shadow-sm mt-4 border-0">
        <div class="card-header bg-dark text-white">
            <strong>📈 Biểu đồ doanh thu</strong>
        </div>
        <div class="card-body">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bảng chi tiết -->
    <div class="card shadow-sm mt-4 border-0">
        <div class="card-header bg-dark text-white">
            <strong>📋 Chi tiết thống kê</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <h6 class="text-muted mb-3">💰 Bán hàng</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Số lượng bán:</span>
                            <strong><?php echo number_format($data['stats']['sold_quantity'] ?? 0, 0, ',', '.'); ?> sản phẩm</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Số đơn bán:</span>
                            <strong><?php echo number_format($data['stats']['sold_count'] ?? 0, 0, ',', '.'); ?> đơn</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tổng doanh thu:</span>
                            <strong class="text-success"><?php echo number_format($data['stats']['total_revenue'] ?? 0, 0, ',', '.'); ?> ₫</strong>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <h6 class="text-muted mb-3">📊 Chi phí</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng giá nhập:</span>
                            <strong class="text-danger"><?php echo number_format($data['stats']['total_cost'] ?? 0, 0, ',', '.'); ?> ₫</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Thuế (1%):</span>
                            <strong class="text-warning"><?php echo number_format($data['stats']['tax'] ?? 0, 0, ',', '.'); ?> ₫</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Lãi ròng:</span>
                            <strong style="color: <?php echo ($data['stats']['profit'] ?? 0) >= 0 ? '#28a745' : '#dc3545'; ?>">
                                <?php 
                                    $profit = $data['stats']['profit'] ?? 0;
                                    echo ($profit >= 0 ? '+' : '') . number_format($profit, 0, ',', '.'); 
                                ?> ₫
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <h6 class="text-muted mb-3">🔄 Trả hàng</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Số lượng trả:</span>
                            <strong><?php echo number_format($data['stats']['returned_quantity'] ?? 0, 0, ',', '.'); ?> sản phẩm</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Số đơn trả:</span>
                            <strong><?php echo number_format($data['stats']['returned_count'] ?? 0, 0, ',', '.'); ?> đơn</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tỷ lệ trả:</span>
                            <strong class="text-warning">
                                <?php 
                                    $totalSold = ($data['stats']['sold_quantity'] ?? 0) + ($data['stats']['returned_quantity'] ?? 0);
                                    if ($totalSold > 0) {
                                        $returnRate = (($data['stats']['returned_quantity'] ?? 0) / $totalSold) * 100;
                                        echo number_format($returnRate, 2);
                                    } else {
                                        echo "0";
                                    }
                                ?>%
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <h6 class="text-muted mb-3">⭐ Đánh giá</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Đánh giá sao trung bình:</span>
                            <div>
                                <?php 
                                    $rating = $data['stats']['avg_rating'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<i class="bi bi-star-fill text-warning"></i>';
                                        } else {
                                            echo '<i class="bi bi-star text-muted"></i>';
                                        }
                                    }
                                    echo ' ' . number_format($rating, 2) . ' / 5.00';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($data['chartData'])): ?>
    const chartData = <?php echo json_encode($data['chartData']); ?>;
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    let labels = [];
    let revenues = [];
    let orderCounts = [];
    
    chartData.forEach(item => {
        <?php if (($data['filterType'] ?? '') === 'year'): ?>
            // Tháng
            const monthNames = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
            labels.push(monthNames[item.month - 1]);
        <?php else: ?>
            // Ngày
            labels.push(item.date.split('-')[2]);
        <?php endif; ?>
        revenues.push(item.revenue);
        orderCounts.push(item.order_count);
    });
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Doanh thu (₫)',
                    data: revenues,
                    backgroundColor: 'rgba(40, 167, 69, 0.5)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2,
                    yAxisID: 'y'
                },
                {
                    label: 'Số đơn hàng',
                    data: orderCounts,
                    type: 'line',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 2,
                    yAxisID: 'y1',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Doanh thu (₫)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Số đơn hàng'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
<?php endif; ?>
</script>
