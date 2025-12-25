<?php
$productList = $data['productList'] ?? [];
?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary mb-0">Sản phẩm của tôi</h3>
        <a href="<?= APP_URL ?>/Distributor/createProduct" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm sản phẩm
        </a>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Danh sách sản phẩm</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Ảnh</th>
                            <th>Mã SP</th>
                            <th>Tên SP</th>
                            <th>Loại</th>
                            <th>Số lượng</th>
                            <th>Giá xuất</th>
                            <th>Khuyến mãi</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                     <?php
                        if (!empty($productList)) {
                            $i = 1;
                            foreach ($productList as $k => $v) {
                        ?>
                        <tr>
                            <td><?= $i++?></td>
                            <td>
                                <?php if (!empty($v['hinhanh'])): ?>
                                    <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($v['hinhanh']) ?>" 
                                    style="height: 3rem; width: auto;"/>
                                <?php else: ?>
                                    <em class="text-muted">Không có</em>
                                <?php endif; ?>
                            </td>
                            <td>
                              <?= htmlspecialchars($v["masp"] ?? '') ?> 
                            </td>
                            <td>
                                <?= htmlspecialchars($v["tensp"] ?? '') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($v["maLoaiSP"] ?? 'N/A') ?>
                            </td>
                            <td>
                                <?php 
                                    $quantity = intval($v["soluong"] ?? 0);
                                    if ($quantity == 0) {
                                        echo '<span class="badge bg-danger">Hết hàng (0)</span>';
                                    } else {
                                        echo htmlspecialchars($quantity);
                                    }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($v["giaXuat"] ?? 0) ?></td>
                            <td><?= htmlspecialchars($v["khuyenmai"] ?? '') ?></td>
                            <td>
                                <a href="<?= APP_URL ?>/Distributor/editProduct/<?= htmlspecialchars($v['masp']) ?>" class="btn btn-sm btn-warning">✏️ Sửa</a>
                                <a href="<?= APP_URL ?>/Distributor/deleteProduct/<?= htmlspecialchars($v['masp']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">🗑️ Xóa</a>
                            </td>
                        </tr>
                        <?php } 
                        } else {
                        ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Bạn chưa đăng sản phẩm nào.
                            </td>
                        </tr>
                        <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
