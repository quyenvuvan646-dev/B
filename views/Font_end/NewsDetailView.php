<style>
.article{max-width:900px;margin:30px auto;padding:0 16px}
.article-card{background:linear-gradient(135deg,#1e293b,#0f172a);border:1px solid #334155;border-radius:14px;padding:22px;color:#e2e8f0}
.article h1{color:#93c5fd;margin:0 0 12px 0}
.article .meta{color:#94a3b8;font-size:12px;margin-bottom:12px}
.article img{width:100%;max-height:420px;object-fit:cover;border-radius:10px;border:1px solid #334155;margin:12px 0}
.article .content{color:#cbd5e1;line-height:1.7}
</style>
<div class="article">
  <?php $item = $data['item'] ?? null; if ($item): ?>
  <div class="article-card">
    <h1><?php echo htmlspecialchars($item['title']); ?></h1>
    <div class="meta">Ngày đăng: <?php echo htmlspecialchars($item['created_at']); ?></div>
    <?php if (!empty($item['image'])): ?>
      <img src="<?php echo APP_URL; ?>/public/images/news/<?php echo htmlspecialchars($item['image']); ?>" alt="news" />
    <?php endif; ?>
    <div class="content"><?php echo $item['content']; ?></div>
  </div>
  <?php else: ?>
    <div class="article-card">Bài viết không tồn tại.</div>
  <?php endif; ?>
</div>
