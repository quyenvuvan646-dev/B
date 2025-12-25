<div class="container mt-5">
    <h1>🎟️ Quản Lý Voucher Admin</h1>
    <p class="text-muted">Tạo và quản lý voucher để giảm giá gốc hoặc giảm giá ship</p>

    <!-- Form Thêm Voucher -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5>➕ Thêm Voucher Mới</h5>
        </div>
        <div class="card-body">
            <form id="addVoucherForm" method="POST" action="<?php echo APP_URL; ?>/?url=Admin/addVoucher">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vc_id" class="form-label">Mã Voucher <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="vc_id" name="vc_id" required placeholder="VCH001">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="chuc_nang" class="form-label">Loại Voucher <span class="text-danger">*</span></label>
                            <select class="form-control" id="chuc_nang" name="chuc_nang" required>
                                <option value="">-- Chọn loại --</option>
                                <option value="goc">🎁 Giảm Giá Gốc (Giảm giá sản phẩm)</option>
                                <option value="ship">🚚 Giảm Giá Ship (Giảm phí vận chuyển)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="giagiam" class="form-label">Giảm Giá (VND) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="giagiam" name="giagiam" required min="0" placeholder="50000">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="giatoithieu" class="form-label">Giá Tối Thiểu (VND) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="giatoithieu" name="giatoithieu" required min="0" placeholder="100000">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="soluong" class="form-label">Số Lượng <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="soluong" name="soluong" required min="1" placeholder="100">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ngaybatdau" class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ngayketthuc" class="form-label">Ngày Kết Thúc <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="trangthai" name="trangthai" value="1" checked>
                        <label class="form-check-label" for="trangthai">
                            ✓ Kích Hoạt Voucher Ngay
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">✓ Thêm Voucher</button>
            </form>
        </div>
    </div>

    <!-- Danh Sách Voucher -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5>📋 Danh Sách Voucher</h5>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-all" data-bs-toggle="tab" href="#all-vouchers" role="tab">Tất Cả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-goc" data-bs-toggle="tab" href="#goc-vouchers" role="tab">🎁 Giảm Giá Gốc</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-ship" data-bs-toggle="tab" href="#ship-vouchers" role="tab">🚚 Giảm Giá Ship</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Tab: Tất Cả -->
                <div class="tab-pane fade show active" id="all-vouchers" role="tabpanel">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Mã Voucher</th>
                                <th>Loại</th>
                                <th>Giảm Giá</th>
                                <th>Tối Thiểu</th>
                                <th>Số Lượng</th>
                                <th>Đã Dùng</th>
                                <th>Thời Gian</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="allVouchersBody">
                            <tr><td colspan="9" class="text-center text-muted">Đang tải...</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab: Giảm Giá Gốc -->
                <div class="tab-pane fade" id="goc-vouchers" role="tabpanel">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Mã Voucher</th>
                                <th>Loại</th>
                                <th>Giảm Giá</th>
                                <th>Tối Thiểu</th>
                                <th>Số Lượng</th>
                                <th>Đã Dùng</th>
                                <th>Thời Gian</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="gocVouchersBody">
                            <tr><td colspan="9" class="text-center text-muted">Đang tải...</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab: Giảm Giá Ship -->
                <div class="tab-pane fade" id="ship-vouchers" role="tabpanel">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Mã Voucher</th>
                                <th>Loại</th>
                                <th>Giảm Giá</th>
                                <th>Số Lượng</th>
                                <th>Đã Dùng</th>
                                <th>Thời Gian</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="shipVouchersBody">
                            <tr><td colspan="8" class="text-center text-muted">Đang tải...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sửa Voucher -->
<div class="modal fade" id="editVoucherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">✏️ Sửa Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editVoucherForm" method="POST" action="<?php echo APP_URL; ?>/?url=Admin/updateVoucher">
                <div class="modal-body">
                    <input type="hidden" id="edit_vc_id" name="vc_id">
                    <div class="mb-3">
                        <label for="edit_chuc_nang" class="form-label">Loại Voucher</label>
                        <select class="form-control" id="edit_chuc_nang" name="chuc_nang" required>
                            <option value="goc">🎁 Giảm Giá Gốc</option>
                            <option value="ship">🚚 Giảm Giá Ship</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_giagiam" class="form-label">Giảm Giá (VND)</label>
                        <input type="number" class="form-control" id="edit_giagiam" name="giagiam" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="edit_giatoithieu" class="form-label">Giá Tối Thiểu (VND)</label>
                        <input type="number" class="form-control" id="edit_giatoithieu" name="giatoithieu" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="edit_soluong" class="form-label">Số Lượng</label>
                        <input type="number" class="form-control" id="edit_soluong" name="soluong" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="edit_ngaybatdau" class="form-label">Ngày Bắt Đầu</label>
                        <input type="date" class="form-control" id="edit_ngaybatdau" name="ngaybatdau" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_ngayketthuc" class="form-label">Ngày Kết Thúc</label>
                        <input type="date" class="form-control" id="edit_ngayketthuc" name="ngayketthuc" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_trangthai" name="trangthai" value="1">
                        <label class="form-check-label" for="edit_trangthai">Kích Hoạt</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">💾 Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Load vouchers khi trang load
    document.addEventListener('DOMContentLoaded', function() {
        loadAllVouchers();
        loadGocVouchers();
        loadShipVouchers();
    });

    function loadAllVouchers() {
        fetch('<?php echo APP_URL; ?>/?url=Admin/getAllVouchers', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            let html = '';
            if (data.success && data.vouchers.length > 0) {
                data.vouchers.forEach(v => {
                    let chucNangBadge = v.chuc_nang === 'ship' ? '🚚 Ship' : '🎁 Gốc';
                    let statusBadge = (v.trangthai == 1) ? '<span class="badge bg-success">✓ Hoạt động</span>' : '<span class="badge bg-danger">✗ Tắt</span>';
                    html += `<tr>
                        <td><strong>${v.vc_id}</strong></td>
                        <td>${chucNangBadge}</td>
                        <td>${v.giagiam.toLocaleString('vi-VN')}đ</td>
                        <td>${v.giatoithieu.toLocaleString('vi-VN')}đ</td>
                        <td>${v.soluong}</td>
                        <td>${v.soluong_used || 0}</td>
                        <td>${v.ngaybatdau.substring(0, 10)} ~ ${v.ngayketthuc.substring(0, 10)}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editVoucher('${v.vc_id}')">Sửa</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteVoucher('${v.vc_id}')">Xóa</button>
                        </td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="9" class="text-center text-muted">Chưa có voucher nào</td></tr>';
            }
            document.getElementById('allVouchersBody').innerHTML = html;
        })
        .catch(err => console.error('Error:', err));
    }

    function loadGocVouchers() {
        fetch('<?php echo APP_URL; ?>/?url=Admin/getVouchersByType&type=goc', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            let html = '';
            if (data.success && data.vouchers.length > 0) {
                data.vouchers.forEach(v => {
                    let statusBadge = (v.trangthai == 1) ? '<span class="badge bg-success">✓ Hoạt động</span>' : '<span class="badge bg-danger">✗ Tắt</span>';
                    let chucNangBadge = '🎁 Gốc';
                    html += `<tr>
                        <td><strong>${v.vc_id}</strong></td>
                        <td>${chucNangBadge}</td>
                        <td>${v.giagiam.toLocaleString('vi-VN')}đ</td>
                        <td>${v.giatoithieu.toLocaleString('vi-VN')}đ</td>
                        <td>${v.soluong}</td>
                        <td>${v.soluong_used || 0}</td>
                        <td>${v.ngaybatdau.substring(0, 10)} ~ ${v.ngayketthuc.substring(0, 10)}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editVoucher('${v.vc_id}')">Sửa</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteVoucher('${v.vc_id}')">Xóa</button>
                        </td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="8" class="text-center text-muted">Chưa có voucher giảm giá gốc</td></tr>';
            }
            document.getElementById('gocVouchersBody').innerHTML = html;
        })
        .catch(err => console.error('Error:', err));
    }

    function loadShipVouchers() {
        fetch('<?php echo APP_URL; ?>/?url=Admin/getVouchersByType&type=ship', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            let html = '';
            if (data.success && data.vouchers.length > 0) {
                data.vouchers.forEach(v => {
                    let statusBadge = (v.trangthai == 1) ? '<span class="badge bg-success">✓ Hoạt động</span>' : '<span class="badge bg-danger">✗ Tắt</span>';
                    let chucNangBadge = '🚚 Ship';
                    html += `<tr>
                        <td><strong>${v.vc_id}</strong></td>
                        <td>${chucNangBadge}</td>
                        <td>${v.giagiam.toLocaleString('vi-VN')}đ</td>
                        <td>${v.soluong}</td>
                        <td>${v.soluong_used || 0}</td>
                        <td>${v.ngaybatdau.substring(0, 10)} ~ ${v.ngayketthuc.substring(0, 10)}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editVoucher('${v.vc_id}')">Sửa</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteVoucher('${v.vc_id}')">Xóa</button>
                        </td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="7" class="text-center text-muted">Chưa có voucher giảm giá ship</td></tr>';
            }
            document.getElementById('shipVouchersBody').innerHTML = html;
        })
        .catch(err => console.error('Error:', err));
    }

    function editVoucher(vc_id) {
        fetch('<?php echo APP_URL; ?>/?url=Admin/getVoucherById&vc_id=' + encodeURIComponent(vc_id), {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.voucher) {
                document.getElementById('edit_vc_id').value = data.voucher.vc_id;
                document.getElementById('edit_chuc_nang').value = data.voucher.chuc_nang || 'goc';
                document.getElementById('edit_giagiam').value = data.voucher.giagiam;
                document.getElementById('edit_giatoithieu').value = data.voucher.giatoithieu;
                document.getElementById('edit_soluong').value = data.voucher.soluong;
                document.getElementById('edit_ngaybatdau').value = data.voucher.ngaybatdau.substring(0, 10);
                document.getElementById('edit_ngayketthuc').value = data.voucher.ngayketthuc.substring(0, 10);
                document.getElementById('edit_trangthai').checked = data.voucher.trangthai == 1;
                
                new bootstrap.Modal(document.getElementById('editVoucherModal')).show();
            }
        })
        .catch(err => console.error('Error:', err));
    }

    function deleteVoucher(vc_id) {
        if (confirm('Bạn chắc chắn muốn xóa voucher này?')) {
            fetch('<?php echo APP_URL; ?>/?url=Admin/deleteVoucher', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'vc_id=' + encodeURIComponent(vc_id)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✓ Xóa voucher thành công');
                    loadAllVouchers();
                    loadGocVouchers();
                    loadShipVouchers();
                } else {
                    alert('✗ Xóa thất bại: ' + data.message);
                }
            })
            .catch(err => console.error('Error:', err));
        }
    }

    // Reload sau khi sửa (ngăn submit mặc định, gửi bằng fetch, reload data)
    document.getElementById('editVoucherForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        fetch('<?php echo APP_URL; ?>/?url=Admin/updateVoucher', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('✓ Cập nhật voucher thành công');
                new bootstrap.Modal(document.getElementById('editVoucherModal')).hide();
                loadAllVouchers();
                loadGocVouchers();
                loadShipVouchers();
            } else {
                alert('✗ Cập nhật thất bại: ' + (data.message || 'Lỗi không xác định'));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('✗ Lỗi: ' + err);
        });
    });

    // Reload sau khi thêm voucher
    document.getElementById('addVoucherForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        fetch('<?php echo APP_URL; ?>/?url=Admin/addVoucher', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('✓ Thêm voucher thành công');
                this.reset();
                loadAllVouchers();
                loadGocVouchers();
                loadShipVouchers();
            } else {
                alert('✗ Thêm thất bại: ' + (data.message || 'Lỗi không xác định'));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('✗ Lỗi: ' + err);
        });
    });
</script>
