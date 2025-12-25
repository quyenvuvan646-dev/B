<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$user = $user ?? [];
$contract = $contract ?? null;
$existing = $existing ?? false;
?>

<style>
    :root {
        --gradient-dark: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        --gradient-blue: linear-gradient(135deg, #1e3a5f 0%, #3b5998 100%);
        --border-color: #334155;
        --text-light: #f1f5f9;
        --text-muted: #94a3b8;
    }

    .register-container {
        min-height: calc(100vh - 80px);
        background: var(--gradient-dark);
        padding: 40px 20px;
    }

    .register-card {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(30, 58, 95, 0.6));
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 40px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
    }

    .register-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .register-header h1 {
        color: var(--text-light);
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .register-header p {
        color: var(--text-muted);
        font-size: 16px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        color: var(--text-light);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-light);
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
        background: rgba(15, 23, 42, 0.8);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", sans-serif;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .section-title {
        color: var(--text-light);
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border-color);
    }

    /* Terms Section */
    .terms-section {
        margin-bottom: 30px;
        padding: 20px;
        background: rgba(15, 23, 42, 0.3);
        border: 1px solid var(--border-color);
        border-radius: 8px;
    }

    .terms-title {
        color: var(--text-light);
        font-weight: 600;
        margin-bottom: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .terms-title i {
        transition: transform 0.3s ease;
    }

    .terms-title.collapsed i {
        transform: rotate(-90deg);
    }

    .terms-content {
        max-height: 400px;
        overflow-y: auto;
        padding: 16px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        margin-bottom: 12px;
        color: var(--text-muted);
        font-size: 13px;
        line-height: 1.6;
        display: none;
    }

    .terms-content.show {
        display: block;
    }

    .terms-content strong {
        color: var(--text-light);
    }

    .terms-content ol,
    .terms-content ul {
        margin: 10px 0 10px 20px;
    }

    .terms-content li {
        margin-bottom: 8px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-group input[type="checkbox"] {
        width: auto;
        margin: 0;
        cursor: pointer;
        width: 20px;
        height: 20px;
        accent-color: #3b82f6;
    }

    .checkbox-group label {
        margin: 0;
        font-weight: 400;
        cursor: pointer;
        color: var(--text-light);
    }

    /* File Upload */
    .file-upload-group {
        position: relative;
    }

    .file-upload-input {
        display: none;
    }

    .file-upload-label {
        display: block;
        padding: 20px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
        border: 2px dashed #3b82f6;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-muted);
    }

    .file-upload-label:hover {
        border-color: #60a5fa;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.1));
    }

    .file-upload-label i {
        display: block;
        font-size: 32px;
        margin-bottom: 8px;
        color: #3b82f6;
    }

    .file-name {
        color: var(--text-light);
        margin-top: 8px;
        font-weight: 500;
    }

    /* Buttons */
    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-submit {
        flex: 1;
        padding: 14px 24px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 15px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
    }

    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-cancel {
        flex: 1;
        padding: 14px 24px;
        background: transparent;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-light);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 15px;
    }

    .btn-cancel:hover {
        border-color: var(--text-muted);
        color: var(--text-muted);
    }

    /* Status Messages */
    .status-message {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .status-message.success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #86efac;
    }

    .status-message.error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
    }

    .status-message i {
        font-size: 18px;
    }

    /* Existing Application Status */
    .existing-application {
        padding: 24px;
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 8px;
        margin-top: 20px;
    }

    .existing-application h3 {
        color: #86efac;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.pending {
        background: rgba(251, 146, 60, 0.2);
        color: #fdba74;
    }

    .status-badge.approved {
        background: rgba(34, 197, 94, 0.2);
        color: #86efac;
    }

    .status-badge.rejected {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid rgba(34, 197, 94, 0.2);
        color: var(--text-muted);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item strong {
        color: var(--text-light);
    }
</style>

<div class="register-container">
    <div class="register-card">
        <!-- Header -->
        <div class="register-header">
            <h1><i class="bi bi-building"></i> Đăng Ký Kinh Doanh</h1>
            <p>Trở thành nhà phân phối chính thức của chúng tôi</p>
        </div>

        <!-- Status Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="status-message success" id="statusMessage">
                <i class="bi bi-check-circle"></i>
                <div><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="status-message error" id="statusMessage">
                <i class="bi bi-exclamation-circle"></i>
                <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            </div>
        <?php endif; ?>

        <!-- Existing Application Status -->
        <?php if ($existing && $contract): ?>
            <div class="existing-application">
                <h3>
                    <i class="bi bi-hourglass-split"></i>
                    Đơn Đăng Ký Của Bạn
                </h3>
                <div class="info-item">
                    <strong>Trạng Thái:</strong>
                    <span class="status-badge <?php echo strtolower($contract['status']); ?>">
                        <?php
                        $statusText = [
                            'pending' => 'Chờ Duyệt',
                            'approved' => 'Đã Duyệt',
                            'rejected' => 'Bị Từ Chối'
                        ];
                        echo $statusText[$contract['status']] ?? $contract['status'];
                        ?>
                    </span>
                </div>
                <div class="info-item">
                    <strong>Công Ty:</strong>
                    <span><?php echo htmlspecialchars($contract['company_name']); ?></span>
                </div>
                <div class="info-item">
                    <strong>Ngày Đăng Ký:</strong>
                    <span><?php echo date('d/m/Y H:i', strtotime($contract['created_at'])); ?></span>
                </div>
                <?php if ($contract['admin_notes']): ?>
                    <div class="info-item">
                        <strong>Ghi Chú Admin:</strong>
                        <span><?php echo htmlspecialchars($contract['admin_notes']); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Registration Form -->
            <form action="<?php echo APP_URL; ?>/Home/submitDistributorRegistration" method="POST" enctype="multipart/form-data" id="registrationForm">
                <!-- Section 1: Thông Tin Cơ Bản -->
                <div class="section-title">
                    <i class="bi bi-person"></i> Thông Tin Cơ Bản
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Họ Tên Đầy Đủ *</label>
                        <input 
                            type="text" 
                            id="full_name" 
                            name="full_name" 
                            placeholder="Nhập họ tên" 
                            required
                            minlength="3"
                        >
                    </div>
                    <div class="form-group">
                        <label for="phone">Số Điện Thoại *</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            placeholder="0123456789 hoặc +84..." 
                            required
                        >
                        <small style="display: block; margin-top: 5px; color: var(--text-muted);">
                            Định dạng: 0123456789 hoặc +84123456789
                        </small>
                    </div>
                </div>

                <!-- Section 2: Thông Tin Công Ty -->
                <div class="section-title" style="margin-top: 30px;">
                    <i class="bi bi-briefcase"></i> Thông Tin Công Ty
                </div>

                <div class="form-group">
                    <label for="company_name">Tên Công Ty *</label>
                    <input 
                        type="text" 
                        id="company_name" 
                        name="company_name" 
                        placeholder="Tên công ty của bạn" 
                        required
                        minlength="3"
                    >
                </div>

                <div class="form-group">
                    <label for="business_address">Địa Chỉ Kinh Doanh *</label>
                    <textarea 
                        id="business_address" 
                        name="business_address" 
                        placeholder="Nhập địa chỉ kinh doanh đầy đủ" 
                        required
                        minlength="10"
                    ></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tax_id">Mã Số Thuế</label>
                        <input 
                            type="text" 
                            id="tax_id" 
                            name="tax_id" 
                            placeholder="Mã số thuế (nếu có)"
                        >
                    </div>
                    <div class="form-group">
                        <label for="business_license">Số Giấy Phép Kinh Doanh</label>
                        <input 
                            type="text" 
                            id="business_license" 
                            name="business_license" 
                            placeholder="Số giấy phép (nếu có)"
                        >
                    </div>
                </div>

                <!-- Section 3: Tài Liệu -->
                <div class="section-title" style="margin-top: 30px;">
                    <i class="bi bi-file-earmark"></i> Tài Liệu Hợp Đồng
                </div>

                <div class="form-group file-upload-group">
                    <label class="file-upload-label" for="contract_file">
                        <i class="bi bi-cloud-upload"></i>
                        <div>Tải Lên Hợp Đồng / Giấy Phép</div>
                        <small style="color: var(--text-muted);">PDF, DOC, DOCX (tối đa 5MB)</small>
                    </label>
                    <input 
                        type="file" 
                        id="contract_file" 
                        name="contract_file" 
                        class="file-upload-input"
                        accept=".pdf,.doc,.docx"
                    >
                    <div class="file-name" id="fileName"></div>
                </div>

                <!-- Section 4: Điều Lệ & Điều Kiện -->
                <div class="section-title" style="margin-top: 30px;">
                    <i class="bi bi-clipboard-check"></i> Điều Lệ & Điều Kiện
                </div>

                <div class="terms-section">
                    <div class="terms-title collapsed" onclick="toggleTerms(this)">
                        <i class="bi bi-chevron-right"></i>
                        <span>Xem Điều Lệ Hợp Tác Phân Phối</span>
                    </div>
                    <div class="terms-content" id="termsContent">
                        <strong>ĐIỀU LỆ HỢP TÁC PHÂN PHỐI</strong>
                        <p style="margin-top: 10px;">Hiệu lực từ ngày 01/01/2025</p>

                        <ol>
                            <li><strong>Quyền Và Nghĩa Vụ Của Nhà Phân Phối</strong>
                                <ul>
                                    <li>Nhà phân phối cam kết bán các sản phẩm theo giá bán lẻ đề xuất</li>
                                    <li>Duy trì chất lượng dịch vụ khách hàng cao nhất</li>
                                    <li>Tuân thủ các chính sách marketing của công ty</li>
                                    <li>Báo cáo doanh số bán hàng hàng tháng</li>
                                </ul>
                            </li>
                            <li><strong>Hỗ Trợ Của Công Ty</strong>
                                <ul>
                                    <li>Cung cấp chiết khấu phân phối cạnh tranh</li>
                                    <li>Hỗ trợ marketing và quảng cáo</li>
                                    <li>Cung cấp tài liệu sản phẩm và đào tạo</li>
                                    <li>Hỗ trợ kỹ thuật 24/7</li>
                                </ul>
                            </li>
                            <li><strong>Thanh Toán</strong>
                                <ul>
                                    <li>Điều khoản thanh toán sẽ được thống nhất trong hợp đồng chi tiết</li>
                                    <li>Được phép từ 30-60 ngày để thanh toán tuỳ theo thỏa thuận</li>
                                    <li>Hỗ trợ tài chính cho các đơn hàng lớn</li>
                                </ul>
                            </li>
                            <li><strong>Kỳ Hạn Hợp Đồng</strong>
                                <ul>
                                    <li>Thời gian hợp đồng ban đầu: 1 năm</li>
                                    <li>Tự động gia hạn hàng năm nếu cả hai bên đồng ý</li>
                                    <li>Có thể chấm dứt bất cứ lúc nào với thông báo 30 ngày</li>
                                </ul>
                            </li>
                            <li><strong>Bảo Mật Và Bảo Vệ Dữ Liệu</strong>
                                <ul>
                                    <li>Bảo vệ thông tin khách hàng theo quy định pháp luật</li>
                                    <li>Không chia sẻ dữ liệu với bên thứ ba</li>
                                    <li>Tuân thủ GDPR và các quy định bảo mật khác</li>
                                </ul>
                            </li>
                            <li><strong>Giải Quyết Tranh Chấp</strong>
                                <ul>
                                    <li>Các tranh chấp sẽ được giải quyết thông qua thương lượng</li>
                                    <li>Nếu không thể thương lượng, sẽ chuyển sang trọng tài</li>
                                    <li>Các tranh chấp chịu sự điều chỉnh của pháp luật Việt Nam</li>
                                </ul>
                            </li>
                        </ol>

                        <p style="margin-top: 15px; color: var(--text-muted);">
                            <strong>Lưu ý:</strong> Bằng việc ký dưới đây, bạn xác nhận rằng bạn đã đọc, hiểu và đồng ý 
                            tuân thủ tất cả các điều khoản và điều kiện trong hợp đồng này.
                        </p>
                    </div>
                </div>

                <div class="checkbox-group" style="margin-top: 16px;">
                    <input 
                        type="checkbox" 
                        id="terms_accepted" 
                        name="terms_accepted" 
                        required
                    >
                    <label for="terms_accepted">
                        Tôi đã đọc và chấp nhận <strong>Điều Lệ Hợp Tác Phân Phối</strong> *
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <a href="<?php echo APP_URL; ?>/Home/show" class="btn-cancel">Hủy Bỏ</a>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Gửi Đơn Đăng Ký
                    </button>
                </div>

                <small style="display: block; text-align: center; color: var(--text-muted); margin-top: 20px;">
                    * Là các trường bắt buộc
                </small>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleTerms(element) {
    element.classList.toggle('collapsed');
    document.getElementById('termsContent').classList.toggle('show');
}

document.getElementById('contract_file')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileNameEl = document.getElementById('fileName');
    
    if (file) {
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            fileNameEl.textContent = '❌ File quá lớn (tối đa 5MB)';
            fileNameEl.style.color = '#fca5a5';
            e.target.value = '';
        } else {
            fileNameEl.textContent = '✓ ' + file.name;
            fileNameEl.style.color = '#86efac';
        }
    } else {
        fileNameEl.textContent = '';
    }
});

// Add client-side validation and show helpful messages
document.getElementById('registrationForm')?.addEventListener('submit', function(e) {
    const phone = document.getElementById('phone')?.value?.trim() || '';
    const phoneError = document.getElementById('phoneError');
    
    // Remove previous error if exists
    if (phoneError) phoneError.remove();
    
    // Validate phone format
    if (phone && !phone.match(/^(\+?84|0)[0-9]{9,10}$/)) {
        e.preventDefault();
        
        // Show error message
        const phoneInput = document.getElementById('phone');
        const errorDiv = document.createElement('div');
        errorDiv.id = 'phoneError';
        errorDiv.style.cssText = 'color: #fca5a5; font-size: 0.85rem; margin-top: 5px;';
        errorDiv.textContent = '⚠️  Số điện thoại không hợp lệ. Vui lòng nhập: 0123456789 hoặc +84123456789';
        phoneInput.parentElement.appendChild(errorDiv);
        
        // Scroll to phone field
        phoneInput.focus();
        phoneInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    // Log submission for debugging
    console.log('Form submitting with valid data');
});

// Scroll to status message if it exists (after page reload following submit)
window.addEventListener('load', function() {
    const statusMessage = document.getElementById('statusMessage');
    if (statusMessage) {
        // Small delay to ensure DOM is ready
        setTimeout(function() {
            statusMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    }
});
</script>
