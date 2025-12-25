<div class="container mt-4">
    <h2 class="mb-4">Quản lý Voucher</h2>

    <!-- === FORM THÊM/SỬA VOUCHER === -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
            <?= isset($data["editVoucher"]) ? "Sửa Voucher" : "Thêm Voucher Mới" ?>
        </div>
        <div class="card-body">
            <form method="post" action="<?= APP_URL ?>/voucher/show">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Mã Voucher</label>
                        <input type="text" name="vc_id" class="form-control" 
                               value="<?= isset($data["editVoucher"]) ? htmlspecialchars($data["editVoucher"]["vc_id"]) : "" ?>"
                               <?= isset($data["editVoucher"]) ? "readonly" : "" ?> required>
                    </div>
                    <div class="col-md-6">
                        <label>Trạng Thái</label>
                        <select name="trangthai" class="form-select" required>
                            <option value="1" <?= (isset($data["editVoucher"]) && $data["editVoucher"]["trangthai"] == 1) ? "selected" : "" ?>>Kích Hoạt</option>
                            <option value="0" <?= (isset($data["editVoucher"]) && $data["editVoucher"]["trangthai"] == 0) ? "selected" : "" ?>>Vô Hiệu</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Ngày Bắt Đầu</label>
                        <input type="date" name="ngaybatdau" class="form-control"
                               value="<?= isset($data["editVoucher"]) ? htmlspecialchars($data["editVoucher"]["ngaybatdau"]) : "" ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Ngày Kết Thúc</label>
                        <input type="date" name="ngayketthuc" class="form-control"
                               value="<?= isset($data["editVoucher"]) ? htmlspecialchars($data["editVoucher"]["ngayketthuc"]) : "" ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Giá Tối Thiểu (₫)</label>
                        <input type="number" name="giatoithieu" class="form-control" min="0" step="1000"
                               value="<?= isset($data["editVoucher"]) ? htmlspecialchars($data["editVoucher"]["giatoithieu"]) : "" ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Giá Giảm (₫)</label>
                        <input type="number" name="giagiam" class="form-control" min="0" step="1000"
                               value="<?= isset($data["editVoucher"]) ? htmlspecialchars($data["editVoucher"]["giagiam"]) : "" ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Số Lượng</label>
                        <input type="number" name="soluong" class="form-control" min="0"
                               value="<?= isset($data["editVoucher"]) ? htmlspecialchars($data["editVoucher"]["soluong"]) : "" ?>" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <?= isset($data["editVoucher"]) ? "🔄 Cập Nhật" : "➕ Thêm Voucher" ?>
                </button>
                <?php if (isset($data["editVoucher"])): ?>
                    <a href="<?= APP_URL ?>/voucher/show" class="btn btn-secondary">Hủy</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- === DANH SÁCH VOUCHER === -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold">
            Danh Sách Voucher
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Mã Voucher</th>
                            <th>Giá Tối Thiểu</th>
                            <th>Giảm</th>
                            <th>Số Lượng</th>
                            <th>Từ Ngày</th>
                            <th>Đến Ngày</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stt = 1;
                        if (!empty($data["voucherList"])):
                            foreach ($data["voucherList"] as $row):
                        ?>
                            <tr>
                                <td><?= $stt++ ?></td>
                                <td><strong><?= htmlspecialchars($row["vc_id"]) ?></strong></td>
                                <td><?= number_format($row["giatoithieu"], 0, ',', '.') ?> ₫</td>
                                <td><?= number_format($row["giagiam"], 0, ',', '.') ?> ₫</td>
                                <td><?= htmlspecialchars($row["soluong"]) ?></td>
                                <td><?= htmlspecialchars($row["ngaybatdau"]) ?></td>
                                <td><?= htmlspecialchars($row["ngayketthuc"]) ?></td>
                                <td>
                                    <?php if ($row["trangthai"] == 1): ?>
                                        <span class="badge bg-success">Kích Hoạt</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Vô Hiệu</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= APP_URL ?>/voucher/edit/<?= urlencode($row["vc_id"]) ?>"
                                       class="btn btn-warning btn-sm">✏️ Sửa</a>
                                    <a href="<?= APP_URL ?>/voucher/delete/<?= urlencode($row["vc_id"]) ?>"
                                       onclick="return confirm('Xóa voucher này?')"
                                       class="btn btn-danger btn-sm">🗑️ Xóa</a>
                                </td>
                            </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">Chưa có voucher nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>