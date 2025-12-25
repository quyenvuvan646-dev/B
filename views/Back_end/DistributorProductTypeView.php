<?php
// Không cần kiểm tra role
?>
<div class="container mt-5">
    <h2 class="mb-4">📦 Quản lý danh mục loại sản phẩm của tôi</h2>
    <?php if (!empty($_SESSION['pt_error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['pt_error']) ?></div>
        <?php unset($_SESSION['pt_error']); endif; ?>
    <?php if (!empty($_SESSION['pt_success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['pt_success']) ?></div>
        <?php unset($_SESSION['pt_success']); endif; ?>
    <!-- Form luôn hiển thị để có thể thêm mới kể cả khi danh sách trống -->
    <table class="table table-bordered table-hover">
        <tr>
            <td colspan="5">
                <?php
                // Nếu tồn tại biến $data["editItem"] thì đang ở chế độ sửa
                $isEdit = isset($data["editItem"]);
                $edit = $isEdit ? $data["editItem"] : null;
                ?>
                <form
                    action="<?= $isEdit ? (APP_URL . "/Distributor/ptSave/" . urlencode($edit["maLoaiSP"])) : (APP_URL . "/Distributor/ptCreate") ?>"
                    method="post"
                    class="bg-light p-3 rounded shadow-sm">
                    <div class="row align-items-end gx-3 gy-2">
                        <!-- Mã loại sản phẩm -->
                        <div class="col-md-2">
                            <label for="txt_maloaisp" class="form-label">Mã loại SP</label>
                            <input type="text" name="txt_maloaisp" id="txt_maloaisp" class="form-control"
                                required value="<?= $isEdit ? htmlspecialchars($edit["maLoaiSP"]) : '' ?>"
                                <?= $isEdit ? 'readonly' : '' ?> />
                        </div>

                        <!-- Tên loại sản phẩm -->
                        <div class="col-md-2">
                            <label for="txt_tenloaisp" class="form-label">Tên loại SP</label>
                            <input type="text"
                                name="txt_tenloaisp"
                                id="txt_tenloaisp"
                                class="form-control"
                                value="<?= $isEdit ? htmlspecialchars($edit["tenLoaiSP"]) : '' ?>" />
                        </div>

                        <!-- Mô tả loại sản phẩm -->
                        <div class="col-md-2">
                            <label for="txt_motaloaisp" class="form-label">Mô tả</label>
                            <input type="text"
                                name="txt_motaloaisp"
                                id="txt_motaloaisp"
                                class="form-control"
                                value="<?= $isEdit ? htmlspecialchars($edit["moTaLoaiSP"]) : '' ?>" />
                        </div>

                        <!-- Nút hành động -->
                        <div class="col-md-<?= $isEdit ? '3' : '4' ?>">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-<?= $isEdit ? 'warning' : 'primary' ?>">
                                    💾 <?= $isEdit ? "Cập nhật" : "Thêm mới" ?>
                                </button>
                                <!-- Nút Huỷ -->
                                <?php if ($isEdit): ?>
                                    <a href="<?= APP_URL ?>/Distributor/productTypes" class="btn btn-secondary">
                                        🔁 Huỷ
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
        <tr>
            <th>STT</th>
            <th>Mã loại</th>
            <th>Tên loại SP</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
        <?php if (!empty($data["productList"])): ?>
            <?php $i = 0; foreach ($data["productList"] as $k => $v): $i++; ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= htmlspecialchars($v["maLoaiSP"]) ?></td>
                    <td><?= htmlspecialchars($v["tenLoaiSP"]) ?> </td>
                    <td><?= htmlspecialchars($v["moTaLoaiSP"]) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/Distributor/ptEdit/<?= urlencode($v["maLoaiSP"]) ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                        <a href="<?= APP_URL ?>/Distributor/ptRemove/<?= urlencode($v["maLoaiSP"]) ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn có chắc muốn xoá loại sản phẩm này?');">🗑️ Xoá</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center text-muted">Chưa có loại sản phẩm nào. Hãy thêm mới ở form phía trên.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
