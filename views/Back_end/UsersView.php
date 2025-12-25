<div class="container mt-4">
    <h2>Quản lý người dùng</h2>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Phân quyền</th>
                        <th>Đã xác minh</th>
                        <th>Trạng thái</th>
                        <th>Đơn hàng</th>
                        <th>Doanh thu</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $userModel = $this->model('UserModel');
                    $users = $userModel->allUsers();
                    foreach ($users as $u) {
                        // fallback keys to adapt to various schema names
                        $userEmail = $u['email'] ?? $u['user_email'] ?? null;
                        $userFullname = $u['fullname'] ?? $u['name'] ?? $u['user_name'] ?? $userEmail;
                        $userId = $u['id'] ?? $u['user_id'] ?? $userEmail;
                        $isVerified = $u['is_verified'] ?? $u['verified'] ?? 0;
                        $isLocked = $u['is_locked'] ?? $u['locked'] ?? 0;

                        $stats = $userEmail ? $userModel->getUserOrderStats($userEmail) : ['order_count' => 0, 'total_revenue' => 0];

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($userId) . '</td>';
                        echo '<td>' . htmlspecialchars($userFullname) . '</td>';
                        echo '<td>' . htmlspecialchars($userEmail) . '</td>';

                        // Phân quyền (Roles)
                        echo '<td>';
                        $hasNumericId = is_numeric($userId);
                        $uidAttr = $hasNumericId ? (int)$userId : '';
                        $emailAttr = htmlspecialchars($userEmail ?? '');
                        echo '<select style="min-width:140px;max-width:240px;font-size:0.85rem;" class="form-select form-select-sm user-roles-select" data-userid="' . $uidAttr . '" data-useremail="' . $emailAttr . '"></select>';
                        echo '<button class="btn btn-sm btn-primary mt-1 save-roles-btn" data-userid="' . $uidAttr . '" data-useremail="' . $emailAttr . '">Lưu</button>';
                        if (!$hasNumericId) echo '<div class="small text-muted mt-1">ID không tồn tại; phân quyền sẽ dùng email.</div>';
                        echo '</td>';

                        echo '<td>' . ($isVerified ? 'Có' : 'Không') . '</td>';
                        echo '<td>' . ($isLocked ? '<span class="badge bg-danger">Khóa</span>' : '<span class="badge bg-success">Hoạt động</span>') . '</td>';

                        echo '<td>' . ($stats['order_count'] ?? 0) . '</td>';
                        echo '<td>' . number_format($stats['total_revenue'] ?? 0, 0, ',', '.') . ' đ</td>';

                        // Hành động
                        echo '<td>';
                        if ($userEmail) {
                            echo '<div class="dropdown">';
                            echo '<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownUser' . htmlspecialchars($userId) . '" data-bs-toggle="dropdown">Hành động</button>';
                            echo '<ul class="dropdown-menu">';
                            echo '<li><a class="dropdown-item" href="' . APP_URL . '/Admin/customerDetail/' . urlencode($userEmail) . '">👁️ Xem chi tiết</a></li>';
                            echo '<li><hr class="dropdown-divider"></li>';
                            echo '<li><a class="dropdown-item" href="' . APP_URL . '/Admin/lockUser/' . urlencode($userEmail) . '/1">🔒 Khóa tài khoản</a></li>';
                            echo '<li><a class="dropdown-item" href="' . APP_URL . '/Admin/lockUser/' . urlencode($userEmail) . '/0">🔓 Mở khóa tài khoản</a></li>';
                            echo '<li><hr class="dropdown-divider"></li>';
                            echo '<li><a class="dropdown-item text-danger" href="' . APP_URL . '/Admin/deleteUser/' . urlencode($userEmail) . '" onclick="return confirm(\'Xác nhận xóa user này?\')">🗑️ Xóa tài khoản</a></li>';
                            echo '</ul>';
                            echo '</div>';
                        } else {
                            echo '<small class="text-muted">Không có email</small>';
                        }
                        echo '</td>';

                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function(){

    function loadRoles(){
        fetch("<?php echo APP_URL; ?>/?url=Admin/getRoles")
        .then(r => r.json())
        .then(roles => {
            if (!roles || !Array.isArray(roles)) return;

            const options = roles.map(role =>
                `<option value="${role.id}">${role.name || role.slug}</option>`
            ).join('');

            document.querySelectorAll('.user-roles-select').forEach(sel => {
                sel.innerHTML = options;

                const uid = sel.dataset.userid;
                const uemail = sel.dataset.useremail;

                const lookup = (uid && /^\d+$/.test(uid)) ? uid : encodeURIComponent(uemail);

                fetch("<?php echo APP_URL; ?>/?url=Admin/getUserRoles/" + lookup)
                .then(r => r.json())
                .then(userRoles => {
                    const ids = (userRoles || []).map(r => String(r.id));
                    [...sel.options].forEach(opt => {
                        opt.selected = ids.includes(opt.value);
                    });
                });
            });
        });
    }

    // SAVE ROLES
    document.addEventListener("click", function(e){
        if(!e.target.classList.contains("save-roles-btn")) return;

        const uid = e.target.dataset.userid;
        const uemail = e.target.dataset.useremail;

        let sel = null;
        if (uid && /^\d+$/.test(uid)) {
            sel = document.querySelector(`.user-roles-select[data-userid="${uid}"]`);
        }
        if (!sel && uemail) {
            sel = document.querySelector(`.user-roles-select[data-useremail="${uemail}"]`);
        }
        if (!sel) return alert("Không tìm thấy select roles!");

        const selected = sel.value ? [sel.value] : [];

        const form = new FormData();
        if (uid && /^\d+$/.test(uid)) form.append("user_id", uid);
        else form.append("user_email", uemail);

        form.append("roles", JSON.stringify(selected));

        fetch("<?php echo APP_URL; ?>/?url=Admin/assignRoles", {
            method: "POST",
            body: form
        })
        .then(r => r.json())
        .then(resp => {
            if (resp && resp.success) {
                alert("Đã lưu roles");
            } else {
                alert("Lưu roles không thành công");
            }
        })
        .catch(err => {
            console.error("Fetch error:", err);
            alert("Lỗi mạng: " + err.message);
        });
    });

    // script ở cuối -> DOM đã load -> gọi trực tiếp
    loadRoles();

})();
</script>
