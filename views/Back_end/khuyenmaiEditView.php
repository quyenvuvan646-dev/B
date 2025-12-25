<div class="container mt-4">
    <h2 class="mb-4">Sửa khuyến mãi</h2>

    <!-- === FORM SỬA KHUYẾN MÃI === -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark fw-bold">
            Cập nhật thông tin khuyến mãi
        </div>
        <div class="card-body">
            <form method="post" action="<?= APP_URL ?>/khuyenmai/edit/<?= urlencode($data['promotion']['km_id']) ?>">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Loại sản phẩm</label>
                        <select name="maLoaiSP" id="maLoaiSP" class="form-select" required>
                            <option value="">-- Chọn loại sản phẩm --</option>
                            <?php foreach ($data["dataView"] as $type): ?>
                                <option value="<?= htmlspecialchars($type["maLoaiSP"]) ?>" 
                                    <?= $type["maLoaiSP"] === $data['promotion']['maLoaiSP'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($type["maLoaiSP"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Sản phẩm</label>
                        <select name="masp" id="masp" class="form-select">
                            <option value="">-- Áp dụng cho tất cả sản phẩm của loại --</option>
                            <?php foreach ($data["products"] as $p): ?>
                                <option value="<?= htmlspecialchars($p["masp"]) ?>" 
                                    data-loai="<?= htmlspecialchars($p["maLoaiSP"]) ?>"
                                    <?= $p["masp"] === $data['promotion']['masp'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p["tensp"]) ?> (<?= htmlspecialchars($p["masp"]) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <script>
                        document.getElementById('maLoaiSP').addEventListener('change', function() {
                            const selectedLoai = this.value;
                            const maspSelect = document.getElementById('masp');
                            Array.from(maspSelect.options).forEach(opt => {
                                if (opt.value === "") return; // giữ dòng đầu tiên
                                opt.hidden = selectedLoai && opt.getAttribute('data-loai') !== selectedLoai;
                            });
                        });
                    </script>

                    <div class="col-md-4">
                        <label>Phần trăm khuyến mãi (%)</label>
                        <input type="number" name="phantram" class="form-control" min="1" max="100" 
                            value="<?= htmlspecialchars($data['promotion']['phantram']) ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Ngày bắt đầu</label>
                        <input type="date" name="ngaybatdau" class="form-control" 
                            value="<?= htmlspecialchars($data['promotion']['ngaybatdau']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Ngày kết thúc</label>
                        <input type="date" name="ngayketthuc" class="form-control" 
                            value="<?= htmlspecialchars($data['promotion']['ngayketthuc']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">💾 Lưu thay đổi</button>
                        <a href="<?= APP_URL ?>/khuyenmai/show" class="btn btn-secondary">🔙 Quay lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
