<style>
.news-container{max-width:1100px;margin:30px auto;padding:0 16px}
.card-news{background:linear-gradient(135deg,#1e293b,#0f172a);border:1px solid #334155;border-radius:14px;padding:18px;color:#e2e8f0}
.card-news h4{color:#93c5fd;margin:0 0 8px 0}
.card-news p{color:#cbd5e1;margin:0}
.card-news .meta{color:#94a3b8;font-size:12px;margin-top:8px}
.grid{display:grid;grid-template-columns:1fr;gap:16px}
@media(min-width:900px){.grid{grid-template-columns:1fr 1fr}}
</style>
<div class="news-container">
  <h2 style="color:#fff;margin-bottom:16px">📰 Tin tức</h2>
  <div class="grid">
    <?php foreach(($news ?? []) as $n): ?>
      <a href="<?php echo APP_URL; ?>/News/detail/<?php echo (int)$n['id']; ?>" style="text-decoration:none;">
        <div class="card-news">
          <h4><?php echo htmlspecialchars($n['title']); ?></h4>
          <div class="meta">Ngày đăng: <?php echo htmlspecialchars($n['created_at']); ?></div>
          <?php if (!empty($n['image'])): ?>
            <img src="<?php echo APP_URL; ?>/public/images/news/<?php echo htmlspecialchars($n['image']); ?>" alt="news" style="width:100%;max-height:220px;object-fit:cover;border-radius:10px;border:1px solid #334155;margin-top:10px" />
          <?php endif; ?>
          <p style="margin-top:10px;line-height:1.5;max-height:4.5em;overflow:hidden;"> <?php echo htmlspecialchars(mb_strimwidth(strip_tags($n['content']),0,240,'...')); ?> </p>
        </div>
      </a>
    <?php endforeach; ?>
    <?php if (empty($news)): ?>
      <div class="card-news">Chưa có tin tức.</div>
    <?php endif; ?>
  </div>
</div>
