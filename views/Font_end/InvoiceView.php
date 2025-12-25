<?php
$orderCode = $order['order_code'] ?? 'N/A';
$totalAmount = $order['total_amount'] ?? 0;
$receiver = $order['receiver'] ?? '';
$phone = $order['phone'] ?? '';
$address = $order['address'] ?? '';
$email = $order['user_email'] ?? '';
$createdAt = $order['created_at'] ?? date('Y-m-d H:i:s');
$transactionInfo = $order['transaction_info'] ?? 'chothanhtoan';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn - <?php echo htmlspecialchars($orderCode); ?></title>
    <link href="<?php echo APP_URL; ?>/public/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .invoice-container {
            background-color: white;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }
        .invoice-header p {
            margin: 5px 0;
            color: #666;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #333;
            width: 30%;
        }
        .info-value {
            color: #666;
            width: 70%;
            text-align: right;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
            background-color: #f9f9f9;
            padding: 10px;
            margin-top: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #f9f9f9;
        }
        table th {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table tr:hover {
            background-color: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 8px 0;
        }
        .summary-label {
            font-weight: bold;
        }
        .summary-value {
            text-align: right;
            min-width: 120px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 4px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #d32f2f;
        }
        .payment-status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 10px;
        }
        .payment-status.cod {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .payment-status.paid {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-button button {
            padding: 12px 30px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .print-button button:hover {
            background-color: #0056b3;
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                padding: 0;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-button">
        <button onclick="window.print()">🖨️ In Hóa Đơn</button>
    </div>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h1>HÓA ĐƠN MUA HÀNG</h1>
            <p>Mã hóa đơn: <strong><?php echo htmlspecialchars($orderCode); ?></strong></p>
            <p>Ngày đặt hàng: <strong><?php echo date('d/m/Y H:i', strtotime($createdAt)); ?></strong></p>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="invoice-info">
            <div class="section-title">📋 Thông Tin Đơn Hàng</div>
            
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo htmlspecialchars($email); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Người nhận:</div>
                <div class="info-value"><?php echo htmlspecialchars($receiver); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Số điện thoại:</div>
                <div class="info-value"><?php echo htmlspecialchars($phone); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Địa chỉ giao hàng:</div>
                <div class="info-value"><?php echo htmlspecialchars($address); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Phương thức thanh toán:</div>
                <div class="info-value">
                    <?php 
                    if ($transactionInfo === 'chothanhtoan') {
                        echo '<span class="payment-status cod">💳 Thanh toán khi nhận hàng (COD)</span>';
                    } else {
                        echo '<span class="payment-status paid">✓ Đã thanh toán qua VNPAY</span>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="invoice-info">
            <div class="section-title">🛍️ Chi Tiết Sản Phẩm</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Sản Phẩm</th>
                        <th class="text-right">Số Lượng</th>
                        <th class="text-right">Đơn Giá</th>
                        <th class="text-right">Giá Sau KM</th>
                        <th class="text-right">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal = 0;
                    $shippingFee = 0;
                    
                    if (!empty($orderDetails)) {
                        foreach ($orderDetails as $detail) {
                            $quantity = $detail['quantity'] ?? 1;
                            $unitPrice = $detail['price'] ?? 0;
                            $priceAfterDiscount = $detail['sale_price'] ?? $unitPrice;
                            $total = $detail['total'] ?? ($priceAfterDiscount * $quantity);
                            $subtotal += $total;
                            
                            echo '<tr>';
                            echo '<td><strong>' . htmlspecialchars($detail['product_name'] ?? 'N/A') . '</strong></td>';
                            echo '<td class="text-right">' . number_format($quantity, 0) . '</td>';
                            echo '<td class="text-right">' . number_format($unitPrice, 0) . ' ₫</td>';
                            echo '<td class="text-right">' . number_format($priceAfterDiscount, 0) . ' ₫</td>';
                            echo '<td class="text-right"><strong>' . number_format($total, 0) . ' ₫</strong></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Tóm tắt thanh toán -->
        <div class="invoice-info">
            <div class="section-title">💰 Tóm Tắt Thanh Toán</div>
            
            <div class="summary-row">
                <span class="summary-label">Tổng tiền sản phẩm:</span>
                <span class="summary-value"><?php echo number_format($subtotal, 0); ?> ₫</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Phí vận chuyển:</span>
                <span class="summary-value"><?php 
                    // Tính phí vận chuyển từ tổng tiền
                    $shippingFee = max(0, $totalAmount - $subtotal);
                    echo number_format($shippingFee, 0);
                ?> ₫</span>
            </div>
            
            <div class="total-row">
                <span>TỔNG CỘNG:</span>
                <span><?php echo number_format($totalAmount, 0); ?> ₫</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Cảm ơn bạn đã mua hàng!</p>
            <p>Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc điện thoại.</p>
            <p>Cô lập in này được in vào <?php echo date('d/m/Y H:i'); ?></p>
        </div>
    </div>

    <script>
        // Auto-open print dialog when page loads (optional)
        // window.print();
    </script>
</body>
</html>
