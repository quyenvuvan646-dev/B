<form action="" method="post" enctype="multipart/form-data" class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?php echo isset($data['editItem']) ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới'; ?></h5>
        </div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <?php 
                if (isset($data['editItem']) && $data['editItem']['hinhanh']) {
                    echo "<img src='" . APP_URL . "/public/images/" . $data['editItem']['hinhanh'] . "' 
                          class='img-thumbnail mb-2' style='height: 10rem; width: auto;'>";
                }
                else {?>
                    <img src="<?php echo APP_URL?>/public/images/defaut.png" >
              <?php  }
                ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Mã loại sản phẩm</label>
                <select name="txt_maloaisp" class="form-select">
                    <?php
                    foreach ($data["producttype"] as $k => $v) {
                        $selected = (isset($data['editItem']) && $data['editItem']['maLoaiSP'] == $v["maLoaiSP"]) ? "selected" : "";
                        echo "<option value='{$v["maLoaiSP"]}' $selected>{$v["maLoaiSP"]}</option>";
                    }
                    ?>
                </select>
                <br>
                <label class="form-label">Mã sản phẩm</label>
                <input type="text" name="txt_masp" class="form-control"
                       value="<?php echo isset($data['editItem']) ? $data['editItem']['masp'] : ''; ?>"
                       <?php echo isset($data['editItem']) ? 'readonly' : ''; ?>>
            </div>

            <div class="col-md-6">
                <label class="form-label">Hình ảnh</label><br>
                <input type="file" name="uploadfile" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="txt_tensp" class="form-control"
                       value="<?php echo isset($data['editItem']) ? $data['editItem']['tensp'] : ''; ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Số lượng</label>
                <input type="number" name="txt_soluong" class="form-control"
                       value="<?php echo isset($data['editItem']) ? $data['editItem']['soluong'] : ''; ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Giá nhập</label>
                <input type="number" name="txt_gianhap" class="form-control"
                       value="<?php echo isset($data['editItem']) ? $data['editItem']['giaNhap'] : ''; ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Giá xuất</label>
                <input type="number" name="txt_giaxuat" class="form-control"
                       value="<?php echo isset($data['editItem']) ? $data['editItem']['giaXuat'] : ''; ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Khuyến mại</label>
                <input type="number" name="txt_khuyenmai" class="form-control"
                       value="<?php echo isset($data['editItem']) ? $data['editItem']['khuyenmai'] : ''; ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ngày tạo</label>
                <input type="date" name="create_date" class="form-control"
                       value="<?php echo isset($data['editItem']) ? $data['editItem']['createDate'] : ''; ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email người bán</label>
                <input type="email" name="txt_email" class="form-control"
                       value="<?php echo isset($data['editItem']) ? ($data['editItem']['email'] ?? '') : ($_SESSION['user']['email'] ?? ''); ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Mô tả</label>
                <textarea name="txt_mota" rows="1" class="form-control"><?php echo isset($data['editItem']) ? $data['editItem']['mota'] : ''; ?></textarea>
            </div>
        </div>

        <div class="card-footer text-end">
            <input type="submit" name="btn_submit"
                   class="btn btn-<?php echo isset($data['editItem']) ? 'warning' : 'success'; ?>"
                   value="<?php echo isset($data['editItem']) ? 'Cập nhật' : 'Lưu'; ?>">
        </div>
    </div>
</form>
