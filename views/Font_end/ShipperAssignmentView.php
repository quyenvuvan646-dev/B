<?php
// Shipper order assignment view with map
$user = $_SESSION['user'] ?? null;
$orders = $data['orders'] ?? [];
?>
<div class="container-fluid mt-4">
    <h2>Phân công giao hàng</h2>
    
    <div class="row">
        <!-- Left: Address input -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Vị trí của bạn</h5>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="shipperAddress">Nhập địa chỉ hiện tại:</label>
                        <input type="text" id="shipperAddress" class="form-control" placeholder="VD: 15 Nguyễn Khang, Cầu Giấy, Hà Nội" value="<?php echo isset($data['shipper_address']) ? htmlspecialchars($data['shipper_address']) : ''; ?>">
                        <small class="form-text text-muted">Dùng để tìm đơn hàng gần nhất</small>
                    </div>
                    <button id="findOrdersBtn" class="btn btn-primary w-100">Tìm đơn hàng</button>
                </div>
            </div>
        </div>

        <!-- Right: Orders list -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Danh sách đơn hàng chưa giao</h5>
                </div>
                <div class="card-body" style="max-height: 700px; overflow-y: auto;">
                    <div id="ordersList">
                        <p class="text-muted">Nhập địa chỉ của bạn và bấm "Tìm đơn hàng" để xem danh sách</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <script>
            let currentShipperAddress = '';
            let allOrders = [];

            // Find orders and show shipper location
            document.getElementById('findOrdersBtn').addEventListener('click', function() {
                const shipperAddress = document.getElementById('shipperAddress').value.trim();
                if (!shipperAddress) {
                    alert('Vui lòng nhập địa chỉ của bạn');
                    return;
                }

                currentShipperAddress = shipperAddress;
                
                // Load and display all pending orders with distances
                loadAllOrders(shipperAddress);
            });


            function loadAllOrders(shipperAddress) {
                const ordersList = document.getElementById('ordersList');
                ordersList.innerHTML = '<p class="text-muted">Đang tải...</p>';

                // Fetch all pending orders from backend (Shipper controller)
                fetch('<?php echo APP_URL; ?>/?url=Shipper/getPendingOrders')
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success || !data.orders || data.orders.length === 0) {
                            ordersList.innerHTML = '<p class="text-muted">Không có đơn hàng chưa giao</p>';
                            return;
                        }

                        allOrders = data.orders;
                        const ordersWithDistance = [];

                        // Calculate distance for each order (no ship fee calculation)
                        let completed = 0;
                        allOrders.forEach(order => {
                            const customerAddress = order.address;
                            fetch('<?php echo APP_URL; ?>/?url=Shipper/calculateDistance&shipper_address=' + 
                                encodeURIComponent(shipperAddress) + '&customer_address=' + 
                                encodeURIComponent(customerAddress))
                                .then(r => r.json())
                                .then(distData => {
                                    if (distData && distData.success && typeof distData.distance_km !== 'undefined') {
                                        ordersWithDistance.push({
                                            ...order,
                                            distance_km: distData.distance_km,
                                            distance_text: distData.distance_text
                                        });
                                    } else {
                                        // Show order even if distance calc fails
                                        ordersWithDistance.push({
                                            ...order,
                                            distance_km: 0,
                                            distance_text: 'N/A'
                                        });
                                    }
                                })
                                .catch(err => {
                                    console.error('Error calling calculateDistance for order', order.id, err);
                                    ordersWithDistance.push({
                                        ...order,
                                        distance_km: 0,
                                        distance_text: 'N/A'
                                    });
                                })
                                .finally(() => {
                                    completed++;
                                    if (completed === allOrders.length) {
                                        renderAllOrdersList(ordersWithDistance);
                                    }
                                });
                        });
                    })
                    .catch(err => {
                        console.error('Error fetching pending orders:', err);
                        ordersList.innerHTML = '<p class="text-danger">Lỗi khi tải danh sách đơn hàng</p>';
                    });
            }

            function renderAllOrdersList(orders) {
                const ordersList = document.getElementById('ordersList');
                if (orders.length === 0) {
                    ordersList.innerHTML = '<p class="text-muted">Không có đơn hàng chưa giao</p>';
                    return;
                }

                // Sort by distance (closest first)
                orders.sort((a, b) => a.distance_km - b.distance_km);

                let html = '';
                orders.forEach(order => {
                    html += `
                        <div class="card mb-2 order-item" data-order-id="${order.id}">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div style="flex:1;">
                                        <h6 class="mb-1">${order.order_code}</h6>
                                        <small class="text-muted">
                                            <strong>${order.receiver}</strong> - ${order.phone}<br>
                                            ${order.address}<br>
                                            <span class="badge bg-info">${order.distance_km.toFixed(2)} km</span>
                                            <span class="badge bg-warning">${(parseFloat(order.total_amount) || 0).toLocaleString('vi-VN')} đ</span>
                                        </small>
                                    </div>
                                    <div class="ms-2 text-end">
                                        <button class="btn btn-sm btn-outline-primary mb-1 view-detail" data-order-id="${order.id}">Chi tiết</button>
                                        <button class="btn btn-sm btn-outline-success assign-order" data-order-id="${order.id}">Nhận giao</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                ordersList.innerHTML = html;

                // View details button -> show modal with details and order location
                document.querySelectorAll('.view-detail').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const oid = this.dataset.orderId;
                        showOrderDetail(oid, orders);
                    });
                });

                // Assign order button - show delivery modal
                document.querySelectorAll('.assign-order').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const oid = this.dataset.orderId;
                        const order = orders.find(o => o.id == oid);
                        if (!order) return;
                        showDeliveryModal(oid, order);
                    });
                });
            }

            // Show delivery modal with map and status buttons
            function showDeliveryModal(orderId, order) {
                const totalAmount = parseFloat(order.total_amount) || 0;
                
                let modalHtml = `
                    <p><strong>Mã đơn:</strong> ${order.order_code}</p>
                    <p><strong>Khách hàng:</strong> ${order.receiver} - ${order.phone}</p>
                    <p><strong>Địa chỉ giao hàng:</strong> ${order.address}</p>
                    <p style="border-top: 1px solid #ddd; padding-top: 10px;"><strong style="font-size: 1.1em;">Tổng cộng:</strong> <span style="font-size: 1.1em; color: #d9534f;">${totalAmount.toLocaleString('vi-VN')} đ</span></p>
                    <p><strong>Khoảng cách:</strong> ${order.distance_km.toFixed(2)} km</p>
                    <div id="modalMap" style="width:100%; height:400px; border:1px solid #ccc; margin:10px 0;"></div>
                `;

                let modalContainer = document.createElement('div');
                modalContainer.className = 'modal fade';
                modalContainer.tabIndex = -1;
                modalContainer.innerHTML = `
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Giao hàng - ${order.order_code}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">${modalHtml}</div>
                            <div class="modal-footer">
                                <a href="<?php echo APP_URL; ?>/Home/orderDetail/${orderId}" class="btn btn-info">Xem chi tiết & Cập nhật</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(modalContainer);
                const bsModal = new bootstrap.Modal(modalContainer);
                bsModal.show();

                // When modal shown, initialize modal map with delivery address
                modalContainer.addEventListener('shown.bs.modal', function() {
                    const modalMapEl = document.getElementById('modalMap');
                    const q = encodeURIComponent(order.address || order.receiver || '');
                    modalMapEl.innerHTML = `<iframe width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen src="https://www.google.com/maps?q=${q}&z=15&output=embed"></iframe>`;
                });

                // Handle success button
                modalContainer.querySelector('#successBtn').addEventListener('click', function() {
                    const oid = this.dataset.orderId;
                    updateOrderStatus(oid, 'da_nhan_hang', modalContainer, bsModal);
                });

                // Handle return button
                modalContainer.querySelector('#returnBtn').addEventListener('click', function() {
                    const oid = this.dataset.orderId;
                    updateOrderStatus(oid, 'da_tra_hang', modalContainer, bsModal);
                });

                // Cleanup modal element when hidden
                modalContainer.addEventListener('hidden.bs.modal', function() {
                    bsModal.dispose();
                    modalContainer.remove();
                });
            }

            // Update order status via API
            function updateOrderStatus(orderId, status, modalContainer, bsModal) {
                // If returning, show reason modal first
                if (status === 'da_tra_hang') {
                    showReturnReasonModal(orderId, bsModal);
                    return;
                }

                // For successful delivery, send directly
                fetch('<?php echo APP_URL; ?>/?url=Shipper/updateOrderStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        status: status
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Cập nhật trạng thái giao thành công');
                        
                        // Close modal and reload list
                        bsModal.hide();
                        setTimeout(() => {
                            const shipperAddress = document.getElementById('shipperAddress').value.trim();
                            if (shipperAddress) {
                                loadAllOrders(shipperAddress);
                            }
                        }, 500);
                    } else {
                        alert('Lỗi: ' + (data.message || 'Không thể cập nhật trạng thái'));
                    }
                })
                .catch(err => {
                    console.error('Error updating order status:', err);
                    alert('Lỗi khi cập nhật trạng thái');
                });
            }

            // Show modal to enter return reason
            function showReturnReasonModal(orderId, prevModal) {
                let reasonModal = document.createElement('div');
                reasonModal.className = 'modal fade';
                reasonModal.tabIndex = -1;
                reasonModal.innerHTML = `
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Lý do trả hàng</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label for="returnReason" class="form-label">Ghi lý do trả hàng:</label>
                                <textarea id="returnReason" class="form-control" rows="4" placeholder="Ví dụ: Khách từ chối nhận, hàng bị hỏng..."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="button" class="btn btn-danger" id="confirmReturnBtn">Xác nhận trả hàng</button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(reasonModal);
                const bsReasonModal = new bootstrap.Modal(reasonModal);
                bsReasonModal.show();

                document.getElementById('confirmReturnBtn').addEventListener('click', function() {
                    const reason = document.getElementById('returnReason').value.trim() || 'Không có lý do';
                    
                    fetch('<?php echo APP_URL; ?>/?url=Shipper/updateOrderStatus', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            status: 'da_tra_hang',
                            return_reason: reason
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            alert('Cập nhật trạng thái trả hàng thành công');
                            bsReasonModal.hide();
                            prevModal.hide();
                            
                            setTimeout(() => {
                                const shipperAddress = document.getElementById('shipperAddress').value.trim();
                                if (shipperAddress) {
                                    loadAllOrders(shipperAddress);
                                }
                            }, 500);
                        } else {
                            alert('Lỗi: ' + (data.message || 'Không thể cập nhật trạng thái'));
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Lỗi khi cập nhật trạng thái');
                    });
                });

                reasonModal.addEventListener('hidden.bs.modal', function() {
                    reasonModal.remove();
                });
            }

            // Show order details modal with map of delivery address
            function showOrderDetail(orderId, orders) {
                const order = orders.find(o => o.id == orderId);
                if (!order) return;

                // Nếu backend trả kèm order_items, hiển thị list sản phẩm
                let itemsHtml = '';
                if (order.order_items && Array.isArray(order.order_items) && order.order_items.length > 0) {
                    itemsHtml = '<div class="mt-3"><strong>Sản phẩm:</strong><div class="list-group">';
                    order.order_items.forEach(it => {
                        const name = it.tensp || it.product_name || 'Sản phẩm';
                        const qty = it.qty || it.quantity || 1;
                        const img = it.hinhanh ? (`<?php echo APP_URL; ?>/public/images/${it.hinhanh}`) : '';
                        itemsHtml += `<div class="list-group-item d-flex align-items-center">
                            ${img ? `<img src="${img}" alt="${name}" style="width:50px;height:50px;object-fit:cover;margin-right:10px;border-radius:4px;">` : ''}
                            <div>
                                <div><strong>${name}</strong></div>
                                <div class="text-muted">SL: ${qty}</div>
                            </div>
                        </div>`;
                    });
                    itemsHtml += '</div></div>';
                }

                let modalHtml = `
                    <p><strong>Mã đơn:</strong> ${order.order_code}</p>
                    <p><strong>Khách hàng:</strong> ${order.receiver} - ${order.phone}</p>
                    <p><strong>Địa chỉ giao hàng:</strong> ${order.address}</p>
                    <p><strong>Tổng tiền:</strong> ${order.total_amount.toLocaleString('vi-VN')} đ</p>
                    <p><strong>Khoảng cách:</strong> ${order.distance_km.toFixed(1)} km</p>
                    ${itemsHtml}
                    <div id="modalMap" style="width:100%; height:400px; border:1px solid #ccc; margin-top:10px;"></div>
                `;

                let modalContainer = document.createElement('div');
                modalContainer.className = 'modal fade';
                modalContainer.tabIndex = -1;
                modalContainer.innerHTML = `
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Chi tiết đơn hàng</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">${modalHtml}</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(modalContainer);
                const bsModal = new bootstrap.Modal(modalContainer);
                bsModal.show();

                // When modal shown, initialize modal map with delivery address
                modalContainer.addEventListener('shown.bs.modal', function() {
                    const modalMapEl = document.getElementById('modalMap');
                    const q = encodeURIComponent(order.address || order.receiver || '');
                    modalMapEl.innerHTML = `<iframe width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen src="https://www.google.com/maps?q=${q}&z=15&output=embed"></iframe>`;
                });

                // Cleanup modal element when hidden
                modalContainer.addEventListener('hidden.bs.modal', function() {
                    bsModal.dispose();
                    modalContainer.remove();
                });
            }
        </script>

<!-- Load Google Maps JS API dynamically with callback and error handling -->
<script>
    (function(){
        var key = '<?php echo defined("GOOGLE_MAPS_API_KEY") ? GOOGLE_MAPS_API_KEY : ""; ?>';
        if (!key) return; // nothing to do

        // Create script element with callback=initMap
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.defer = true;
        s.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(key) + '&libraries=places&callback=initMap';
        s.onerror = function() {
            var mapEl = document.getElementById('map');
            if (mapEl) mapEl.innerHTML = '<div class="text-center text-danger mt-5">Google Maps không tải được. Vui lòng kiểm tra API key và mở bảng điều khiển JavaScript để biết chi tiết.</div>';
        };
        document.head.appendChild(s);

        // Minimal initMap to mark that maps loaded (we don't need a global map here)
        window.initMap = function() {
            // noop - maps library loaded and ready for geocoding/markers in showOnMap
            console.debug('Google Maps API loaded');
        };
    })();
</script>
