<?php $products = $data["productList"] ?? []; $favoritesMap = $data["favoritesMap"] ?? []; ?>

<style>
.fav-hero {
    background: radial-gradient(circle at 10% 10%, rgba(96,165,250,0.12), transparent 25%),
                radial-gradient(circle at 90% 20%, rgba(59,130,246,0.14), transparent 30%),
                linear-gradient(140deg, #0b1220 0%, #102040 45%, #0a0f1d 100%);
    border-bottom: 1px solid #1f2a44;
    padding: 2.5rem 0 2rem;
    box-shadow: 0 20px 45px rgba(0,0,0,0.25);
}
.fav-hero h1 {
    color: #e2e8f0;
    font-weight: 800;
    letter-spacing: -0.02em;
    font-size: 2.15rem;
    margin-bottom: 0.5rem;
}
.fav-hero p { color: #cbd5e1; max-width: 720px; margin: 0; }

.pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 0.8rem;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 700;
    border: 1px solid rgba(148,163,184,0.25);
    background: rgba(148,163,184,0.12);
    color: #cbd5e1;
}

.pill.accent {
    border-color: rgba(236,72,153,0.35);
    background: rgba(236,72,153,0.16);
    color: #f9a8d4;
}

.fav-list { display: flex; flex-direction: column; gap: 0.9rem; }
.fav-card {
    background: linear-gradient(135deg, #111827 0%, #0f172a 100%);
    border: 1px solid #1f2a44;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: row;
    align-items: stretch;
    min-height: 120px;
    padding: 0.75rem;
    gap: 0.9rem;
}
.fav-card:hover { transform: translateY(-2px); border-color: #3b82f6; box-shadow: 0 12px 32px rgba(59,130,246,.18); }
.fav-thumb { flex: 0 0 130px; height: 110px; background:#0b1220; border: 1px solid #1f2a44; border-radius: 8px; display:flex; align-items:center; justify-content:center; overflow: hidden; cursor: pointer; transition: all 0.2s ease; }
.fav-thumb:hover { border-color: #3b82f6; box-shadow: 0 8px 20px rgba(59,130,246,.15); }
.fav-thumb img { width:100%; height:100%; object-fit:contain; }
.fav-body { padding: 0.35rem 0.35rem 0.35rem 0; flex: 1; color:#e2e8f0; display:flex; flex-direction:column; gap:0.35rem; }
.fav-name { font-size:0.95rem; font-weight:700; color:#f8fafc; display: -webkit-box; line-clamp: 2; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; cursor: pointer; transition: color 0.2s ease; }
.fav-name:hover { color: #60a5fa; }
.fav-price { font-size:1.05rem; font-weight:800; color:#93c5fd; }
.fav-meta { display:flex; gap:.5rem; color:#94a3b8; font-size:.75rem; flex-wrap: wrap; }
.fav-actions { display:flex; gap:.4rem; margin-top:auto; }
.fav-btn { flex:1; border:none; border-radius:8px; padding:.55rem .75rem; font-weight:700; font-size:.82rem; display:inline-flex; gap:.3rem; align-items:center; justify-content:center; color:#e2e8f0; transition:all .2s ease; text-decoration:none; }
.fav-btn.view { background: rgba(59, 89, 152, 0.15); border: 1px solid #3b5998; color: #93c5fd; }
.fav-btn.view:hover { background: #3b5998; }
.fav-btn.remove { background: rgba(244, 114, 182, 0.12); border: 1px solid rgba(236, 72, 153, 0.35); color: #f9a8d4; }
.fav-btn.remove:hover { border-color: #ec4899; color: #fecdd3; }
.empty-fav { border:1px dashed #334155; border-radius:12px; padding:1.5rem; background:rgba(15,23,42,0.6); color:#cbd5e1; text-align: center; }

@media (max-width: 768px) {
    .fav-card {
        flex-direction: column;
        padding: 0.85rem;
    }
    .fav-thumb {
        flex: none;
        width: 100%;
        height: 160px;
    }
}
</style>

<section class="fav-hero">
  <div class="container-fluid px-4">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
      <div class="d-flex flex-column gap-2">
        <div class="d-flex flex-wrap gap-2">
          <span class="pill accent"><i class="bi bi-heart-fill"></i> Yêu thích của bạn</span>
          <span class="pill" style="border-color: rgba(16,185,129,0.35); background: rgba(16,185,129,0.12); color: #6ee7b7;"><i class="bi bi-box"></i> <?php echo count($products); ?> sản phẩm</span>
        </div>
        <h1>Danh sách yêu thích</h1>
        <p>Những sản phẩm bạn đã lưu lại để xem nhanh và đặt hàng sau.</p>
      </div>
      <div>
        <a href="<?= APP_URL ?>/Home/products" class="btn btn-outline-light d-inline-flex align-items-center gap-2" style="padding:0.7rem 1.2rem; border-radius: 8px;"><i class="bi bi-bag"></i> Tiếp tục mua</a>
      </div>
    </div>
  </div>
</section>

<div class="container-fluid px-4 py-4">
  <?php if (empty($products)): ?>
    <div class="empty-fav">
      <div class="mb-2"><i class="bi bi-emoji-neutral" style="font-size:1.8rem;"></i></div>
      <div style="font-weight: 600; margin-bottom: 0.5rem;">Chưa có sản phẩm yêu thích</div>
      <div style="font-size: 0.9rem;">Hãy thêm những sản phẩm bạn muốn theo dõi để dễ dàng tìm kiếm sau này.</div>
    </div>
  <?php else: ?>
    <div class="fav-list">
      <?php foreach ($products as $p): ?>
        <div class="fav-card">
          <a href="<?= APP_URL ?>/Home/detail/<?= $p['masp'] ?>" class="fav-thumb" style="text-decoration: none;">
            <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($p['hinhanh']) ?>" alt="<?= htmlspecialchars($p['tensp']) ?>" onerror="this.style.display='none'">
          </a>
          <div class="fav-body">
            <a href="<?= APP_URL ?>/Home/detail/<?= $p['masp'] ?>" class="fav-name" style="text-decoration: none; color: inherit;">
              <?= htmlspecialchars($p['tensp']) ?>
            </a>
            <div class="fav-price"><?= number_format($p['giaXuat'], 0, ',', '.') ?> ₫</div>
            <div class="fav-meta">
              <span><i class="bi bi-box-seam"></i> <?= htmlspecialchars($p['masp']) ?></span>
              <span><i class="bi bi-stack"></i> Kho: <?= (int)$p['soluong'] ?></span>
            </div>
            <div class="fav-actions">
              <a class="fav-btn view" href="<?= APP_URL ?>/Home/detail/<?= $p['masp'] ?>"><i class="bi bi-eye"></i> Xem</a>
              <a class="fav-btn view" href="<?= APP_URL ?>/Home/addtocard/<?= $p['masp'] ?>"><i class="bi bi-cart-plus"></i> Giỏ</a>
              <button class="fav-btn remove btn-remove-fav" data-product="<?= $p['masp'] ?>" type="button"><i class="bi bi-x-circle"></i> Bỏ</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script>
// Remove favorite from list
const removeButtons = document.querySelectorAll('.btn-remove-fav');
removeButtons.forEach(btn => {
  btn.addEventListener('click', async () => {
    const code = btn.dataset.product;
    try {
      const res = await fetch(`<?= APP_URL ?>/Favorite/toggle/${code}`, {
        method: 'POST'
      });
      if (res.status === 401) {
        alert('Vui lòng đăng nhập để tiếp tục');
        window.location.href = '<?= APP_URL ?>/?url=Home/login';
        return;
      }
      const data = await res.json();
      if (data.success) {
        btn.closest('.fav-card').remove();
        if (document.querySelectorAll('.fav-card').length === 0) {
          location.reload();
        }
      } else {
        alert(data.message || 'Không thể cập nhật yêu thích');
      }
    } catch (e) {
      alert('Không thể cập nhật yêu thích');
    }
  });
});
</script>
