<style>
    .checkout-container { background:linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius:20px; padding:40px; box-shadow:0 20px 60px rgba(15,23,42,.5); }
    .checkout-header { margin-bottom:40px; text-align:center; }
    .checkout-header h1 { font-size:32px; font-weight:700; color:#fff; margin:0; letter-spacing:-0.5px; }
    .checkout-header p { color:#94a3b8; margin:10px 0 0 0; font-size:14px; }
    .checkout-grid { display:grid; grid-template-columns:1fr 1fr; gap:40px; align-items:start; }
    .form-section { background:linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); border:1px solid #334155; border-radius:16px; padding:28px; }
    .form-section h3 { font-size:16px; font-weight:700; color:#93c5fd; margin:0 0 20px 0; text-transform:uppercase; letter-spacing:0.05em; border-bottom:2px solid #3b82f6; padding-bottom:12px; }
    .form-group { margin-bottom:20px; }
    .form-label { display:block; font-size:13px; font-weight:600; color:#cbd5e1; text-transform:uppercase; margin-bottom:8px; letter-spacing:0.05em; }
    .form-control { background:#0f172a; border:1px solid #334155; color:#f1f5f9; padding:12px 16px; border-radius:8px; font-size:14px; transition:all 0.3s ease; width:100%; }
    .form-control:focus { border-color:#3b82f6; background:#1e293b; box-shadow:0 0 0 3px rgba(59,130,246,.1); color:#f1f5f9; outline:none; }
    .form-control::placeholder { color:#64748b; }
    .form-group select { width:100%; }
    .form-group input[type="radio"] { margin-right:8px; }
    .form-group label { display:flex; align-items:center; font-size:14px; color:#f1f5f9; cursor:pointer; margin-bottom:10px; }
    .form-check-input { width:20px; height:20px; cursor:pointer; }
    .map-section { background:#0f172a; border:1px solid #334155; border-radius:12px; padding:16px; margin-top:12px; }
    .map-frame { width:100%; height:300px; border:1px solid #334155; border-radius:8px; }
    .map-info { font-size:13px; color:#cbd5e1; margin-top:12px; }
    .map-info strong { color:#93c5fd; }
    .address-input-group { display:flex; gap:8px; }
    .address-input-group input { flex:1; }
    .address-input-group button { padding:12px 20px; }
    .btn-map { background:linear-gradient(135deg, #3b82f6, #1d4ed8); color:#fff; border:none; font-weight:600; padding:10px 18px; border-radius:8px; cursor:pointer; transition:all 0.3s ease; }
    .btn-map:hover { opacity:0.95; box-shadow:0 4px 12px rgba(59,130,246,.3); }
    .summary-card { background:linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); border:2px solid #3b82f6; border-radius:16px; padding:28px; position:sticky; top:20px; }
    .summary-title { font-size:18px; font-weight:700; color:#93c5fd; margin:0 0 24px 0; display:flex; align-items:center; gap:8px; }
    .summary-row { display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid #334155; font-size:14px; color:#f1f5f9; }
    .summary-row:last-child { border-bottom:none; }
    .summary-row strong { color:#cbd5e1; font-weight:600; }
    .summary-value { color:#93c5fd; font-weight:600; }
    .summary-value.discount { color:#10b981; }
    .summary-total { display:flex; justify-content:space-between; padding:20px 0; margin:20px 0; border-top:2px solid #3b82f6; border-bottom:2px solid #3b82f6; font-size:20px; font-weight:700; }
    .summary-total .label { color:#f1f5f9; }
    .summary-total .amount { color:#6ee7b7; font-size:24px; }
    .shipping-info { background:linear-gradient(135deg, rgba(59,130,246,.1), rgba(30,58,95,.2)); border:1px solid rgba(59,130,246,.3); border-radius:8px; padding:16px; margin-top:12px; font-size:13px; color:#cbd5e1; display:none; }
    .shipping-info.active { display:block; }
    .shipping-row { display:flex; justify-content:space-between; margin:8px 0; }
    .shipping-row strong { color:#93c5fd; }
    .btn-checkout { background:linear-gradient(135deg, #10b981, #059669); color:#fff; border:none; font-weight:700; padding:16px 32px; border-radius:12px; font-size:16px; width:100%; cursor:pointer; transition:all 0.3s ease; margin-top:24px; text-transform:uppercase; letter-spacing:0.05em; }
    .btn-checkout:hover { opacity:0.95; box-shadow:0 8px 20px rgba(16,185,129,.3); transform:translateY(-2px); }
    .btn-checkout:active { transform:translateY(0); }
    .empty-state { text-align:center; color:#94a3b8; padding:40px 20px; }
    @media (max-width:1024px) { .checkout-grid { grid-template-columns:1fr; } .summary-card { position:static; } }
    .section-divider { height:1px; background:linear-gradient(90deg, transparent, #334155, transparent); margin:24px 0; }
    .container { max-width:1400px; margin:40px auto; padding:0 20px; }
</style>

<div class="container">
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>🛒 Hoàn Tất Đơn Hàng</h1>
            <p>Kiểm tra thông tin và xác nhận thanh toán</p>
        </div>

        <form action="<?php echo APP_URL; ?>/Home/checkoutSave" method="POST">
            <div class="checkout-grid">
                <!-- Left: Form Section -->
                <div>
                    <!-- Delivery Info -->
                    <div class="form-section">
                        <h3>📍 Thông Tin Giao Hàng</h3>
                        
                        <div class="form-group">
                            <label class="form-label">Tên người nhận</label>
                            <input type="text" class="form-control" id="receiver" name="receiver" 
                                value="<?php echo isset($_SESSION['user']['fullname']) ? htmlspecialchars($_SESSION['user']['fullname']) : ''; ?>" 
                                required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?php echo isset($_SESSION['user']['email']) ? htmlspecialchars($_SESSION['user']['email']) : ''; ?>" 
                                readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Địa chỉ giao hàng</label>
                            <div class="address-input-group">
                                <input type="text" class="form-control" id="address" name="address" placeholder="Nhập địa chỉ..." required>
                                <button type="button" id="showMapBtn" class="btn-map">🗺️</button>
                            </div>

                            <input type="hidden" name="lat" id="lat">
                            <input type="hidden" name="lng" id="lng">
                            <input type="hidden" name="distance_km" id="distance_km" value="0">
                            <input type="hidden" name="distance_fee" id="distance_fee" value="0">
                            <input type="hidden" name="distributor_voucher_amount" id="distributor_voucher_amount" value="0">
                            <input type="hidden" name="admin_goc_voucher_amount" id="admin_goc_voucher_amount" value="0">
                            <input type="hidden" name="admin_ship_voucher_amount" id="admin_ship_voucher_amount" value="0">
                            <!-- ✅ Gửi tổng tiền đã tính từ frontend lên backend -->
                            <input type="hidden" name="total_amount_calculated" id="total_amount_calculated" value="0">
                            
                            <div id="mapContainer" class="map-section" style="display:none;">
                                <label class="form-label" style="margin-bottom:12px;">Vị trí trên bản đồ</label>
                                <iframe id="mapFrame" class="map-frame" frameborder="0" style="border:0" allowfullscreen></iframe>
                                <div class="map-info">
                                    <div style="margin-top:12px;"><strong>Địa chỉ:</strong></div>
                                    <div id="mapAddress" style="word-break: break-word; margin-top:4px;"></div>
                                    <div id="mapCoords" style="margin-top:8px; color:#64748b;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Vouchers -->
                    <div class="form-section">
                        <h3>🎁 Mã Giảm Giá</h3>
                        
                        <div class="form-group">
                            <label class="form-label">Voucher của Shop</label>
                            <select class="form-control" id="distributor_voucher" name="distributor_voucher">
                                <option value="">-- Chọn voucher --</option>
                            </select>
                            <div id="distributorVoucherLoading" style="display:none; margin-top:8px; font-size:12px; color:#94a3b8;">⏳ Đang tải...</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Voucher Admin - Giảm Giá Sản Phẩm</label>
                            <select class="form-control" id="admin_goc_voucher" name="admin_goc_voucher">
                                <option value="">-- Chọn voucher --</option>
                            </select>
                            <div id="adminGocVoucherLoading" style="display:none; margin-top:8px; font-size:12px; color:#94a3b8;">⏳ Đang tải...</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Voucher Admin - Giảm Giá Vận Chuyển</label>
                            <select class="form-control" id="admin_ship_voucher" name="admin_ship_voucher">
                                <option value="">-- Chọn voucher --</option>
                            </select>
                            <div id="adminShipVoucherLoading" style="display:none; margin-top:8px; font-size:12px; color:#94a3b8;">⏳ Đang tải...</div>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Shipping & Payment -->
                    <div class="form-section">
                        <h3>🚚 Vận Chuyển & Thanh Toán</h3>
                        
                        <div class="form-group">
                            <label class="form-label">Phương thức vận chuyển</label>
                            <select class="form-control" id="shipping_method" name="shipping_method" onchange="updateShippingFee()">
                                <option value="">-- Chọn phương thức --</option>
                                <option value="1">📦 Vận chuyển thường (3-5 ngày)</option>
                                <option value="2">🚚 Vận chuyển nhanh (1-2 ngày)</option>
                                <option value="3">⚡ Hỏa tốc (Same-day)</option>
                            </select>
                            <div id="shippingFeeInfo" class="shipping-info">
                                <div class="shipping-row">
                                    <span>Khoảng cách:</span>
                                    <strong><span id="displayDistance">0</span> km</strong>
                                </div>
                                <div class="shipping-row">
                                    <span>Phí vận chuyển:</span>
                                    <strong style="color:#10b981;"><span id="calculatedShippingFee">0</span>₫</strong>
                                </div>
                                <div class="shipping-row">
                                    <span>Ước tính giao:</span>
                                    <strong><span id="estimatedDays">3</span> ngày</strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="margin-bottom:12px;">Phương thức thanh toán</label>
                            <label>
                                <input type="radio" name="payment_method" value="cod" checked> 💵 Tiền mặt (COD)
                            </label>
                            <label>
                                <input type="radio" name="payment_method" value="vnpay"> 🏦 VNPAY (Online)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right: Summary -->
                <div>
                    <div class="summary-card">
                        <div class="summary-title">
                            <span>📊 Tóm Tắt Đơn Hàng</span>
                        </div>

                        <div class="summary-row">
                            <strong>Tổng tiền hàng:</strong>
                            <span class="summary-value" id="totalProductPrice">0₫</span>
                        </div>

                        <div class="summary-row">
                            <strong>Voucher Shop:</strong>
                            <span class="summary-value discount" id="totalDistributorVoucher">0₫</span>
                        </div>

                        <div class="summary-row">
                            <strong>Giảm giá sản phẩm:</strong>
                            <span class="summary-value discount" id="totalAdminGocVoucher">0₫</span>
                        </div>

                        <div class="summary-row">
                            <strong>Phí vận chuyển:</strong>
                            <span class="summary-value" id="totalShipping">0₫</span>
                        </div>

                        <div class="summary-row">
                            <strong>Giảm giá vận chuyển:</strong>
                            <span class="summary-value discount" id="totalAdminShipVoucher">0₫</span>
                        </div>

                        <div class="summary-total">
                            <span class="label">💰 Tổng thanh toán:</span>
                            <span class="amount" id="totalPayment">0₫</span>
                        </div>

                        <button type="submit" id="submitBtn" class="btn-checkout">
                            ✓ Xác Nhận Đặt Hàng
                        </button>

                        <div style="text-align:center; margin-top:16px; color:#94a3b8; font-size:12px;">
                            💳 Thanh toán an toàn và bảo mật
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



    <script>
        (function(){
            // Map / Geocode flow
            var showMapBtn = document.getElementById('showMapBtn');
            var addressInput = document.getElementById('address');
            var mapContainer = document.getElementById('mapContainer');
            var mapFrame = document.getElementById('mapFrame');
            var mapAddress = document.getElementById('mapAddress');
            var mapCoords = document.getElementById('mapCoords');
            var latInput = document.getElementById('lat');
            var lngInput = document.getElementById('lng');
            
            // Voucher inputs
            var distributorVoucherInput = document.getElementById('distributor_voucher');
            var adminGocVoucherInput = document.getElementById('admin_goc_voucher');
            var adminShipVoucherInput = document.getElementById('admin_ship_voucher');
            var distributorVoucherLoading = document.getElementById('distributorVoucherLoading');
            var adminGocVoucherLoading = document.getElementById('adminGocVoucherLoading');
            var adminShipVoucherLoading = document.getElementById('adminShipVoucherLoading');

            // Load cart total on page load
            loadCartTotal();
            
            // Load available vouchers
            loadAvailableVouchers();

            // Listen to distributor voucher changes
            distributorVoucherInput.addEventListener('change', function(){
                if(distributorVoucherInput.value) {
                    validateDistributorVoucher(distributorVoucherInput.value);
                } else {
                    updateTotalPayment();
                }
            });

            // Listen to admin goc voucher changes
            adminGocVoucherInput.addEventListener('change', function(){
                if(adminGocVoucherInput.value) {
                    validateAdminGocVoucher(adminGocVoucherInput.value);
                } else {
                    updateTotalPayment();
                }
            });
            // Listen to admin ship voucher changes
            adminShipVoucherInput.addEventListener('change', function(){
                if(adminShipVoucherInput.value) {
                    validateAdminShipVoucher(adminShipVoucherInput.value);
                } else {
                    updateTotalPayment();
                }
            });

            function loadCartTotal() {
            fetch('<?php echo APP_URL; ?>/?url=Home/getCartTotal', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            }).then(function(res){
                return res.json();
            }).then(function(data){
                if(data.success) {
                    var totalProducts = data.total_products || 0;
                    document.getElementById('totalProductPrice').textContent = totalProducts.toLocaleString('vi-VN') + '₫';
                    updateTotalPayment();
                }
            }).catch(function(err){
                console.error('Error loading cart total:', err);
            });
        }

        function updateTotalPayment() {
            // Get values
            var totalProductsText = document.getElementById('totalProductPrice').textContent.replace(/[^0-9]/g, '');
            var totalProducts = parseInt(totalProductsText) || 0;
            
            // Get voucher amounts
            var distributorVoucherAmount = parseInt(document.getElementById('distributor_voucher_amount').value) || 0;
            var adminGocVoucherAmount = parseInt(document.getElementById('admin_goc_voucher_amount').value) || 0;
            var adminShipVoucherAmount = parseInt(document.getElementById('admin_ship_voucher_amount').value) || 0;
            
            var shippingFee = parseInt(document.getElementById('distance_fee').value) || 0;
            var finalShippingFee = Math.max(0, shippingFee - adminShipVoucherAmount);
            
            var totalPayment = totalProducts - adminGocVoucherAmount - distributorVoucherAmount + finalShippingFee;
            if (totalPayment < 0) totalPayment = 0;
            
            // Display
            document.getElementById('totalDistributorVoucher').textContent = distributorVoucherAmount.toLocaleString('vi-VN') + '₫';
            document.getElementById('totalAdminGocVoucher').textContent = adminGocVoucherAmount.toLocaleString('vi-VN') + '₫';
            document.getElementById('totalAdminShipVoucher').textContent = adminShipVoucherAmount.toLocaleString('vi-VN') + '₫';
            document.getElementById('totalShipping').textContent = finalShippingFee.toLocaleString('vi-VN') + '₫';
            document.getElementById('totalPayment').textContent = totalPayment.toLocaleString('vi-VN') + '₫';
            
            // ✅ Lưu tổng tiền tính được vào hidden input để gửi lên backend
            document.getElementById('total_amount_calculated').value = totalPayment;
        }

        function loadAvailableVouchers() {
            console.log('Loading vouchers...');
            loadAdminVouchers();
            loadDistributorVouchersForDropdown();
        }

        function loadAdminVouchers() {
            console.log('Loading admin vouchers...');
            adminGocVoucherLoading.style.display = '';
            adminShipVoucherLoading.style.display = '';
            fetch('<?php echo APP_URL; ?>/?url=Home/getAvailableVouchers', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            }).then(function(res){
                return res.json();
            }).then(function(data){
                adminGocVoucherLoading.style.display = 'none';
                adminShipVoucherLoading.style.display = 'none';
                
                // Load GOC vouchers
                var gocHtml = '<option value="">-- Không có voucher --</option>';
                if(data.success && data.goc_vouchers && data.goc_vouchers.length > 0) {
                    gocHtml = '<option value="">-- Chọn voucher --</option>';
                    data.goc_vouchers.forEach(function(v){
                        gocHtml += '<option value="' + v.vc_id + '" data-discount="' + v.giagiam + '">' + v.display + '</option>';
                    });
                }
                adminGocVoucherInput.innerHTML = gocHtml;
                
                // Load SHIP vouchers
                var shipHtml = '<option value="">-- Không có voucher --</option>';
                if(data.success && data.ship_vouchers && data.ship_vouchers.length > 0) {
                    shipHtml = '<option value="">-- Chọn voucher --</option>';
                    data.ship_vouchers.forEach(function(v){
                        shipHtml += '<option value="' + v.vc_id + '" data-discount="' + v.giagiam + '">' + v.display + '</option>';
                    });
                }
                adminShipVoucherInput.innerHTML = shipHtml;
            }).catch(function(err){
                console.error('Error loading admin vouchers:', err);
                adminGocVoucherLoading.style.display = 'none';
                adminShipVoucherLoading.style.display = 'none';
            });
        }

        function loadDistributorVouchersForDropdown() {
            console.log('Loading distributor vouchers...');
            distributorVoucherLoading.style.display = '';
            
            fetch('<?php echo APP_URL; ?>/?url=Home/getCartItems', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            }).then(function(res){
                return res.json();
            }).then(function(cartData){
                distributorVoucherLoading.style.display = 'none';
                
                if(cartData.success && cartData.items && cartData.items.length > 0) {
                    var distributorEmail = cartData.items[0].product_seller;
                    
                    if(!distributorEmail) {
                        distributorVoucherInput.innerHTML = '<option value="">-- Không có voucher --</option>';
                        return;
                    }
                    
                    fetch('<?php echo APP_URL; ?>/?url=Home/getDistributorVouchers&distributor_email=' + encodeURIComponent(distributorEmail), {
                        method: 'GET',
                        headers: { 'Content-Type': 'application/json' }
                    }).then(function(res){
                        return res.json();
                    }).then(function(data){
                        var html = '<option value="">-- Không có voucher --</option>';
                        
                        if(data.success && data.vouchers && data.vouchers.length > 0) {
                            html = '<option value="">-- Chọn voucher --</option>';
                            data.vouchers.forEach(function(v){
                                html += '<option value="' + v.code + '" data-discount="' + v.discount_value + '">' + 
                                    v.code + ' (-' + v.discount_value.toLocaleString('vi-VN') + '₫)' + 
                                    '</option>';
                            });
                        }
                        
                        distributorVoucherInput.innerHTML = html;
                    }).catch(function(err){
                        console.error('Error loading distributor vouchers:', err);
                    });
                } else {
                    distributorVoucherInput.innerHTML = '<option value="">-- Không có voucher --</option>';
                }
            }).catch(function(err){
                console.error('Error getting cart items:', err);
                distributorVoucherLoading.style.display = 'none';
            });
        }

        function validateDistributorVoucher(voucherCode) {
            var totalProductsText = document.getElementById('totalProductPrice').textContent.replace(/[^0-9]/g, '');
            var cartTotal = parseInt(totalProductsText) || 0;
            
            fetch('<?php echo APP_URL; ?>/?url=Home/validateDistributorVoucher', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'code=' + encodeURIComponent(voucherCode) + '&cart_total=' + cartTotal
            }).then(function(res){
                return res.json();
            }).then(function(data){
                if(data.valid) {
                    document.getElementById('distributor_voucher_amount').value = data.voucher.discount_value;
                    updateTotalPayment();
                } else {
                    alert('Voucher shop không hợp lệ: ' + data.message);
                    distributorVoucherInput.value = '';
                    document.getElementById('distributor_voucher_amount').value = '0';
                    updateTotalPayment();
                }
            }).catch(function(err){
                console.error('Error validating distributor voucher:', err);
                alert('Lỗi kiểm tra voucher shop');
            });
        }

        function validateAdminGocVoucher(voucherId) {
            fetch('<?php echo APP_URL; ?>/?url=Home/validateVoucher&voucherId=' + encodeURIComponent(voucherId) + '&type=goc', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            }).then(function(res){
                return res.json();
            }).then(function(data){
                if(data.success) {
                    document.getElementById('admin_goc_voucher_amount').value = data.discount;
                    updateTotalPayment();
                } else {
                    alert('Voucher không hợp lệ hoặc đã hết hiệu lực');
                    adminGocVoucherInput.value = '';
                    document.getElementById('admin_goc_voucher_amount').value = '0';
                    updateTotalPayment();
                }
            }).catch(function(err){
                console.error('Error validating admin goc voucher:', err);
            });
        }

        function validateAdminShipVoucher(voucherId) {
            fetch('<?php echo APP_URL; ?>/?url=Home/validateVoucher&voucherId=' + encodeURIComponent(voucherId) + '&type=ship', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            }).then(function(res){
                return res.json();
            }).then(function(data){
                if(data.success) {
                    document.getElementById('admin_ship_voucher_amount').value = data.discount;
                    updateTotalPayment();
                } else {
                    alert('Voucher không hợp lệ hoặc đã hết hiệu lực');
                    adminShipVoucherInput.value = '';
                    document.getElementById('admin_ship_voucher_amount').value = '0';
                    updateTotalPayment();
                }
            }).catch(function(err){
                console.error('Error validating admin ship voucher:', err);
            });
        }

        showMapBtn.addEventListener('click', function(){
            var address = addressInput.value.trim();
            if(!address){
                alert('Vui lòng nhập địa chỉ trước khi hiển thị bản đồ.');
                return;
            }

            fetch('<?php echo APP_URL; ?>/?url=Home/geocodeAddress&address=' + encodeURIComponent(address), {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            }).then(function(res){
                var contentType = res.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    return res.text().then(function(text) {
                        throw new Error('Server returned non-JSON');
                    });
                }
                return res.json();
            }).then(function(data){
                if(!data.success){
                    alert('Không tìm thấy vị trí: ' + (data.message||'---'));
                    return;
                }
                var lat = data.lat;
                var lng = data.lng;
                var formatted = data.address || address;
                
                var addressParts = formatted.split(',').map(function(part){
                    return part.trim();
                });
                
                if(addressParts.length > 1) {
                    mapAddress.innerHTML = addressParts.join('<br>');
                } else {
                    mapAddress.textContent = formatted;
                }

                if (lat && lng) {
                    latInput.value = lat;
                    lngInput.value = lng;
                    mapCoords.textContent = lat + ', ' + lng;
                    mapFrame.src = 'https://www.google.com/maps?q=' + lat + ',' + lng + '&z=15&output=embed';
                } else {
                    latInput.value = '';
                    lngInput.value = '';
                    mapCoords.textContent = '';
                    mapFrame.src = 'https://www.google.com/maps?q=' + encodeURIComponent(formatted) + '&z=15&output=embed';
                }
                mapContainer.style.display = '';
                calculateDistance(address);
            }).catch(function(err){
                console.error('Geocode fetch error:', err);
                alert('Lỗi khi gọi geocode: ' + err.message);
            });
        });

        function calculateDistance(address) {
            fetch('<?php echo APP_URL; ?>/?url=Home/calculateDistance&address=' + encodeURIComponent(address), {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            }).then(function(res){
                return res.json();
            }).then(function(data){
                if(!data.success){
                    document.getElementById('distance_km').value = '0';
                    document.getElementById('distance_fee').value = '0';
                    updateTotalPayment();
                    return;
                }
                var distanceKm = data.distance_km || 0;
                var deliveryFee = Math.ceil(distanceKm) * 1000;
                
                document.getElementById('distance_km').value = distanceKm;
                document.getElementById('distance_fee').value = deliveryFee;
                
                updateTotalPayment();
            }).catch(function(err){
                console.error('Distance calculation error:', err);
                document.getElementById('distance_km').value = '0';
                document.getElementById('distance_fee').value = '0';
                updateTotalPayment();
            });
        }

        window.updateShippingFee = function() {
            var shippingMethodId = document.getElementById('shipping_method').value;
            var distanceKm = parseFloat(document.getElementById('distance_km').value) || 0;
            var shippingFeeInfo = document.getElementById('shippingFeeInfo');
            var calculatedFee = document.getElementById('calculatedShippingFee');
            var displayDistance = document.getElementById('displayDistance');

            if (!shippingMethodId || distanceKm <= 0) {
                shippingFeeInfo.classList.remove('active');
                return;
            }

            displayDistance.textContent = distanceKm.toFixed(2);

            var pricePerKm = {
                '1': 1000,
                '2': 2000,
                '3': 10000
            };

            fetch('<?php echo APP_URL; ?>/?url=Home/getShippingFee&method_id=' + shippingMethodId + '&distance=' + distanceKm, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            }).then(function(res) {
                return res.json();
            }).then(function(data) {
                var fee;
                var days = 3;
                if (data.success && data.fee !== null) {
                    fee = data.fee;
                    days = data.days || 3;
                } else {
                    var price = pricePerKm[shippingMethodId] || 0;
                    fee = Math.ceil(distanceKm * price);
                    if (distanceKm < 10) days = 3;
                    else if (distanceKm <= 100) days = 5;
                    else days = 7;
                    if (shippingMethodId == 2) days = Math.max(1, days - 1);
                    else if (shippingMethodId == 3) days = Math.max(1, days - 2);
                }

                calculatedFee.textContent = fee.toLocaleString('vi-VN');
                document.getElementById('estimatedDays').textContent = days;
                shippingFeeInfo.classList.add('active');
                document.getElementById('distance_fee').value = fee;
                updateTotalPayment();
            }).catch(function(err) {
                console.error('Error fetching shipping fee:', err);
                var price = pricePerKm[shippingMethodId] || 0;
                var fee = Math.ceil(distanceKm * price);
                var days = 3;
                if (distanceKm < 10) days = 3;
                else if (distanceKm <= 100) days = 5;
                else days = 7;
                if (shippingMethodId == 2) days = Math.max(1, days - 1);
                else if (shippingMethodId == 3) days = Math.max(1, days - 2);
                calculatedFee.textContent = fee.toLocaleString('vi-VN');
                document.getElementById('estimatedDays').textContent = days;
                shippingFeeInfo.classList.add('active');
                document.getElementById('distance_fee').value = fee;
                updateTotalPayment();
            });
        };

        addressInput.addEventListener('keydown', function(e){
            if(e.key === 'Enter'){
                e.preventDefault();
                showMapBtn.click();
            }
        });

        var addressTimeout;
        addressInput.addEventListener('input', function(){
            clearTimeout(addressTimeout);
            var address = addressInput.value.trim();
            
            if (address.length > 5) {
                addressTimeout = setTimeout(function(){
                    calculateDistance(address);
                }, 500);
            } else {
                document.getElementById('distance_km').value = '0';
                document.getElementById('distance_fee').value = '0';
            }
        });
        })();
    </script>
