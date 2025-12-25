<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hợp Đồng Đơn Hàng - <?= htmlspecialchars($data['order']['order_code']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border: 2px solid #333;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 30px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        
        .contract-title {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0;
            letter-spacing: 1px;
        }
        
        .contract-number {
            font-size: 14px;
            margin: 10px 0;
            color: #333;
        }
        
        /* Content Sections */
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            background: #f0f0f0;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-left: 4px solid #3b5998;
            letter-spacing: 0.5px;
        }
        
        .info-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 20px;
        }
        
        .info-item {
            margin-bottom: 12px;
        }
        
        .info-label {
            font-size: 12px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 14px;
            color: #000;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 5px;
            min-height: 20px;
        }
        
        /* Table */
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .products-table thead {
            background: #f0f0f0;
            border-bottom: 2px solid #333;
        }
        
        .products-table th {
            padding: 12px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            color: #333;
        }
        
        .products-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        
        .products-table tbody tr:hover {
            background: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Summary */
        .summary-table {
            width: 100%;
            margin-top: 20px;
        }
        
        .summary-table tr {
            border-bottom: 1px solid #ddd;
        }
        
        .summary-table td {
            padding: 10px;
            font-size: 13px;
        }
        
        .summary-table td:first-child {
            text-align: right;
            font-weight: bold;
            width: 50%;
        }
        
        .summary-table .total-row {
            border-top: 2px solid #333;
            border-bottom: 3px double #333;
            font-weight: bold;
            font-size: 14px;
        }
        
        /* Status */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-processing {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .signature-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin-top: 50px;
        }
        
        .signature-item {
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 10px;
            font-size: 12px;
        }
        
        /* Print */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }
            .container {
                box-shadow: none;
                border: 1px solid #000;
            }
            .no-print {
                display: none !important;
            }
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-print {
            background: #3b5998;
            color: white;
        }
        
        .btn-print:hover {
            background: #2d4373;
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
        }
        
        .btn-back:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="action-buttons no-print">
        <button class="btn btn-print" onclick="window.print()">🖨️ In/PDF</button>
        <a href="<?= APP_URL ?>/Home/orderHistory" class="btn btn-back">← Quay Lại</a>
    </div>
    
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">🏢 PHƯƠNG NAM MARKETPLACE</div>
            <div class="company-info">
                Email: support@example.com | Hotline: 1900-xxx-xxx<br>
                Website: www.example.com
            </div>
            <div class="contract-title">Hợp Đồng Mua Bán Hàng Hóa</div>
            <div class="contract-number">
                Số: <?= htmlspecialchars($order['order_code'] ?? 'N/A'); ?>
            </div>
        </div>
        
        <!-- Date & Status -->
        <div class="info-group">
            <div class="info-item">
                <div class="info-label">Ngày Lập Hợp Đồng</div>
                <div class="info-value"><?= date('d/m/Y H:i', strtotime($order['created_at'] ?? date('Y-m-d H:i:s'))); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Trạng Thái</div>
                <div class="info-value">
                    <?php 
                    $status = $order['delivery_status'] ?? 'unknown';
                    $statusMap = [
                        'cho_xac_nhan' => 'Chờ Xác Nhận',
                        'da_giao_dvvc' => 'Đã Giao DVVC',
                        'da_nhan_hang' => 'Đã Nhận Hàng',
                        'da_tra_hang' => 'Đã Trả Hàng'
                    ];
                    echo $statusMap[$status] ?? 'Không Xác Định';
                    ?>
                </div>
            </div>
        </div>
        
        <!-- Bên Bán -->
        <div class="section">
            <div class="section-title">I. Bên Bán (Nhà Cung Cấp)</div>
            <div class="info-group">
                <div class="info-item">
                    <div class="info-label">Tên Công Ty</div>
                    <div class="info-value">Phương Nam Marketplace</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Địa Chỉ</div>
                    <div class="info-value">TP. Hồ Chí Minh, Việt Nam</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">support@example.com</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Điện Thoại</div>
                    <div class="info-value">1900-xxx-xxx</div>
                </div>
            </div>
        </div>
        
        <!-- Bên Mua -->
        <div class="section">
            <div class="section-title">II. Bên Mua (Khách Hàng)</div>
            <div class="info-group">
                <div class="info-item">
                    <div class="info-label">Tên Khách Hàng</div>
                    <div class="info-value"><?= htmlspecialchars($order['receiver'] ?? $order['user_email']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?= htmlspecialchars($order['user_email'] ?? 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Số Điện Thoại</div>
                    <div class="info-value"><?= htmlspecialchars($order['phone'] ?? 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Địa Chỉ Giao Hàng</div>
                    <div class="info-value"><?= htmlspecialchars($order['address'] ?? 'N/A'); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Chi Tiết Sản Phẩm -->
        <div class="section">
            <div class="section-title">III. Chi Tiết Sản Phẩm/Dịch Vụ</div>
            <table class="products-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">STT</th>
                        <th style="width: 40%;">Tên Sản Phẩm</th>
                        <th class="text-center" style="width: 10%;">Số Lượng</th>
                        <th class="text-right" style="width: 15%;">Đơn Giá</th>
                        <th class="text-right" style="width: 15%;">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $total = 0;
                    if (!empty($order_details)): 
                        foreach ($order_details as $item): 
                            $itemTotal = $item['quantity'] * $item['price'];
                            $total += $itemTotal;
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($item['product_name'] ?? 'N/A'); ?></td>
                        <td class="text-center"><?= $item['quantity']; ?></td>
                        <td class="text-right"><?= number_format($item['price'], 0, ',', '.'); ?> ₫</td>
                        <td class="text-right"><?= number_format($itemTotal, 0, ',', '.'); ?> ₫</td>
                    </tr>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Không có dữ liệu</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Tóm Tắt Thanh Toán -->
        <div class="section">
            <div class="section-title">IV. Tóm Tắt Thanh Toán</div>
            <table class="summary-table">
                <tr>
                    <td>Tổng Tiền Hàng</td>
                    <td class="text-right"><?= number_format($order['total_amount'] ?? 0, 0, ',', '.'); ?> ₫</td>
                </tr>
                <tr>
                    <td>Phí Vận Chuyển</td>
                    <td class="text-right"><?= number_format($order['shipping_fee'] ?? 0, 0, ',', '.'); ?> ₫</td>
                </tr>
                <?php if (!empty($order['discount'])): ?>
                <tr>
                    <td>Giảm Giá</td>
                    <td class="text-right">- <?= number_format($order['discount'], 0, ',', '.'); ?> ₫</td>
                </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td>TỔNG CỘNG</td>
                    <td class="text-right"><?= number_format($order['total_amount'] ?? 0, 0, ',', '.'); ?> ₫</td>
                </tr>
                <tr>
                    <td>Hình Thức Thanh Toán</td>
                    <td class="text-right">
                        <?php 
                        $payment = $order['payment_method'] ?? 'unknown';
                        $paymentMap = [
                            'cod' => 'Thanh Toán Khi Nhận Hàng (COD)',
                            'bank' => 'Chuyển Khoản Ngân Hàng',
                            'online' => 'Thanh Toán Online'
                        ];
                        echo $paymentMap[$payment] ?? 'Không Xác Định';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Trạng Thái Thanh Toán</td>
                    <td class="text-right">
                        <?= $order['is_paid'] ? '✓ Đã Thanh Toán' : '⏳ Chưa Thanh Toán'; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Điều Khoản -->
        <div class="section">
            <div class="section-title">V. Điều Khoản & Điều Kiện</div>
            <div style="font-size: 12px; line-height: 1.6; color: #333;">
                <p><strong>1. Giá Cả:</strong> Giá được xác nhận lúc đặt hàng. Nếu có thay đổi, bên bán sẽ thông báo trước.</p>
                <p><strong>2. Giao Hàng:</strong> Hàng hóa sẽ được giao trong vòng 1-5 ngày làm việc tùy theo địa chỉ giao hàng.</p>
                <p><strong>3. Thanh Toán:</strong> Thanh toán được thực hiện theo phương thức đã chọn. Đơn hàng chỉ được xác nhận khi đủ điều kiện thanh toán.</p>
                <p><strong>4. Đổi Trả:</strong> Khách hàng có quyền đổi/trả hàng trong vòng 7 ngày nếu hàng có lỗi từ nhà sản xuất.</p>
                <p><strong>5. Bảo Hành:</strong> Sản phẩm được bảo hành theo điều kiện của nhà sản xuất.</p>
            </div>
        </div>
        
        <!-- Signature -->
        <div class="signature-box">
            <div class="signature-item">
                <div style="font-weight: bold; margin-bottom: 10px;">Bên Bán</div>
                <div class="signature-line">
                    Phương Nam Marketplace
                </div>
            </div>
            <div class="signature-item">
                <div style="font-weight: bold; margin-bottom: 10px;">Bên Mua</div>
                <div class="signature-line">
                    <?= htmlspecialchars($order['receiver'] ?? $order['user_email']); ?>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Hợp đồng này được lập thành 2 bản, mỗi bên giữ 1 bản.</p>
            <p>Lập ngày: <?= date('d/m/Y'); ?></p>
            <p style="margin-top: 10px; color: #999;">Cảm ơn bạn đã mua hàng tại Phương Nam Marketplace!</p>
        </div>
    </div>
</body>
</html>
