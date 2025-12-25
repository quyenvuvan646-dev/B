<div class="container mt-5" style="max-width: 480px;">
    <h2 class="mb-3">Quên mật khẩu</h2>

    <?php if (!empty($data['message'])): ?>
        <div class="alert alert-<?php echo $data['messageType'] ?? 'info'; ?>">
            <?php echo htmlspecialchars($data['message']); ?>
        </div>
        <?php if (($data['messageType'] ?? '') === 'success'): ?>
            <a href="<?php echo APP_URL; ?>/AuthController/ShowLogin" class="btn btn-success w-100 mb-3">Đăng nhập và đổi mật khẩu luôn</a>
        <?php endif; ?>
    <?php endif; ?>

    <form action="<?php echo APP_URL; ?>/AuthController/resetPassword" method="POST" class="card card-body shadow-sm">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($data['prefillEmail'] ?? ''); ?>">
        </div>
        <button type="submit" class="btn btn-primary w-100">Gửi lại mật khẩu</button>
    </form>
    <div class="mt-3 text-center">
        <a href="<?php echo APP_URL; ?>/AuthController/ShowLogin" class="btn btn-link">Đăng nhập</a>
    </div>
</div>
