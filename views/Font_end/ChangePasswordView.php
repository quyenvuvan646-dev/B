<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card" style="background: rgba(30,41,59,0.85); border: 1px solid rgba(148,163,184,0.2); color: #e2e8f0;">
        <div class="card-body p-4">
          <h5 class="card-title mb-4" style="color:#f8fafc">Đổi mật khẩu</h5>
          <form method="post" action="<?php echo APP_URL; ?>/AuthController/changePassword">
            <div class="mb-3">
              <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
              <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="mb-3">
              <label for="new_password" class="form-label">Mật khẩu mới</label>
              <input type="password" class="form-control" id="new_password" name="new_password" minlength="6" required>
            </div>
            <div class="mb-4">
              <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
            </div>
            <div class="d-flex justify-content-end gap-2">
              <a href="<?php echo APP_URL; ?>/Home" class="btn btn-outline-light">Hủy</a>
              <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
