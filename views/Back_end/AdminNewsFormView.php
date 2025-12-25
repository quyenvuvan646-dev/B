<div class="container mt-4">
  <?php $editing = isset($data['item']); $item = $data['item'] ?? null; ?>
  <h3><?php echo $editing ? 'Sửa tin tức' : 'Đăng tin tức'; ?></h3>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Tiêu đề</label>
      <input type="text" class="form-control" name="title" required value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" />
    </div>
    <div class="mb-3">
      <label class="form-label">Nội dung</label>
      <textarea class="form-control" name="content" rows="8" required><?php echo htmlspecialchars($item['content'] ?? ''); ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Ảnh (tùy chọn)</label>
      <?php if (!empty($item['image'])): ?>
        <div class="mb-2"><img src="<?php echo APP_URL; ?>/public/images/news/<?php echo htmlspecialchars($item['image']); ?>" alt="news" style="max-height:120px;"/></div>
      <?php endif; ?>
      <input type="file" class="form-control" name="image" accept="image/*" />
    </div>
    <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" id="is_published" name="is_published" <?php echo (isset($item['is_published']) ? ((int)$item['is_published'] === 1) : true) ? 'checked' : ''; ?> />
      <label class="form-check-label" for="is_published">Hiển thị</label>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-success" type="submit">Lưu</button>
      <a class="btn btn-secondary" href="<?php echo APP_URL; ?>/News/adminIndex">Hủy</a>
    </div>
  </form>
</div>
