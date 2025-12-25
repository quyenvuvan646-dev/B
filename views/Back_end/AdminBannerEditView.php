<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Banner</title>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-image"></i> Sửa Banner
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form action="<?= APP_URL ?>/Admin/updateBanner/<?= $banner['id'] ?>" method="POST" enctype="multipart/form-data">
                        <!-- Current Image Preview -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Ảnh hiện tại:</label>
                            <div class="text-center p-3 border rounded bg-light">
                                <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($banner['image_path']) ?>" 
                                     alt="Banner" 
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 300px; object-fit: contain;">
                            </div>
                        </div>

                        <!-- New Image Upload -->
                        <div class="mb-3">
                            <label for="banner_image" class="form-label fw-bold">
                                Ảnh mới (để trống nếu không đổi):
                            </label>
                            <input type="file" 
                                   class="form-control" 
                                   id="banner_image" 
                                   name="banner_image" 
                                   accept="image/*">
                            <div class="form-text">Định dạng: JPG, PNG, GIF. Kích thước đề xuất: 1920x600px</div>
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">
                                Tiêu đề: <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   value="<?= htmlspecialchars($banner['title']) ?>" 
                                   required>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?= APP_URL ?>/Admin/banners" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.5rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.form-label.fw-bold {
    color: #333;
    margin-bottom: 0.5rem;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 0.5rem 1.5rem;
    transition: transform 0.2s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    padding: 0.5rem 1.5rem;
    transition: transform 0.2s;
}

.btn-secondary:hover {
    transform: translateY(-2px);
}

.border.rounded.bg-light {
    background-color: #f8f9fa !important;
}

.img-fluid.rounded.shadow-sm {
    transition: transform 0.3s ease;
}

.img-fluid.rounded.shadow-sm:hover {
    transform: scale(1.02);
}
</style>

</body>
</html>
