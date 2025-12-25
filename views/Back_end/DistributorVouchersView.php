<div class="container mt-5">
    <h2>Quản Lý Voucher Của Shop</h2>

    <?php if (isset($_SESSION['voucher_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['voucher_success']; unset($_SESSION['voucher_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['voucher_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['voucher_error']; unset($_SESSION['voucher_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Thêm Voucher -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tạo Voucher Mới</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo APP_URL; ?>/?url=Distributor/addVoucher">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã Voucher</label>
                        <input type="text" class="form-control" name="code" required placeholder="VD: SUMMER20">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá Khuyến Mãi (₫)</label>
                        <input type="number" class="form-control" name="discount_value" required min="1000" placeholder="Số tiền giảm">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Đơn Tối Thiểu (₫)</label>
                        <input type="number" class="form-control" name="min_amount" required min="0" placeholder="VD: 100000">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số Lượng Voucher</label>
                        <input type="number" class="form-control" name="quantity" required min="1" value="100" placeholder="Số lần có thể dùng">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày Bắt Đầu</label>
                        <input type="datetime-local" class="form-control" name="time_start" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày Kết Thúc</label>
                        <input type="datetime-local" class="form-control" name="time_end" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select class="form-select" name="status">
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">+ Tạo Voucher</button>
            </form>
        </div>
    </div>

    <!-- Danh Sách Voucher -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Danh Sách Voucher (<?php echo count($vouchers ?? []); ?>)</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã Voucher</th>
                        <th>Giá Giảm</th>
                        <th>Đơn Tối Thiểu</th>
                        <th>Số Lượng</th>
                        <th>Đã Dùng</th>
                        <th>Thời Gian</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($vouchers)): ?>
                        <?php foreach ($vouchers as $v): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($v['code']); ?></strong></td>
                                <td><?php echo number_format($v['discount_value']); ?>₫</td>
                                <td><?php echo number_format($v['min_amount']); ?>₫</td>
                                <td><?php echo $v['quantity']; ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo $v['quantity_used']; ?>/<?php echo $v['quantity']; ?></span>
                                </td>
                                <td>
                                    <small><?php echo date('d/m/Y', strtotime($v['time_start'])); ?> - <?php echo date('d/m/Y', strtotime($v['time_end'])); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $v['status'] === 'active' ? 'success' : 'danger'; ?>">
                                        <?php echo $v['status'] === 'active' ? 'Hoạt động' : 'Tắt'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editVoucher(<?php echo htmlspecialchars(json_encode($v)); ?>)">Sửa</button>
                                    <form method="POST" action="<?php echo APP_URL; ?>/?url=Distributor/deleteVoucher" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $v['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Bạn chưa tạo voucher nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Sửa Voucher -->
<div class="modal fade" id="editVoucherModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Sửa Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo APP_URL; ?>/?url=Distributor/updateVoucher">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="mb-3">
                        <label class="form-label">Mã Voucher</label>
                        <input type="text" class="form-control" id="edit_code" disabled>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá Khuyến Mãi (₫)</label>
                            <input type="number" class="form-control" id="edit_discount_value" name="discount_value" required min="1000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Đơn Tối Thiểu (₫)</label>
                            <input type="number" class="form-control" id="edit_min_amount" name="min_amount" required min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Lượng Voucher</label>
                            <input type="number" class="form-control" id="edit_quantity" name="quantity" required min="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trạng Thái</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Không hoạt động</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày Bắt Đầu</label>
                            <input type="datetime-local" class="form-control" id="edit_time_start" name="time_start" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày Kết Thúc</label>
                            <input type="datetime-local" class="form-control" id="edit_time_end" name="time_end" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editVoucher(voucher) {
    document.getElementById('edit_id').value = voucher.id;
    document.getElementById('edit_code').value = voucher.code;
    document.getElementById('edit_discount_value').value = voucher.discount_value;
    document.getElementById('edit_min_amount').value = voucher.min_amount;
    document.getElementById('edit_quantity').value = voucher.quantity;
    document.getElementById('edit_status').value = voucher.status;
    document.getElementById('edit_time_start').value = voucher.time_start.slice(0, 16);
    document.getElementById('edit_time_end').value = voucher.time_end.slice(0, 16);
    
    new bootstrap.Modal(document.getElementById('editVoucherModal')).show();
}
</script>
