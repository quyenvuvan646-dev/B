<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Nhập địa chỉ hiện tại</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['shipper_error'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['shipper_error']); unset($_SESSION['shipper_error']); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo APP_URL; ?>/?url=Shipper/saveAddress">
                        <div class="mb-3">
                            <label for="shipper_address" class="form-label">Địa chỉ hiện tại</label>
                            <input type="text" class="form-control" id="shipper_address" name="shipper_address" placeholder="Ví dụ: 15 Nguyễn Khang, Cầu Giấy, Hà Nội">
                            <div class="form-text">Địa chỉ này sẽ được dùng để tìm các đơn hàng gần bạn (<=10km).</div>
                        </div>
                        <button class="btn btn-primary" type="submit">Lưu và tiếp tục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>