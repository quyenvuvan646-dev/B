<div class="container mt-5">
    <h1 style="margin-bottom: 30px;">📋 Quản Lý Hợp Đồng Phân Phối</h1>

    <!-- Status Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
            <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
            <i class="bi bi-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="contractTabs" role="tablist" style="margin-bottom: 25px;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                <i class="bi bi-hourglass-split"></i> Chờ Duyệt
                <span class="badge bg-warning ms-2"><?php echo count($pendingContracts ?? []); ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">
                <i class="bi bi-check-circle"></i> Đã Duyệt
                <span class="badge bg-success ms-2"><?php echo count($approvedContracts ?? []); ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">
                <i class="bi bi-x-circle"></i> Từ Chối
                <span class="badge bg-danger ms-2"><?php echo count($rejectedContracts ?? []); ?></span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="contractTabContent">
        
        <!-- PENDING CONTRACTS -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <?php if (!empty($pendingContracts)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Tên Đầy Đủ</th>
                                <th>Công Ty</th>
                                <th>Số ĐT</th>
                                <th>Ngày Đăng Ký</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingContracts as $contract): ?>
                                <tr>
                                    <td><strong>#<?php echo $contract['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($contract['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($contract['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($contract['company_name']); ?></td>
                                    <td><?php echo htmlspecialchars($contract['phone']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($contract['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo APP_URL; ?>/Admin/viewContract/<?php echo $contract['id']; ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Xem Chi Tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Không có hợp đồng chờ duyệt
                </div>
            <?php endif; ?>
        </div>

        <!-- APPROVED CONTRACTS -->
        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
            <?php if (!empty($approvedContracts)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Tên Đầy Đủ</th>
                                <th>Công Ty</th>
                                <th>Ngày Duyệt</th>
                                <th>Ghi Chú</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($approvedContracts as $contract): ?>
                                <tr>
                                    <td><strong>#<?php echo $contract['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($contract['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($contract['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($contract['company_name']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($contract['updated_at'])); ?></td>
                                    <td>
                                        <small><?php echo htmlspecialchars(substr($contract['admin_notes'] ?? '', 0, 50)); ?></small>
                                    </td>
                                    <td>
                                        <a href="<?php echo APP_URL; ?>/Admin/viewContract/<?php echo $contract['id']; ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Xem Chi Tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Chưa có hợp đồng đã duyệt
                </div>
            <?php endif; ?>
        </div>

        <!-- REJECTED CONTRACTS -->
        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
            <?php if (!empty($rejectedContracts)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Tên Đầy Đủ</th>
                                <th>Công Ty</th>
                                <th>Ngày Từ Chối</th>
                                <th>Lý Do</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rejectedContracts as $contract): ?>
                                <tr>
                                    <td><strong>#<?php echo $contract['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($contract['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($contract['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($contract['company_name']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($contract['updated_at'])); ?></td>
                                    <td>
                                        <small><?php echo htmlspecialchars(substr($contract['admin_notes'] ?? '', 0, 50)); ?></small>
                                    </td>
                                    <td>
                                        <a href="<?php echo APP_URL; ?>/Admin/viewContract/<?php echo $contract['id']; ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Xem Chi Tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Chưa có hợp đồng bị từ chối
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #0d6efd;
    }
    
    .btn-sm {
        padding: 0.35rem 0.6rem;
        font-size: 0.875rem;
    }
</style>
