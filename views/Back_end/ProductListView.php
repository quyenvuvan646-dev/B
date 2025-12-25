
 <div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary mb-0">Quản lý sản phẩm</h3>
        <a href="<?= APP_URL ?>/Product/create" class="btn btn-success">
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
                            <th>Giá nhập</th>
                            <th>Giá xuất</th>
                            <th>KM</th>
                            <th>Mô tả</th>
                            <th>Email bán</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                     <?php
                        if (!empty($data['productList'])) {
                            $i = 1;
                            foreach ($data['productList'] as  $k => $v) {
                        ?>
                        <tr>
                            <td><?= $i++?></td>
                            <td>
                                <img src="<?php echo APP_URL;?>/public/images/<?= htmlspecialchars($v['hinhanh']) ?>" 
                                style="height: 10rem;"/>
                            </td>
                            <td>
                              <?= htmlspecialchars($v["masp"]) ?> 
                            </td>
                            <td>
                                <?= htmlspecialchars($v["tensp"]) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($v["maLoaiSP"]) ?>
                            </td>
                            <td>
                                <?php 
                                    $quantity = intval($v["soluong"]);
                                    if ($quantity == 0) {
                                        echo '<span class="badge bg-danger">Hết hàng (0)</span>';
                                    } else {
                                        echo htmlspecialchars($quantity);
                                    }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($v["giaNhap"]) ?> </td>
                            <td><?= htmlspecialchars($v["giaXuat"]) ?></td>
                            <td><?= htmlspecialchars($v["khuyenmai"]) ?></td>
                            <td><?= htmlspecialchars($v["mota"]) ?> </td>
                            <td><?= htmlspecialchars($v["email"] ?? '') ?></td>
                            <td><?= htmlspecialchars($v["createDate"]) ?></td>
                            <td>
                                <a href="<?= APP_URL ?>/Product/edit/<?= $v["masp"] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                                <a href="<?= APP_URL ?>/Product/delete/<?= $v["masp"] ?>" class="btn btn-danger btn-sm"
                                 onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?');">🗑️ Xoá</a>
                            </td>
                        </tr>
                        <?php } 
                        } else {
                        ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                Không có sản phẩm nào.
                            </td>
                        </tr>
                        <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>