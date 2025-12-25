<?php
require_once 'BaseModel.php';

class StatisticsModel extends BaseModel
{
    // Thống kê trạng thái đơn hàng
    public function getOrderStatusStats()
    {
        $sql = "SELECT transaction_info, COUNT(*) as order_count, SUM(total_amount) as total_revenue FROM orders GROUP BY transaction_info ORDER BY order_count DESC";
        return $this->select($sql, []);
    }

    // Thống kê khách hàng
    public function getCustomerStats()
    {
        $sql = "SELECT u.email, u.fullname, COUNT(o.id) as order_count, SUM(o.total_amount) as total_revenue FROM tbluser u LEFT JOIN orders o ON u.email = o.user_email GROUP BY u.email, u.fullname ORDER BY total_revenue DESC";
        return $this->select($sql, []);
    }
    // 1️⃣ Doanh thu theo ngày
    public function getRevenueByDay($date)
    {
        $sql = "SELECT DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as order_count 
            FROM orders 
            WHERE DATE(created_at) = ?
            GROUP BY DATE(created_at)";
        $result = $this->select($sql, [$date]);
        return $result ? $result[0] : ['revenue' => 0, 'order_count' => 0];
    }

    // 2️⃣ Doanh thu theo tháng
    public function getRevenueByMonth($year, $month)
    {
        $sql = "SELECT YEAR(created_at) as year, MONTH(created_at) as month, 
            SUM(total_amount) as revenue, COUNT(*) as order_count 
            FROM orders 
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            GROUP BY YEAR(created_at), MONTH(created_at)";
        $result = $this->select($sql, [$year, $month]);
        return $result ? $result[0] : ['revenue' => 0, 'order_count' => 0];
    }

    // 3️⃣ Doanh thu theo năm
    public function getRevenueByYear($year)
    {
        $sql = "SELECT YEAR(created_at) as year, SUM(total_amount) as revenue, COUNT(*) as order_count 
            FROM orders 
            WHERE YEAR(created_at) = ?
            GROUP BY YEAR(created_at)";
        $result = $this->select($sql, [$year]);
        return $result ? $result[0] : ['revenue' => 0, 'order_count' => 0];
    }

    // 4️⃣ Doanh thu toàn bộ (tất cả thời gian)
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(total_amount) as total_revenue, COUNT(*) as total_orders 
            FROM orders";
        $result = $this->select($sql, []);
        return $result ? $result[0] : ['total_revenue' => 0, 'total_orders' => 0];
    }

    // 5️⃣ Doanh thu từng sản phẩm
    public function getRevenueByProduct()
    {
        $sql = "SELECT od.product_id, sp.tensp, sp.hinhanh, 
            SUM(od.total) as product_revenue, 
            SUM(od.quantity) as total_quantity,
            COUNT(DISTINCT od.order_id) as order_count
            FROM order_details od
            JOIN tblsanpham sp ON od.product_id = sp.masp
            GROUP BY od.product_id, sp.tensp, sp.hinhanh
            ORDER BY product_revenue DESC";
        return $this->select($sql, []);
    }

    // 6️⃣ Doanh thu từng danh mục sản phẩm
    public function getRevenueByCategory()
    {
        $sql = "SELECT lsp.maLoaiSP, lsp.tenLoaiSP, 
                SUM(od.total) as category_revenue, 
                SUM(od.quantity) as total_quantity,
                COUNT(DISTINCT od.order_id) as order_count
                FROM order_details od
                JOIN tblsanpham sp ON od.product_id = sp.masp
                JOIN tblloaisp lsp ON sp.maLoaiSP = lsp.maLoaiSP
                GROUP BY lsp.maLoaiSP, lsp.tenLoaiSP
                ORDER BY category_revenue DESC";
        return $this->select($sql, []);
    }

    // 7️⃣ Tồn kho sản phẩm
    public function getInventory()
    {
        $sql = "SELECT masp, tensp, hinhanh, soluong, giaNhap, giaXuat, 
                (soluong * giaNhap) as inventory_value,
                (soluong * giaXuat) as selling_value
                FROM tblsanpham
                ORDER BY soluong ASC";
        return $this->select($sql, []);
    }

    // 8️⃣ Sản phẩm sắp hết hàng (soluong < 5)
    public function getLowStockProducts()
    {
        $sql = "SELECT masp, tensp, hinhanh, soluong, giaNhap, giaXuat
                FROM tblsanpham
                WHERE soluong < 5
                ORDER BY soluong ASC";
        return $this->select($sql, []);
    }

    // 9️⃣ Tăng trưởng doanh thu (so sánh tháng hiện tại vs tháng trước)
    public function getRevenueGrowth($year, $month)
    {
        $prevYear = $month == 1 ? $year - 1 : $year;
        $prevMonth = $month == 1 ? 12 : $month - 1;

        $currentMonthRevenue = $this->getRevenueByMonth($year, $month);
        $prevMonthRevenue = $this->getRevenueByMonth($prevYear, $prevMonth);

        $current = $currentMonthRevenue['revenue'] ?? 0;
        $previous = $prevMonthRevenue['revenue'] ?? 0;

        if ($previous == 0) {
            $growth = $current > 0 ? 100 : 0;
        } else {
            $growth = (($current - $previous) / $previous) * 100;
        }

        return [
            'current_revenue' => $current,
            'prev_revenue' => $previous,
            'growth_percent' => round($growth, 2),
            'current_month' => date('m/Y', mktime(0, 0, 0, $month, 1, $year)),
            'prev_month' => date('m/Y', mktime(0, 0, 0, $prevMonth, 1, $prevYear))
        ];
    }

    // 🔟 Doanh thu theo các khoảng thời gian (cho biểu đồ)
    public function getRevenueChart($period = 'month', $year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }

        if ($period === 'day') {
            // Doanh thu từng ngày trong tháng hiện tại
            $sql = "SELECT DATE(created_at) as date, SUM(total_amount) as revenue
                    FROM orders
                    WHERE YEAR(created_at) = ? AND MONTH(created_at) = ? AND transaction_info IN ('chothanhtoan', 'thanhtoanvnpay')
                    GROUP BY DATE(created_at)
                    ORDER BY created_at ASC";
            $month = date('n');
            return $this->select($sql, [$year, $month]);
        } elseif ($period === 'month') {
            // Doanh thu từng tháng trong năm
            $sql = "SELECT MONTH(created_at) as month, SUM(total_amount) as revenue
                    FROM orders
                    WHERE YEAR(created_at) = ? AND transaction_info IN ('chothanhtoan', 'thanhtoanvnpay')
                    GROUP BY MONTH(created_at)
                    ORDER BY MONTH(created_at) ASC";
            return $this->select($sql, [$year]);
        } elseif ($period === 'year') {
            // Doanh thu từng năm (5 năm gần nhất)
            $sql = "SELECT YEAR(created_at) as year, SUM(total_amount) as revenue
                    FROM orders
                    WHERE YEAR(created_at) >= ? AND transaction_info IN ('chothanhtoan', 'thanhtoanvnpay')
                    GROUP BY YEAR(created_at)
                    ORDER BY YEAR(created_at) ASC";
            return $this->select($sql, [$year - 5]);
        }

        return [];
    }

    // 1️⃣1️⃣ Thống kê tổng hợp
    public function getDashboardSummary()
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        $totalRevenue = $this->getTotalRevenue();
        $todayRevenue = $this->getRevenueByDay($today);
        $monthRevenue = $this->getRevenueByMonth(date('Y'), date('n'));
        $totalProducts = $this->select("SELECT COUNT(*) as count FROM tblsanpham", []);
        $lowStockCount = $this->select("SELECT COUNT(*) as count FROM tblsanpham WHERE soluong < 5", []);

        return [
            'total_revenue' => $totalRevenue['total_revenue'] ?? 0,
            'total_orders' => $totalRevenue['total_orders'] ?? 0,
            'today_revenue' => $todayRevenue['revenue'] ?? 0,
            'today_orders' => $todayRevenue['order_count'] ?? 0,
            'month_revenue' => $monthRevenue['revenue'] ?? 0,
            'month_orders' => $monthRevenue['order_count'] ?? 0,
            'total_products' => $totalProducts[0]['count'] ?? 0,
            'low_stock_products' => $lowStockCount[0]['count'] ?? 0
        ];
    }

    // Thống kê cho distributor
    public function getDistributorStats($email, $filterType = 'all', $filterValue = null)
    {
        // Xây dựng điều kiện WHERE dựa trên filter
        $dateCondition = "";
        $params = [$email];
        
        if ($filterType === 'day' && $filterValue) {
            $dateCondition = " AND DATE(o.created_at) = ? ";
            $params[] = $filterValue;
        } elseif ($filterType === 'month' && $filterValue) {
            $dateCondition = " AND YEAR(o.created_at) = YEAR(?) AND MONTH(o.created_at) = MONTH(?) ";
            $params[] = $filterValue;
            $params[] = $filterValue;
        } elseif ($filterType === 'year' && $filterValue) {
            $dateCondition = " AND YEAR(o.created_at) = ? ";
            $params[] = $filterValue;
        }
        
        // Số sao trung bình
        $avgRatingSql = "SELECT AVG(c.rating) as avg_rating 
                         FROM comments c
                         JOIN tblsanpham p ON c.masp = p.masp
                         WHERE p.email = ?";
        $avgRating = $this->select($avgRatingSql, [$email]);
        
        // Số sản phẩm đã bán (trạng thái đã nhận hàng)
        $soldProductsSql = "SELECT COUNT(od.id) as sold_count, SUM(od.quantity) as total_quantity
                            FROM order_details od
                            JOIN tblsanpham p ON od.product_id = p.masp
                            JOIN orders o ON od.order_id = o.id
                            WHERE p.email = ? AND o.delivery_status = 'da_nhan_hang' $dateCondition";
        $soldProducts = $this->select($soldProductsSql, $params);
        
        // Số sản phẩm bị trả
        $returnedProductsSql = "SELECT COUNT(od.id) as returned_count, SUM(od.quantity) as returned_quantity
                                FROM order_details od
                                JOIN tblsanpham p ON od.product_id = p.masp
                                JOIN orders o ON od.order_id = o.id
                                WHERE p.email = ? AND o.delivery_status = 'da_tra_hang' $dateCondition";
        $returnedProducts = $this->select($returnedProductsSql, $params);
        
        // Tổng tiền bán (dựa trên số tiền thực tế khách trả trong hóa đơn)
        $totalRevenueSql = "SELECT SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as total_revenue
                            FROM order_details od
                            JOIN tblsanpham p ON od.product_id = p.masp
                            JOIN orders o ON od.order_id = o.id
                            WHERE p.email = ? AND o.delivery_status = 'da_nhan_hang' $dateCondition";
        $totalRevenue = $this->select($totalRevenueSql, $params);

        // Tổng số đơn có sản phẩm của distributor (bất kể trạng thái giao hàng)
        $totalOrdersWithProductsSql = "SELECT COUNT(DISTINCT o.id) as total_orders
                        FROM order_details od
                        JOIN tblsanpham p ON od.product_id = p.masp
                        JOIN orders o ON od.order_id = o.id
                        WHERE p.email = ? $dateCondition";
        $totalOrdersWithProducts = $this->select($totalOrdersWithProductsSql, $params);

        // Số đơn đã thanh toán thành công (online đã trả tiền)
        $paidOrdersSql = "SELECT COUNT(DISTINCT o.id) as paid_orders
                  FROM order_details od
                  JOIN tblsanpham p ON od.product_id = p.masp
                  JOIN orders o ON od.order_id = o.id
                  WHERE p.email = ? AND (o.is_paid = 1 OR o.transaction_info = 'dathanhtoan') $dateCondition";
        $paidOrders = $this->select($paidOrdersSql, $params);

        // Tổng tiền nhận được từ thanh toán online (dathanhtoan / is_paid = 1)
        $onlineRevenueSql = "SELECT SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as online_revenue
                     FROM order_details od
                     JOIN tblsanpham p ON od.product_id = p.masp
                     JOIN orders o ON od.order_id = o.id
                     WHERE p.email = ? AND (o.is_paid = 1 OR o.transaction_info = 'dathanhtoan') $dateCondition";
        $onlineRevenue = $this->select($onlineRevenueSql, $params);

        // Tổng tiền từ thanh toán khi nhận hàng (COD - trạng thái chothanhtoan)
        $codRevenueSql = "SELECT SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as cod_revenue
                  FROM order_details od
                  JOIN tblsanpham p ON od.product_id = p.masp
                  JOIN orders o ON od.order_id = o.id
                  WHERE p.email = ? AND o.transaction_info = 'chothanhtoan' $dateCondition";
        $codRevenue = $this->select($codRevenueSql, $params);

        // Tổng số đơn bị hủy
        $cancelledOrdersSql = "SELECT COUNT(DISTINCT o.id) as cancelled_orders
                       FROM order_details od
                       JOIN tblsanpham p ON od.product_id = p.masp
                       JOIN orders o ON od.order_id = o.id
                       WHERE p.email = ? AND o.delivery_status = 'da_huy' $dateCondition";
        $cancelledOrders = $this->select($cancelledOrdersSql, $params);
        
        // Tổng giá nhập
        $totalCostSql = "SELECT SUM(od.quantity * p.giaNhap) as total_cost
                         FROM order_details od
                         JOIN tblsanpham p ON od.product_id = p.masp
                         JOIN orders o ON od.order_id = o.id
                         WHERE p.email = ? AND o.delivery_status = 'da_nhan_hang' $dateCondition";
        $totalCost = $this->select($totalCostSql, $params);
        
        // Tính toán
        $revenue = floatval($totalRevenue[0]['total_revenue'] ?? 0);
        $cost = floatval($totalCost[0]['total_cost'] ?? 0);
        $tax = $revenue * 0.01; // 1% thuế
        $profit = $revenue - $cost - $tax;
        
        return [
            'avg_rating' => floatval($avgRating[0]['avg_rating'] ?? 0),
            'sold_count' => intval($soldProducts[0]['sold_count'] ?? 0),
            'sold_quantity' => intval($soldProducts[0]['total_quantity'] ?? 0),
            'returned_count' => intval($returnedProducts[0]['returned_count'] ?? 0),
            'returned_quantity' => intval($returnedProducts[0]['returned_quantity'] ?? 0),
            'total_orders_with_products' => intval($totalOrdersWithProducts[0]['total_orders'] ?? 0),
            'paid_orders' => intval($paidOrders[0]['paid_orders'] ?? 0),
            'online_revenue' => floatval($onlineRevenue[0]['online_revenue'] ?? 0),
            'cod_revenue' => floatval($codRevenue[0]['cod_revenue'] ?? 0),
            'cancelled_orders' => intval($cancelledOrders[0]['cancelled_orders'] ?? 0),
            'total_revenue' => $revenue,
            'total_cost' => $cost,
            'tax' => $tax,
            'profit' => $profit
        ];
    }

    // Lấy doanh thu theo ngày trong tháng
    public function getDistributorDailyRevenue($email, $year, $month)
    {
        $sql = "SELECT DATE(o.created_at) as date, 
                SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as revenue,
                COUNT(DISTINCT o.id) as order_count
                FROM order_details od
                JOIN tblsanpham p ON od.product_id = p.masp
                JOIN orders o ON od.order_id = o.id
                WHERE p.email = ? AND YEAR(o.created_at) = ? AND MONTH(o.created_at) = ? 
                AND o.delivery_status = 'da_nhan_hang'
                GROUP BY DATE(o.created_at)
                ORDER BY DATE(o.created_at)";
        return $this->select($sql, [$email, $year, $month]);
    }

    // Lấy doanh thu theo tháng trong năm
    public function getDistributorMonthlyRevenue($email, $year)
    {
        $sql = "SELECT MONTH(o.created_at) as month, 
                SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as revenue,
                COUNT(DISTINCT o.id) as order_count
                FROM order_details od
                JOIN tblsanpham p ON od.product_id = p.masp
                JOIN orders o ON od.order_id = o.id
                WHERE p.email = ? AND YEAR(o.created_at) = ? 
                AND o.delivery_status = 'da_nhan_hang'
                GROUP BY MONTH(o.created_at)
                ORDER BY MONTH(o.created_at)";
        return $this->select($sql, [$email, $year]);
    }
}
?>
?>
