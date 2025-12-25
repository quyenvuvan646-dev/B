<?php
// DistributorShippingRulesView.php - Distributor quản lý giá vận chuyển tùy chỉnh

// Extract data passed from controller
$rules = $data['rules'] ?? [];
$error = $data['error'] ?? '';
$success = $data['success'] ?? '';
$distributorEmail = $_SESSION['user']['email'] ?? '';

// Debug what we received
file_put_contents(__DIR__ . '/../../debug_received_data.log', 
    "Received in view:\n" . print_r($data, true) . "\n" . 
    "Rules count: " . count($rules) . "\n",
    FILE_APPEND
);
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2>⚙️ Cài đặt phí vận chuyển</h2>
            <p class="text-muted">Quản lý giá vận chuyển tùy chỉnh cho các khoảng cách khác nhau</p>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Add New Rule Form -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">➕ Thêm khoảng cách vận chuyển mới</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo APP_URL; ?>/?url=Distributor/addShippingRule" method="POST" onsubmit="console.log('Form submitting to:', this.action); return true;">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Từ (km) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="distance_from" min="0" step="1" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Đến (km) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="distance_to" min="1" step="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Phí thường (₫) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="standard_fee" min="0" step="100" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Phí nhanh (₫) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="fast_fee" min="0" step="100" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Phí hỏa tốc (₫) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="express_fee" min="0" step="100" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Thêm khoảng cách</button>
                    </form>
                </div>
            </div>

            <!-- Rules List -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">📋 Danh sách khoảng cách vận chuyển</h5>
                </div>
                <div class="card-body">
                    <!-- Debug -->
                    <div class="alert alert-warning">
                        <strong>Debug Info:</strong><br>
                        - Session Email: <?php echo htmlspecialchars($distributorEmail); ?><br>
                        - Rules array count: <?php echo count($rules); ?><br>
                        - Rules variable type: <?php echo gettype($rules); ?><br>
                        - Is array: <?php echo is_array($rules) ? 'Yes' : 'No'; ?>
                        <?php if (!empty($rules)): ?>
                            <br>- First rule: <?php echo htmlspecialchars(json_encode($rules[0])); ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (empty($rules)): ?>
                        <div class="alert alert-info">
                            Chưa có khoảng cách vận chuyển nào. Thêm khoảng cách mới để bắt đầu.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Khoảng cách</th>
                                        <th>Vận chuyển thường</th>
                                        <th>Vận chuyển nhanh</th>
                                        <th>Hỏa tốc</th>
                                        <th>Ngày tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rules as $rule): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($rule['distance_from']); ?> - <?php echo htmlspecialchars($rule['distance_to']); ?> km</strong>
                                            </td>
                                            <td><?php echo number_format($rule['standard_fee']); ?>₫</td>
                                            <td><?php echo number_format($rule['fast_fee']); ?>₫</td>
                                            <td><?php echo number_format($rule['express_fee']); ?>₫</td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($rule['created_at'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" onclick="editRule(<?php echo $rule['id']; ?>)">
                                                    ✏️ Sửa
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteRule(<?php echo $rule['id']; ?>)">
                                                    🗑️ Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Global Rates Info -->
            <div class="alert alert-info mt-4">
                <h6>ℹ️ Lưu ý về giá vận chuyển</h6>
                <ul class="mb-0">
                    <li>Nếu bạn không cài đặt khoảng cách nào, hệ thống sẽ dùng giá mặc định toàn cầu</li>
                    <li>Giá mặc định: Vận chuyển thường 1,000₫/km | Nhanh 2,000₫/km | Hỏa tốc 10,000₫/km</li>
                    <li>Giá của bạn sẽ được ưu tiên nếu khoảng cách của đơn hàng phù hợp</li>
                    <li>Các khoảng cách không được trùng lặp</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa khoảng cách vận chuyển</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                <input type="hidden" name="rule_id" id="editRuleId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Từ (km)</label>
                        <input type="number" class="form-control" id="editDistanceFrom" name="distance_from" min="0" step="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Đến (km)</label>
                        <input type="number" class="form-control" id="editDistanceTo" name="distance_to" min="1" step="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phí thường (₫)</label>
                        <input type="number" class="form-control" id="editStandardFee" name="standard_fee" min="0" step="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phí nhanh (₫)</label>
                        <input type="number" class="form-control" id="editFastFee" name="fast_fee" min="0" step="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phí hỏa tốc (₫)</label>
                        <input type="number" class="form-control" id="editExpressFee" name="express_fee" min="0" step="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editRule(ruleId) {
        // Find rule data from table
        const row = event.target.closest('tr');
        const cells = row.querySelectorAll('td');
        const distance = cells[0].textContent.match(/(\d+)\s*-\s*(\d+)/);
        const standardFee = cells[1].textContent.replace(/[^0-9]/g, '');
        const fastFee = cells[2].textContent.replace(/[^0-9]/g, '');
        const expressFee = cells[3].textContent.replace(/[^0-9]/g, '');

        document.getElementById('editRuleId').value = ruleId;
        document.getElementById('editDistanceFrom').value = distance[1];
        document.getElementById('editDistanceTo').value = distance[2];
        document.getElementById('editStandardFee').value = standardFee;
        document.getElementById('editFastFee').value = fastFee;
        document.getElementById('editExpressFee').value = expressFee;

        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }

    function deleteRule(ruleId) {
        if (confirm('Bạn chắc chắn muốn xóa khoảng cách này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo APP_URL; ?>/Distributor/deleteShippingRule';
            form.innerHTML = '<input type="hidden" name="rule_id" value="' + ruleId + '">';
            document.body.appendChild(form);
            form.submit();
        }
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        this.action = '<?php echo APP_URL; ?>/Distributor/updateShippingRule';
        this.submit();
    });
</script>
