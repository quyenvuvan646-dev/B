<?php
$banners = $data['banners'] ?? [];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-1"><i class="bi bi-images"></i> Quản Lý Banner Slider</h2>
            <p class="text-muted">Thêm, sửa, xóa banner hiển thị trên trang chủ</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBannerModal">
                <i class="bi bi-plus-circle"></i> Thêm Banner Mới
            </button>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($banners)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-images" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Chưa có banner nào. Hãy thêm banner đầu tiên!</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="80">ID</th>
                                <th width="150">Ảnh Preview</th>
                                <th>Tiêu Đề</th>
                                <th>Link URL</th>
                                <th width="100" class="text-center">Thứ Tự</th>
                                <th width="100" class="text-center">Trạng Thái</th>
                                <th width="200" class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($banners as $banner): ?>
                                <tr>
                                    <td><?= $banner['id'] ?></td>
                                    <td>
                                        <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($banner['image_path']) ?>" 
                                             alt="Banner" 
                                             style="width: 100%; height: 60px; object-fit: cover; border-radius: 4px;"
                                             onerror="this.src='<?= APP_URL ?>/public/images/default.jpg'">
                                    </td>
                                    <td><?= htmlspecialchars($banner['title']) ?: '<i class="text-muted">Không có tiêu đề</i>' ?></td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($banner['link_url']) ?: '-' ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?= $banner['display_order'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($banner['is_active']): ?>
                                            <span class="badge bg-success">Hiển thị</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= APP_URL ?>/Admin/editBanner/<?= $banner['id'] ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="<?= APP_URL ?>/Admin/toggleBanner/<?= $banner['id'] ?>" style="display: inline;">
                                            <button type="submit" class="btn btn-sm btn-warning" title="Bật/Tắt hiển thị">
                                                <i class="bi bi-eye<?= $banner['is_active'] ? '-slash' : '' ?>"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= APP_URL ?>/Admin/deleteBanner/<?= $banner['id'] ?>" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Create Banner Modal -->
<div class="modal fade" id="createBannerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= APP_URL ?>/Admin/createBanner" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Thêm Banner Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu Đề <small class="text-muted">(tùy chọn)</small></label>
                        <input type="text" name="title" class="form-control" placeholder="Nhập tiêu đề banner">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh Banner <span class="text-danger">*</span></label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Khuyến nghị: 1920x600px, định dạng JPG/PNG</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link URL <small class="text-muted">(tùy chọn)</small></label>
                        <input type="text" name="link_url" class="form-control" placeholder="/Home/products">
                        <small class="text-muted">Đường dẫn khi click vào banner</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ Tự Hiển Thị</label>
                        <input type="number" name="display_order" class="form-control" value="0" min="0">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="createIsActive" checked>
                        <label class="form-check-label" for="createIsActive">Hiển thị ngay</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm Banner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Banner Modal -->
<div class="modal fade" id="editBannerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editBannerForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil"></i> Chỉnh Sửa Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Ảnh Hiện Tại</label>
                        <div>
                            <img id="editCurrentImage" src="" alt="Current Banner" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 4px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tiêu Đề <small class="text-muted">(tùy chọn)</small></label>
                        <input type="text" name="title" id="editTitle" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh Banner Mới <small class="text-muted">(để trống nếu không đổi)</small></label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link URL</label>
                        <input type="text" name="link_url" id="editLinkUrl" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ Tự Hiển Thị</label>
                        <input type="number" name="display_order" id="editDisplayOrder" class="form-control" min="0">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="editIsActive">
                        <label class="form-check-label" for="editIsActive">Hiển thị</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Fix modal z-index to appear above navbar (navbar is 1100) */
.modal {
    z-index: 1200 !important;
}

.modal-backdrop {
    z-index: 1150 !important;
}
</style>
