<div class="container mt-5">
    <?php if (!empty($_SESSION['comment_violation'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong><?= htmlspecialchars($_SESSION['comment_violation']) ?></strong>
            Bình luận của bạn đã bị ẩn và chờ kiểm duyệt.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['comment_violation']); ?>
    <?php endif; ?>
    <?php
    $p = $data["product"];
    if (!empty($p)) :
        $coKhuyenMai = !empty($p["phantram"]) && $p["phantram"] > 0;
        $giaGoc = (float)$p["giaXuat"];
        $giaSauKM = $coKhuyenMai ? $giaGoc * (1 - $p["phantram"] / 100) : $giaGoc;
    ?>

        <!-- 🕒 ĐỒNG HỒ ĐẾM NGƯỢC (chỉ hiện khi có khuyến mãi) -->
        <?php if ($coKhuyenMai): ?>
            <div id="countdown-box" class="alert alert-warning text-center shadow-sm fs-5 mb-4">
                <strong>Khuyến mãi kết thúc sau:</strong>
                <span id="countdown" class="fw-bold text-danger"></span>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const endTime = new Date("<?= date('Y-m-d H:i:s', strtotime($p['ngayketthuc'])) ?>").getTime();
                    const countdownEl = document.getElementById('countdown');
                    const box = document.getElementById('countdown-box');
                    const promoDateText = document.getElementById('promo-date'); // thêm ID cho đoạn ngày khuyến mãi

                    if (!countdownEl || !box) return;

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = endTime - now;

                        if (distance <= 0) {
                            box.style.display = "none";
                            if (promoDateText) promoDateText.style.display = "none"; // ẩn dòng "áp dụng từ...đến..."
                            clearInterval(timer);
                            return;
                        }

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        countdownEl.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                    }

                    updateCountdown();
                    const timer = setInterval(updateCountdown, 1000);
                });
            </script>
        <?php endif; ?>

        <div class="row">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-md-5 text-center">
                <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($p["hinhanh"]) ?>"
                    alt="<?= htmlspecialchars($p["tensp"]) ?>"
                    class="img-fluid rounded shadow-sm border"
                    style="max-height: 400px; object-fit: cover;">
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-7">
                <h2 class="fw-bold mb-3 text-uppercase"><?= htmlspecialchars($p["tensp"]) ?></h2>

                <p class="text-muted mb-1">
                    Mã sản phẩm: <strong><?= htmlspecialchars($p["masp"]) ?></strong>
                </p>
                <p class="mb-1">
                    Loại sản phẩm: <strong><?= htmlspecialchars($p["maLoaiSP"]) ?></strong>
                </p>
                <p class="mb-3">
                    Số lượng còn: <span class="text-success fw-bold"><?= (int)$p["soluong"] ?></span>
                </p>

                <!-- Giá sản phẩm -->
                <?php if ($coKhuyenMai): ?>
                    <div class="mb-3">
                        <p class="text-decoration-line-through text-secondary mb-1">
                            Giá gốc: <?= number_format($giaGoc, 0, ',', '.') ?> ₫
                        </p>
                        <p class="fs-4 fw-bold text-danger mb-1">
                            Giá khuyến mãi: <?= number_format($giaSauKM, 0, ',', '.') ?> ₫
                        </p>
                        <span class="badge bg-warning text-dark">
                            Giảm <?= htmlspecialchars($p["phantram"]) ?>%
                        </span>
                        <p id="promo-date" class="text-muted mt-2">
                            (Áp dụng từ <?= date("d/m/Y", strtotime($p["ngaybatdau"])) ?>
                            đến <?= date("d/m/Y", strtotime($p["ngayketthuc"])) ?>)
                        </p>
                    </div>
                <?php else: ?>
                    <p class="fs-4 fw-bold text-danger mb-3">
                        Giá bán: <?= number_format($giaGoc, 0, ',', '.') ?> ₫
                    </p>
                <?php endif; ?>

                <!-- Mô tả và thông tin thêm -->
                <p class="mt-3"><strong>Mô tả sản phẩm:</strong></p>
                <p class="text-justify"><?= nl2br(htmlspecialchars($p["mota"])) ?></p>

                <p class="text-muted">Ngày tạo: <?= htmlspecialchars($p["createDate"]) ?></p>

                <!-- Nút hành động -->
                <div class="mt-4 d-flex gap-3">
                    <a href="<?= APP_URL ?>/Home/addtocard/<?= urlencode($p["masp"]) ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-cart"></i> Thêm vào giỏ hàng
                    </a>
                    <a href="<?= APP_URL ?>/Home" class="btn btn-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- ================== Bình luận & Đánh giá ================== -->
        <div class="row mt-5">
            <div class="col-md-8">
                <h4>Đánh giá & Bình luận</h4>
                <?php
                $comments = $data['comments'] ?? [];
                $currentUserEmail = isset($_SESSION['user']) ? $_SESSION['user']['email'] : null;

                if (empty($comments)) {
                    echo '<div class="alert alert-secondary">Chưa có đánh giá nào cho sản phẩm này.</div>';
                } else {
                    // Lấy chỉ comment chính (parent_id = NULL)
                    $mainComments = array_filter($comments, function($c) { return empty($c['parent_id']); });
                    
                    foreach ($mainComments as $c) {
                        $isAuthor = $currentUserEmail && $c['user_email'] === $currentUserEmail;
                        echo '<div class="card mb-3" id="comment-' . $c['id'] . '">';
                        echo '<div class="card-body">';
                        echo '<div class="d-flex justify-content-between align-items-start">';
                        echo '<div>';
                        echo '<strong>' . htmlspecialchars($c['user_name'] ?: 'Khách') . '</strong>';
                        if (!empty($c['rating'])) echo ' <span class="badge bg-warning text-dark ms-2">' . intval($c['rating']) . '★</span>';
                        echo '<br><small class="text-muted">' . htmlspecialchars($c['created_at']) . '</small>';
                        echo '</div>';
                        if ($isAuthor) {
                            echo '<div class="btn-group btn-group-sm">';
                            echo '<button class="btn btn-sm btn-warning" onclick="toggleEditForm(' . $c['id'] . ')">Sửa</button>';
                            echo '<a href="' . APP_URL . '/Home/deleteComment/' . $c['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Xóa đánh giá này?\')">Xóa</a>';
                            echo '</div>';
                        }
                        echo '</div>';
                        
                        // Hiển thị ảnh
                        if (!empty($c['image'])) {
                            echo '<div class="mt-2"><img src="' . APP_URL . '/public/' . htmlspecialchars($c['image']) . '" style="max-width:200px; border-radius:4px;"></div>';
                        }
                        
                        echo '<p class="mt-2 mb-2" id="comment-content-' . $c['id'] . '">' . nl2br(htmlspecialchars($c['content'])) . '</p>';
                        
                        // Form sửa (ẩn mặc định)
                        echo '<div id="edit-form-' . $c['id'] . '" style="display:none; margin-top:10px;">';
                        echo '<form action="' . APP_URL . '/Home/updateComment" method="POST" enctype="multipart/form-data">';
                        echo '<input type="hidden" name="comment_id" value="' . $c['id'] . '">';
                        echo '<div class="mb-2">';
                        echo '<textarea name="content" class="form-control" rows="3" required>' . htmlspecialchars($c['content']) . '</textarea>';
                        echo '</div>';
                        echo '<div class="mb-2">';
                        echo '<label class="form-label">Ảnh (tùy chọn - để trống để giữ ảnh cũ)</label>';
                        echo '<input type="file" name="comment_image" class="form-control" accept="image/*">';
                        if (!empty($c['image'])) echo '<small class="text-muted d-block mt-1">Ảnh hiện tại: ' . htmlspecialchars(basename($c['image'])) . '</small>';
                        echo '</div>';
                        echo '<button class="btn btn-sm btn-success">Lưu</button>';
                        echo '<button type="button" class="btn btn-sm btn-secondary" onclick="toggleEditForm(' . $c['id'] . ')">Hủy</button>';
                        echo '</form>';
                        echo '</div>';
                        
                        // Lấy replies
                        $replies = array_filter($comments, function($r) use ($c) { return $r['parent_id'] == $c['id']; });
                        if (!empty($replies)) {
                            echo '<div class="mt-3 ps-3 border-start">';
                            foreach ($replies as $r) {
                                $isReplyAuthor = $currentUserEmail && $r['user_email'] === $currentUserEmail;
                                echo '<div class="mb-2 p-2 bg-light rounded">';
                                echo '<div class="d-flex justify-content-between">';
                                echo '<div>';
                                echo '<strong>' . htmlspecialchars($r['user_name'] ?: 'Khách') . '</strong>';
                                echo '<br><small class="text-muted">' . htmlspecialchars($r['created_at']) . '</small>';
                                echo '</div>';
                                if ($isReplyAuthor) {
                                    echo '<a href="' . APP_URL . '/Home/deleteComment/' . $r['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Xóa trả lời này?\')">Xóa</a>';
                                }
                                echo '</div>';
                                if (!empty($r['image'])) {
                                    echo '<div class="mt-1"><img src="' . APP_URL . '/public/' . htmlspecialchars($r['image']) . '" style="max-width:150px; border-radius:4px;"></div>';
                                }
                                echo '<p class="mt-1 mb-0">' . nl2br(htmlspecialchars($r['content'])) . '</p>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        
                        // Nút reply (chỉ tác giả comment chính)
                        if ($isAuthor && empty($data['hasReviewed'])) {
                            echo '<div class="mt-2"><button class="btn btn-sm btn-info" onclick="toggleReplyForm(' . $c['id'] . ')">Trả lời</button></div>';
                        }
                        
                        // Form reply
                        echo '<div id="reply-form-' . $c['id'] . '" style="display:none; margin-top:10px;">';
                        echo '<form action="' . APP_URL . '/Home/replyToComment" method="POST" enctype="multipart/form-data">';
                        echo '<input type="hidden" name="parent_id" value="' . $c['id'] . '">';
                        echo '<div class="mb-2">';
                        echo '<textarea name="content" class="form-control" rows="3" placeholder="Trả lời..." required></textarea>';
                        echo '</div>';
                        echo '<div class="mb-2">';
                        echo '<label class="form-label">Ảnh (tùy chọn)</label>';
                        echo '<input type="file" name="comment_image" class="form-control" accept="image/*">';
                        echo '</div>';
                        echo '<button class="btn btn-sm btn-success">Gửi</button>';
                        echo '<button type="button" class="btn btn-sm btn-secondary" onclick="toggleReplyForm(' . $c['id'] . ')">Hủy</button>';
                        echo '</form>';
                        echo '</div>';
                        
                        echo '</div></div>';
                    }
                }
                ?>
            </div>

            <div class="col-md-4">
                <?php if (!empty($data['canReview'])): ?>
                    <div class="card p-3">
                        <h5>Gửi đánh giá của bạn</h5>
                        <form action="<?= APP_URL ?>/Home/submitComment" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="masp" value="<?= htmlspecialchars($p['masp']) ?>">
                            <div class="mb-2">
                                <label class="form-label">Đánh giá (sao)</label>
                                <select name="rating" class="form-select" required>
                                    <option value="5">5 - Tuyệt vời</option>
                                    <option value="4">4 - Tốt</option>
                                    <option value="3">3 - Trung bình</option>
                                    <option value="2">2 - Kém</option>
                                    <option value="1">1 - Rất tệ</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Bình luận</label>
                                <textarea name="content" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Ảnh (tùy chọn)</label>
                                <input type="file" name="comment_image" class="form-control" accept="image/*">
                            </div>
                            <button class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    </div>
                <?php elseif (!empty($data['hasReviewed'])): ?>
                    <div class="alert alert-info">Bạn đã đánh giá sản phẩm này. Cảm ơn bạn!</div>
                <?php else: ?>
                    <div class="alert alert-secondary">Bạn chỉ có thể đánh giá nếu đã mua sản phẩm.</div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-danger">Không tìm thấy sản phẩm!</div>
    <?php endif; ?>
</div>

<script>
function toggleEditForm(commentId) {
    const form = document.getElementById('edit-form-' + commentId);
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
}

function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
}
</script>
