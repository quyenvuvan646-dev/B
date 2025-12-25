<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1">💬 Quản lý bình luận</h3>
            <p class="text-muted mb-0">Từ cấm sẽ tự động ẩn bình luận và báo vi phạm.</p>
        </div>
        <a href="<?= APP_URL ?>/Admin/comments" class="btn btn-outline-secondary btn-sm">Làm mới</a>
    </div>

    <!-- Banned words manager -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Từ ngữ bị cấm</div>
        <div class="card-body">
            <form class="row g-2 align-items-end" action="<?= APP_URL ?>/Admin/addBannedWord" method="POST">
                <div class="col-md-6">
                    <label class="form-label">Thêm từ/ cụm từ cấm</label>
                    <input type="text" name="word" class="form-control" placeholder="nhập từ cấm" required>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">Thêm</button>
                </div>
            </form>

            <div class="mt-3">
                <?php $banned = $data['bannedWords'] ?? []; ?>
                <?php if (empty($banned)): ?>
                    <div class="text-muted">Chưa có từ cấm.</div>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($banned as $w): ?>
                            <span class="badge bg-danger d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                                <?= htmlspecialchars($w) ?>
                                <a class="text-white text-decoration-none" href="<?= APP_URL ?>/Admin/deleteBannedWord/<?= urlencode($w) ?>" onclick="return confirm('Xóa từ cấm này?')">×</a>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Comments table -->
    <div class="card">
        <div class="card-header bg-dark text-white">Danh sách bình luận</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sản phẩm</th>
                            <th>User</th>
                            <th>Nội dung</th>
                            <th>Ngày</th>
                            <th>Hiển thị</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $comments = $data['comments'] ?? []; ?>
                        <?php if (empty($comments)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Chưa có bình luận.</td></tr>
                        <?php else: ?>
                            <?php foreach ($comments as $c): ?>
                                <tr>
                                    <td><?= $c['id'] ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($c['tensp'] ?? 'N/A') ?></div>
                                        <small class="text-muted">Mã: <?= htmlspecialchars($c['masp'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($c['user_name'] ?? 'Ẩn danh') ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($c['user_email'] ?? '') ?></small>
                                    </td>
                                    <td style="max-width: 320px;">
                                        <div class="text-truncate" title="<?= htmlspecialchars($c['content'] ?? '') ?>"><?= htmlspecialchars($c['content'] ?? '') ?></div>
                                        <?php if (!empty($c['rating'])): ?>
                                            <span class="badge bg-warning text-dark mt-1"><?= intval($c['rating']) ?>★</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><small class="text-muted"><?= htmlspecialchars($c['created_at'] ?? '') ?></small></td>
                                    <td>
                                        <?php if (!empty($c['is_visible'])): ?>
                                            <span class="badge bg-success">Đang hiển thị</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Đã ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="d-flex gap-2 flex-wrap">
                                        <?php if (!empty($c['is_visible'])): ?>
                                            <a class="btn btn-sm btn-outline-warning" href="<?= APP_URL ?>/Admin/setCommentVisible/<?= $c['id'] ?>/0">Ẩn</a>
                                        <?php else: ?>
                                            <a class="btn btn-sm btn-outline-success" href="<?= APP_URL ?>/Admin/setCommentVisible/<?= $c['id'] ?>/1">Hiển</a>
                                        <?php endif; ?>
                                        <a class="btn btn-sm btn-outline-danger" href="<?= APP_URL ?>/Admin/deleteComment/<?= $c['id'] ?>" onclick="return confirm('Xóa bình luận này?')">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><div class="container mt-4">
    <h2>Quản lý bình luận / đánh giá</h2>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Người viết</th>
                        <th>Nội dung</th>
                        <th>Rating</th>
                        <th>Hiển thị</th>
                        <th>Thời gian</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $commentModel = $this->model('CommentModel');
                    $comments = $commentModel->listAllComments();
                    foreach ($comments as $c) {
                        echo '<tr>';
                        echo '<td>' . $c['id'] . '</td>';
                        echo '<td>' . htmlspecialchars($c['tensp'] ?? $c['masp']) . '</td>';
                        echo '<td>' . htmlspecialchars($c['user_name'] ?: $c['user_email']) . '</td>';
                        echo '<td>' . nl2br(htmlspecialchars($c['content'])) . '</td>';
                        echo '<td>' . ($c['rating'] ?? '-') . '</td>';
                        echo '<td>' . ($c['is_visible'] ? 'Có' : 'Ẩn') . '</td>';
                        echo '<td>' . $c['created_at'] . '</td>';
                        echo '<td>';
                        echo '<a class="btn btn-sm btn-success me-1" href="' . APP_URL . '/Admin/setCommentVisible/' . $c['id'] . '/1">Hiện</a>';
                        echo '<a class="btn btn-sm btn-warning me-1" href="' . APP_URL . '/Admin/setCommentVisible/' . $c['id'] . '/0">Ẩn</a>';
                        echo '<a class="btn btn-sm btn-danger me-1" href="' . APP_URL . '/Admin/deleteComment/' . $c['id'] . '" onclick="return confirm(\'Xóa bình luận?\')">Xóa</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
