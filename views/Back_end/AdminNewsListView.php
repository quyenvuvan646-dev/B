<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Tin tức</h3>
    <a class="btn btn-primary" href="<?php echo APP_URL; ?>/News/adminCreate">Đăng tin mới</a>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Tiêu đề</th>
        <th>Trạng thái</th>
        <th>Ngày tạo</th>
        <th></th>
          <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach (($list ?? []) as $n): ?>
      <tr>
        <td><?php echo (int)$n['id']; ?></td>
        <td><?php echo htmlspecialchars($n['title']); ?></td>
        <td><?php echo ((int)$n['is_published'] === 1) ? 'Hiển thị' : 'Ẩn'; ?></td>
        <td><?php echo htmlspecialchars($n['created_at']); ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-secondary" href="<?php echo APP_URL; ?>/News/adminEdit/<?php echo (int)$n['id']; ?>">Sửa</a>
            <a class="btn btn-sm btn-outline-<?php echo ((int)$n['is_published'] === 1) ? 'warning' : 'success'; ?>" href="<?php echo APP_URL; ?>/News/adminToggle/<?php echo (int)$n['id']; ?>">
              <?php echo ((int)$n['is_published'] === 1) ? 'Ẩn' : 'Hiện'; ?>
            </a>
            <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa tin này?');" href="<?php echo APP_URL; ?>/News/adminDelete/<?php echo (int)$n['id']; ?>">Xóa</a>
          </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($list)): ?>
      <tr><td colspan="5" class="text-center">Chưa có tin tức</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
