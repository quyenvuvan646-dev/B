<?php
require_once 'BaseModel.php';

class AdminStatisticsModel extends BaseModel {
    
    // Doanh thu tổng theo thời gian (chỉ tính đơn đã nhận hàng)
    public function getTotalRevenue($filterType = 'all', $filterValue = null) {
        // Đếm tất cả đơn đã giao thành công; COD được xem là đã trả tiền khi giao
        $query = "SELECT SUM(total_amount) as total_revenue, COUNT(*) as order_count FROM orders WHERE delivery_status = 'da_nhan_hang'";
        
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(created_at) = YEAR(:filterValue) AND MONTH(created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(created_at) = :filterValue";
        }
        
        $stmt = $this->db->prepare($query);
        if ($filterValue) {
            $stmt->bindParam(':filterValue', $filterValue);
        }
        $stmt->execute();
        return $stmt->fetch();
    }

    // Tổng số đơn hàng theo trạng thái
    public function getOrderStats($filterType = 'all', $filterValue = null) {
        $query = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN delivery_status = 'dang_chuan_bi' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN delivery_status = 'da_giao_dvvc' THEN 1 ELSE 0 END) as processing_orders,
                    SUM(CASE WHEN delivery_status = 'da_nhan_hang' THEN 1 ELSE 0 END) as completed_orders,
                    SUM(CASE WHEN delivery_status = 'da_tra_hang' THEN 1 ELSE 0 END) as returned_orders
                  FROM orders";
        
        if ($filterType === 'day' && $filterValue) {
            $query .= " WHERE DATE(created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " WHERE YEAR(created_at) = YEAR(:filterValue) AND MONTH(created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " WHERE YEAR(created_at) = :filterValue";
        }
        
        $stmt = $this->db->prepare($query);
        if ($filterValue) {
            $stmt->bindParam(':filterValue', $filterValue);
        }
        $stmt->execute();
        return $stmt->fetch();
    }

    // Tổng số người dùng theo role
    public function getUserStats() {
        $query = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN user_role = 1 THEN 1 ELSE 0 END) as buyers,
                    SUM(CASE WHEN user_role = 3 THEN 1 ELSE 0 END) as distributors,
                    SUM(CASE WHEN user_role = 2 THEN 1 ELSE 0 END) as shippers,
                    SUM(CASE WHEN user_role = 4 THEN 1 ELSE 0 END) as admins
                  FROM tbluser";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Top 10 distributor (doanh thu cao nhất - chỉ tính đơn đã nhận hàng)
        public function getTopDistributors($filterType = 'all', $filterValue = null) {
                $query = "SELECT 
                                        p.email,
                                        u.fullname,
                                        SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as total_revenue,
                                        SUM(od.quantity * p.giaNhap) as total_cost,
                                        (SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) - SUM(od.quantity * p.giaNhap)) as profit,
                                        COUNT(DISTINCT o.id) as order_count
                                    FROM orders o
                                    JOIN order_details od ON o.id = od.order_id
                                    JOIN tblsanpham p ON od.product_id = p.masp
                                    JOIN tbluser u ON p.email = u.email
                                        WHERE o.delivery_status = 'da_nhan_hang'";
        
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(o.created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = :filterValue";
        }
        
        $query .= " GROUP BY p.email ORDER BY profit DESC LIMIT 10";
        
        $stmt = $this->db->prepare($query);
        if ($filterValue) {
            $stmt->bindParam(':filterValue', $filterValue);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Sản phẩm bán chạy nhất (chỉ tính đơn đã nhận hàng)
    public function getBestSellingProducts($limit = 10, $filterType = 'all', $filterValue = null) {
        $query = "SELECT 
                    p.masp as product_id,
                    p.tensp as product_name,
                    p.email as distributor_email,
                    u.fullname as distributor_name,
                                        SUM(od.quantity) as total_sold,
                                        -- Doanh thu theo số tiền thực thu (phân bổ theo tỷ lệ giá trị sản phẩm trong đơn)
                                        SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as total_revenue,
                    p.hinhanh as image
                  FROM order_details od
                  JOIN tblsanpham p ON od.product_id = p.masp
                  JOIN tbluser u ON p.email = u.email
                  JOIN orders o ON od.order_id = o.id
                      WHERE o.delivery_status = 'da_nhan_hang'";
        
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(o.created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = :filterValue";
        }
        
        $query .= " GROUP BY p.masp ORDER BY total_sold DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        if ($filterValue) {
            $stmt->bindParam(':filterValue', $filterValue);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Doanh thu theo loại sản phẩm (chỉ tính đơn đã nhận hàng)
    public function getRevenueByProductType($filterType = 'all', $filterValue = null) {
        $query = "SELECT 
                    lt.maloaisp,
                    lt.tenloaisp as type_name,
                                        -- Doanh thu theo số tiền thực thu (phân bổ theo tỷ lệ giá trị sản phẩm trong đơn)
                                        SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as total_revenue,
                    SUM(od.quantity) as total_sold,
                    COUNT(DISTINCT o.id) as order_count
                  FROM order_details od
                  JOIN tblsanpham p ON od.product_id = p.masp
                  JOIN tblloaisp lt ON p.maloaisp = lt.maloaisp
                  JOIN orders o ON od.order_id = o.id
                                    WHERE o.delivery_status = 'da_nhan_hang'";
        
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(o.created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = :filterValue";
        }
        
        $query .= " GROUP BY lt.maloaisp ORDER BY total_revenue DESC";
        
        $stmt = $this->db->prepare($query);
        if ($filterValue) {
            $stmt->bindParam(':filterValue', $filterValue);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Doanh thu hàng ngày (cho chart - chỉ tính đơn đã nhận hàng)
    public function getDailyRevenue($days = 30) {
        $query = "SELECT 
                    DATE(created_at) as date,
                    SUM(total_amount) as revenue,
                    COUNT(*) as order_count
                  FROM orders
                                    WHERE delivery_status = 'da_nhan_hang' AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                  GROUP BY DATE(created_at)
                  ORDER BY date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Thống kê thanh toán (chỉ tính đơn đã nhận hàng)
    public function getPaymentStats($filterType = 'all', $filterValue = null) {
        $query = "SELECT 
                    transaction_info as payment_method,
                    COUNT(*) as count,
                    SUM(total_amount) as total_amount
                  FROM orders
                      WHERE delivery_status = 'da_nhan_hang'";
        
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(created_at) = YEAR(:filterValue) AND MONTH(created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(created_at) = :filterValue";
        }
        
        $query .= " GROUP BY transaction_info";
        
        $stmt = $this->db->prepare($query);
        if ($filterValue) {
            $stmt->bindParam(':filterValue', $filterValue);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tổng quan tài chính: thu, chi, lãi, thuế (1% lãi - chỉ tính đơn đã nhận hàng)
    public function getFinancialSummary($filterType = 'all', $filterValue = null) {
        // Revenue from actual amount paid in orders (o.total_amount) on delivered & paid orders
        $revQuery = "SELECT SUM(o.total_amount) as revenue
                     FROM orders o
                         WHERE o.delivery_status = 'da_nhan_hang'";
        // Cost from product cost price * quantity
        $costQuery = "SELECT SUM(od.quantity * p.giaNhap) as cost
                      FROM orders o
                      JOIN order_details od ON o.id = od.order_id
                      JOIN tblsanpham p ON od.product_id = p.masp
                          WHERE o.delivery_status = 'da_nhan_hang'";

        if ($filterType === 'day' && $filterValue) {
            $revQuery .= " AND DATE(o.created_at) = :filterValue";
            $costQuery .= " AND DATE(o.created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $revQuery .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
            $costQuery .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $revQuery .= " AND YEAR(o.created_at) = :filterValue";
            $costQuery .= " AND YEAR(o.created_at) = :filterValue";
        }

        // Execute revenue
        $stmt = $this->db->prepare($revQuery);
        if ($filterValue) { $stmt->bindParam(':filterValue', $filterValue); }
        $stmt->execute();
        $row = $stmt->fetch();
        $revenue = (float)($row['revenue'] ?? 0);

        // Execute cost
        $stmt = $this->db->prepare($costQuery);
        if ($filterValue) { $stmt->bindParam(':filterValue', $filterValue); }
        $stmt->execute();
        $row = $stmt->fetch();
        $cost = (float)($row['cost'] ?? 0);

        $profit = $revenue - $cost;
        $tax = $profit * 0.01; // 1% of profit
        return [
            'revenue' => $revenue,
            'cost' => $cost,
            'profit' => $profit,
            'tax' => $tax
        ];
    }

    // Thuế theo từng distributor (1% lãi ~ doanh thu distributor - chỉ tính đơn đã nhận hàng)
        public function getDistributorTaxes($filterType = 'all', $filterValue = null) {
                $query = "SELECT 
                                        p.email,
                                        u.fullname,
                                        SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as revenue,
                                        SUM(od.quantity * p.giaNhap) as cost,
                                        (SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) - SUM(od.quantity * p.giaNhap)) as profit
                                    FROM order_details od
                                    JOIN tblsanpham p ON od.product_id = p.masp
                                    JOIN tbluser u ON p.email = u.email
                                    JOIN orders o ON od.order_id = o.id
                                    WHERE o.delivery_status = 'da_nhan_hang'";
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(o.created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = :filterValue";
        }
        $query .= " GROUP BY p.email";

        $stmt = $this->db->prepare($query);
        if ($filterValue) {
            $stmt->bindParam(':filterValue', $filterValue);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as &$r) {
            $rev = (float)($r['revenue'] ?? 0);
            $cost = (float)($r['cost'] ?? 0);
            $profit = $rev - $cost;
            $r['profit'] = $profit;
            $r['tax'] = $profit * 0.01; // 1% of profit
        }
        return $rows;
    }

    // Tổng số sản phẩm
    public function getProductStats() {
        $query = "SELECT 
                    COUNT(*) as total_products,
                    COUNT(DISTINCT maloaisp) as total_types,
                    COUNT(DISTINCT email) as total_distributors
                  FROM tblsanpham";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Đơn hàng hết hàng (có sản phẩm hết stock)
    public function getOutOfStockOrders() {
        $query = "SELECT COUNT(DISTINCT o.id) as out_of_stock_orders
                  FROM orders o
                  JOIN order_details od ON o.id = od.order_id
                  JOIN tblsanpham p ON od.product_id = p.masp
                  WHERE p.soluong = 0 AND o.delivery_status NOT IN ('da_nhan_hang', 'da_tra_hang')";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['out_of_stock_orders'] ?? 0;
    }

    // Sản phẩm được nhiều người yêu thích (>2 favorites)
    public function getFavoriteProducts($limit = 10) {
        $query = "SELECT 
                    p.masp as product_id,
                    p.tensp as product_name,
                    u.fullname as distributor_name,
                    COUNT(f.id) as favorite_count,
                    p.hinhanh as image
                  FROM tblsanpham p
                  LEFT JOIN favorites f ON p.masp = f.product_code
                  LEFT JOIN tbluser u ON p.email = u.email
                  GROUP BY p.masp
                  HAVING favorite_count > 2
                  ORDER BY favorite_count DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Sản phẩm được đánh giá cao (avg rating > 4)
    public function getHighlyRatedProducts($limit = 10) {
        $query = "SELECT 
                    p.masp as product_id,
                    p.tensp as product_name,
                    u.fullname as distributor_name,
                    ROUND(AVG(c.rating), 2) as avg_rating,
                    COUNT(c.id) as review_count,
                    p.hinhanh as image
                  FROM tblsanpham p
                  LEFT JOIN comments c ON p.masp = c.masp AND c.is_visible = 1 AND c.is_deleted = 0 AND c.parent_id IS NULL AND c.rating IS NOT NULL
                  LEFT JOIN tbluser u ON p.email = u.email
                  GROUP BY p.masp
                  HAVING avg_rating > 4 AND review_count > 0
                  ORDER BY avg_rating DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Thống kê theo mã loại sản phẩm
     */
    public function getStatsByProductType($filterType = 'all', $filterValue = null) {
        $query = "SELECT 
                    lt.maloaisp,
                    lt.tenloaisp as type_name,
                    SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as revenue,
                    SUM(od.quantity) as total_sold,
                    COUNT(DISTINCT o.id) as order_count
                  FROM order_details od
                  JOIN tblsanpham p ON od.product_id = p.masp
                  JOIN tblloaisp lt ON p.maloaisp = lt.maloaisp
                  JOIN orders o ON od.order_id = o.id
                  WHERE o.delivery_status = 'da_nhan_hang'";
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(o.created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = :filterValue";
        }
        $query .= " GROUP BY lt.maloaisp";
        $stmt = $this->db->prepare($query);
        if ($filterValue) { $stmt->bindParam(':filterValue', $filterValue); }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Thống kê theo sản phẩm
     */
    public function getStatsByProduct($filterType = 'all', $filterValue = null) {
        $query = "SELECT 
                    p.masp as product_id,
                    p.tensp as product_name,
                    p.email as distributor_email,
                    SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as revenue,
                    SUM(od.quantity) as total_sold,
                    COUNT(DISTINCT o.id) as order_count
                  FROM order_details od
                  JOIN tblsanpham p ON od.product_id = p.masp
                  JOIN orders o ON od.order_id = o.id
                  WHERE o.delivery_status = 'da_nhan_hang'";
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(o.created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = :filterValue";
        }
        $query .= " GROUP BY p.masp";
        $stmt = $this->db->prepare($query);
        if ($filterValue) { $stmt->bindParam(':filterValue', $filterValue); }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Thống kê theo Distributor
     */
    public function getStatsByDistributor($filterType = 'all', $filterValue = null) {
        $query = "SELECT 
                    p.email,
                    u.fullname,
                    SUM(o.total_amount * (od.total / (SELECT SUM(od2.total) FROM order_details od2 WHERE od2.order_id = o.id))) as revenue,
                    SUM(od.quantity * p.giaNhap) as cost,
                    COUNT(DISTINCT o.id) as order_count,
                    SUM(od.quantity) as total_sold
                  FROM order_details od
                  JOIN tblsanpham p ON od.product_id = p.masp
                  JOIN tbluser u ON p.email = u.email
                  JOIN orders o ON od.order_id = o.id
                  WHERE o.delivery_status = 'da_nhan_hang'";
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(o.created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = :filterValue";
        }
        $query .= " GROUP BY p.email";
        $stmt = $this->db->prepare($query);
        if ($filterValue) { $stmt->bindParam(':filterValue', $filterValue); }
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as &$r) {
            $rev = (float)($r['revenue'] ?? 0);
            $cost = (float)($r['cost'] ?? 0);
            $r['profit'] = $rev - $cost;
        }
        return $rows;
    }

    /**
     * Thống kê theo người mua (buyer)
     */
    public function getStatsByBuyer($filterType = 'all', $filterValue = null) {
        // Tránh nhân bản tổng tiền khi join order_details: tính trên bảng orders
        $query = "SELECT 
                    o.user_email,
                    u.fullname,
                    SUM(o.total_amount) as revenue,
                    COUNT(*) as order_count
                  FROM orders o
                  LEFT JOIN tbluser u ON o.user_email = u.email
                  WHERE o.delivery_status = 'da_nhan_hang'";
        if ($filterType === 'day' && $filterValue) {
            $query .= " AND DATE(o.created_at) = :filterValue";
        } elseif ($filterType === 'month' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = YEAR(:filterValue) AND MONTH(o.created_at) = MONTH(:filterValue)";
        } elseif ($filterType === 'year' && $filterValue) {
            $query .= " AND YEAR(o.created_at) = :filterValue";
        }
        $query .= " GROUP BY o.user_email";
        $stmt = $this->db->prepare($query);
        if ($filterValue) { $stmt->bindParam(':filterValue', $filterValue); }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>

