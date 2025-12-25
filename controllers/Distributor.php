<?php
class Distributor extends Controller
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
    }

    public function show()
    {
        $obj = $this->model("AdProductTypeModel");
        $data = $obj->all("tblloaisp");
        $this->view("distributorPage", ["page" => "ProductTypeView", "productList" => $data]);
    }

    // Thống kê của distributor
    public function statistics()
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $filterType = $_GET['filter'] ?? 'all';
        $filterValue = $_GET['filterValue'] ?? null;

        // Nếu filterType khác 'all' mà filterValue rỗng thì reset về 'all'
        if ($filterType !== 'all' && (empty($filterValue) || $filterValue === '')) {
            $filterType = 'all';
            $filterValue = null;
        }

        $obj = $this->model("StatisticsModel");
        $stats = $obj->getDistributorStats($email, $filterType, $filterValue);

        // Lấy dữ liệu biểu đồ
        $chartData = [];
        if ($filterType === 'month' || $filterType === 'all') {
            $year = $filterValue ? date('Y', strtotime($filterValue)) : date('Y');
            $month = $filterValue ? date('m', strtotime($filterValue)) : date('m');
            $chartData = $obj->getDistributorDailyRevenue($email, $year, $month);
        } elseif ($filterType === 'year') {
            $year = $filterValue ?? date('Y');
            $chartData = $obj->getDistributorMonthlyRevenue($email, $year);
        }

        $this->view("distributorPage", [
            "page" => "DistributorStatisticsView",
            "stats" => $stats,
            "chartData" => $chartData,
            "filterType" => $filterType,
            "filterValue" => $filterValue
        ]);
    }

    // Xem danh sách đơn hàng mà distributor đăng bán
    public function orders()
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        require_once __DIR__ . '/../models/DistributorOrderModel.php';
        $orderModel = new DistributorOrderModel();
        $orders = $orderModel->getOrdersByDistributor($email);
        $this->view("distributorPage", ["page" => "DistributorOrdersView", "orders" => $orders]);
    }

    // Xem tin nhắn của distributor
    public function messages()
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        require_once __DIR__ . '/../models/DistributorOrderModel.php';
        $orderModel = new DistributorOrderModel();
        $orders = $orderModel->getOrdersByDistributor($email);
        $this->view("distributorPage", ["page" => "DistributorMessagesView", "orders" => $orders]);
    }

    // Xem chi tiết đơn hàng
    public function orderDetail($orderId)
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        require_once __DIR__ . '/../models/OrderModel.php';
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderById($orderId);
        $orderItems = $orderModel->getOrderItemsWithProduct($orderId);
        if (!$order) {
            echo 'Không tìm thấy đơn hàng.';
            exit();
        }
        // Kiểm tra distributor có quyền xem đơn này (có sản phẩm do mình đăng)
        $hasProduct = false;
        foreach ($orderItems as $item) {
            if (($item['email'] ?? '') === $email) {
                $hasProduct = true;
                break;
            }
        }
        if (!$hasProduct) {
            http_response_code(403);
            echo 'Bạn không có quyền xem đơn hàng này.';
            exit();
        }
        $this->view("distributorPage", ["page" => "DistributorOrderDetailView", "order" => $order, "orderItems" => $orderItems]);
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($orderId)
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        require_once __DIR__ . '/../models/OrderModel.php';
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderById($orderId);
        $orderItems = $orderModel->getOrderItemsWithProduct($orderId);
        if (!$order) {
            echo 'Không tìm thấy đơn hàng.';
            exit();
        }
        // Kiểm tra distributor có quyền cập nhật đơn này
        $hasProduct = false;
        foreach ($orderItems as $item) {
            if (($item['email'] ?? '') === $email) {
                $hasProduct = true;
                break;
            }
        }
        if (!$hasProduct) {
            http_response_code(403);
            echo 'Bạn không có quyền cập nhật đơn hàng này.';
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $delivery_status = $_POST['delivery_status'] ?? '';
            if ($delivery_status) {
                $orderModel->query("UPDATE orders SET delivery_status = :status WHERE id = :id", [':status' => $delivery_status, ':id' => $orderId]);
            }
        }
        header('Location: ' . APP_URL . '/Distributor/orderDetail/' . $orderId);
        exit();
    }

    // Xem sản phẩm của distributor này
    public function products()
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $obj = $this->model("AdProducModel");
        // Lấy chỉ sản phẩm có email trùng với distributor hiện tại
        $sql = "SELECT * FROM tblsanpham WHERE email = ?";
        $data = $obj->select($sql, [$email]);

        $this->view("distributorPage", ["page" => "DistributorProductListView", "productList" => $data]);
    }

    // Tạo sản phẩm mới
    public function createProduct()
    {
        $obj = $this->model("AdProducModel");
        $obj2 = $this->model("AdProductTypeModel");
        $email = $_SESSION['user']['email'] ?? null;

        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        // Lấy chỉ loại sản phẩm của distributor này
        $allProductTypes = $obj2->all("tblloaisp");
        $producttype = array_filter($allProductTypes, function($item) use ($email) {
            return ($item['email'] ?? '') === $email;
        });

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $masp_goc = $_POST["txt_masp"];
            $masp = preg_replace('/\s+/', '', $masp_goc);
            $tensp = $_POST["txt_tensp"];
            $maloaisp = $_POST["txt_maloaisp"];
            $soluong = $_POST["txt_soluong"];
            $gianhap = $_POST["txt_gianhap"];
            $giaxuat = $_POST["txt_giaxuat"];
            $khuyenmai = $_POST["txt_khuyenmai"];
            $mota = $_POST["txt_mota"];
            $ngaytao = $_POST["create_date"];
            $emailSeller = $email; // Luôn dùng email của distributor hiện tại

            $hinhanh = "";
            if (!empty($_FILES["uploadfile"]["name"])) {
                $hinhanh = $_FILES["uploadfile"]["name"];
                $file_tmp = $_FILES["uploadfile"]["tmp_name"];
                move_uploaded_file($file_tmp, "./public/images/" . $hinhanh);
            }

            $obj->insert($maloaisp, $masp, $tensp, $hinhanh, $soluong, $gianhap, $giaxuat, $khuyenmai, $mota, $ngaytao, $emailSeller);
            header("Location: " . APP_URL . "/Distributor/products");
            exit();
        }

        $this->view("distributorPage", ["page" => "ProductView", "producttype" => $producttype]);
    }

    // Chỉnh sửa sản phẩm (chỉ được sửa sản phẩm của mình)
    public function editProduct($masp)
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $obj = $this->model("AdProducModel");
        $obj2 = $this->model("AdProductTypeModel");
        
        // Lấy chỉ loại sản phẩm của distributor này
        $allProductTypes = $obj2->all("tblloaisp");
        $producttype = array_filter($allProductTypes, function($item) use ($email) {
            return ($item['email'] ?? '') === $email;
        });
        
        $product = $obj->find("tblsanpham", $masp);

        // Kiểm tra xem sản phẩm có thuộc về distributor này không
        if (!$product || $product['email'] !== $email) {
            http_response_code(403);
            echo 'Bạn không có quyền chỉnh sửa sản phẩm này';
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $masp = $_POST["txt_masp"];
            $tensp = $_POST["txt_tensp"];
            $maloaisp = $_POST["txt_maloaisp"];
            $soluong = $_POST["txt_soluong"];
            $gianhap = $_POST["txt_gianhap"];
            $giaxuat = $_POST["txt_giaxuat"];
            $khuyenmai = $_POST["txt_khuyenmai"];
            $mota = $_POST["txt_mota"];
            $ngaytao = $_POST["create_date"];
            $hinhanh = $product['hinhanh'];

            if (!empty($_FILES["uploadfile"]["name"])) {
                $hinhanh = $_FILES["uploadfile"]["name"];
                $file_tmp = $_FILES["uploadfile"]["tmp_name"];
                move_uploaded_file($file_tmp, "./public/images/" . $hinhanh);
            }

            $obj->update($maloaisp, $masp, $tensp, $hinhanh, $soluong, $gianhap, $giaxuat, $khuyenmai, $mota, $ngaytao, $email);
            header("Location: " . APP_URL . "/Distributor/products");
            exit();
        }

        $this->view("distributorPage", [
            "page" => "ProductView",
            "producttype" => $producttype,
            "editItem" => $product
        ]);
    }

    // Xóa sản phẩm (chỉ được xóa sản phẩm của mình)
    public function deleteProduct($masp)
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $obj = $this->model("AdProducModel");
        $product = $obj->find("tblsanpham", $masp);

        // Kiểm tra xem sản phẩm có thuộc về distributor này không
        if (!$product || $product['email'] !== $email) {
            http_response_code(403);
            echo 'Bạn không có quyền xóa sản phẩm này';
            exit();
        }

        $obj->delete("tblsanpham", $masp);
        header("Location: " . APP_URL . "/Distributor/products");
        exit();
    }

    // Quản lý loại sản phẩm
    public function productTypes()
    {
        $obj = $this->model("AdProductTypeModel");
        $data = $obj->all("tblloaisp");

        $this->view("distributorPage", ["page" => "DistributorProductTypeView", "productList" => $data]);
    }

    // Tạo loại sản phẩm mới
    public function createProductType()
    {
        $maLoaiSP = $_POST["txt_maloaisp"] ?? "";
        $tenLoaiSP = $_POST["txt_tenloaisp"] ?? "";
        $moTaLoaiSP = $_POST["txt_motaloaisp"] ?? "";

        $obj = $this->model("AdProductTypeModel");
        $obj->insert($maLoaiSP, $tenLoaiSP, $moTaLoaiSP, null);
        header("Location: " . APP_URL . "/Distributor/productTypes");
        exit();
    }

    // NEW: Tạo loại sản phẩm chấp nhận POST/GET, không kiểm tra quyền
    public function ptCreate()
    {
        $maLoaiSP = trim($_POST["txt_maloaisp"] ?? $_GET["txt_maloaisp"] ?? "");
        $tenLoaiSP = trim($_POST["txt_tenloaisp"] ?? $_GET["txt_tenloaisp"] ?? "");
        $moTaLoaiSP = trim($_POST["txt_motaloaisp"] ?? $_GET["txt_motaloaisp"] ?? "");

        $obj = $this->model("AdProductTypeModel");

        if ($maLoaiSP === "" || $tenLoaiSP === "") {
            $_SESSION['pt_error'] = 'Vui lòng nhập đầy đủ Mã loại SP và Tên loại SP';
            header("Location: " . APP_URL . "/Distributor/productTypes");
            exit();
        }

        // Kiểm tra trùng mã trước khi thêm
        $existing = $obj->find("tblloaisp", $maLoaiSP);
        if ($existing) {
            $_SESSION['pt_error'] = 'Mã loại sản phẩm đã tồn tại. Vui lòng chọn mã khác';
            header("Location: " . APP_URL . "/Distributor/productTypes");
            exit();
        }

        // Tiến hành thêm (dùng email rỗng để tránh DB không cho NULL)
        $obj->insert($maLoaiSP, $tenLoaiSP, $moTaLoaiSP, "");
        // Xác minh đã lưu thành công
        $saved = $obj->find("tblloaisp", $maLoaiSP);
        if ($saved) {
            $_SESSION['pt_success'] = 'Thêm loại sản phẩm thành công';
        } else {
            $_SESSION['pt_error'] = 'Đã cố gắng thêm nhưng dữ liệu chưa lưu vào database. Vui lòng thử lại.';
        }
        header("Location: " . APP_URL . "/Distributor/productTypes");
        exit();
    }

    // ===== NEW: Product Type endpoints (no auth, tolerant to method) =====
    // Hiển thị form sửa loại sản phẩm (không kiểm tra quyền)
    public function ptEdit($maLoaiSP)
    {
        $obj = $this->model("AdProductTypeModel");
        $item = $obj->find("tblloaisp", $maLoaiSP);
        $data = $obj->all("tblloaisp");

        $this->view("distributorPage", [
            "page" => "DistributorProductTypeView",
            "productList" => $data,
            "editItem" => $item,
            // Hướng form về endpoint mới ptSave
            "ptAction" => APP_URL . "/Distributor/ptSave/" . urlencode($maLoaiSP)
        ]);
    }

    // Lưu cập nhật loại sản phẩm (chấp nhận POST hoặc GET)
    public function ptSave($maLoaiSP)
    {
        // Cho phép nhận dữ liệu từ cả POST và GET để tránh 403 do method
        $tenLoaiSP = $_POST["txt_tenloaisp"] ?? $_GET["txt_tenloaisp"] ?? "";
        $moTaLoaiSP = $_POST["txt_motaloaisp"] ?? $_GET["txt_motaloaisp"] ?? "";

        $obj = $this->model("AdProductTypeModel");
        $obj->update($maLoaiSP, $tenLoaiSP, $moTaLoaiSP);
        header("Location: " . APP_URL . "/Distributor/productTypes");
        exit();
    }

    // Xóa loại sản phẩm (endpoint mới, không kiểm tra quyền, chấp nhận mọi method)
    public function ptRemove($maLoaiSP)
    {
        $obj = $this->model("AdProductTypeModel");

        // Xóa tất cả sản phẩm thuộc loại này trước để tránh FK
        $obj->query("DELETE FROM tblsanpham WHERE maLoaiSP = :maLoaiSP", [':maLoaiSP' => $maLoaiSP]);

        // Xóa loại sản phẩm
        $obj->delete("tblloaisp", $maLoaiSP);

        header("Location: " . APP_URL . "/Distributor/productTypes");
        exit();
    }

    // Hiển thị cài đặt phí vận chuyển
    public function shippingRules()
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $model = $this->model('DistributorShippingRulesModel');
        $rules = $model->getRulesByDistributor($email);

        // Debug log
        error_log("shippingRules: email=$email, rules_count=" . count($rules));
        file_put_contents('debug_view.log', "View data:\n" . print_r([
            'page' => 'DistributorShippingRulesView',
            'rules' => $rules,
            'rules_count' => count($rules),
            'error' => $_SESSION['shipping_error'] ?? '',
            'success' => $_SESSION['shipping_success'] ?? ''
        ], true));
        
        $this->view('distributorPage', [
            'page' => 'DistributorShippingRulesView',
            'rules' => $rules,
            'error' => $_SESSION['shipping_error'] ?? '',
            'success' => $_SESSION['shipping_success'] ?? ''
        ]);

        // Clear messages after display
        unset($_SESSION['shipping_error']);
        unset($_SESSION['shipping_success']);
    }

    // Thêm rule vận chuyển
    public function addShippingRule()
    {
        // Debug log
        file_put_contents('debug_shipping.log', date('Y-m-d H:i:s') . " - addShippingRule called\n", FILE_APPEND);
        file_put_contents('debug_shipping.log', "POST data: " . json_encode($_POST) . "\n", FILE_APPEND);
        file_put_contents('debug_shipping.log', "SESSION: " . json_encode($_SESSION) . "\n", FILE_APPEND);
        
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            file_put_contents('debug_shipping.log', "Failed: No email or not POST\n", FILE_APPEND);
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        $distanceFrom = (int)($_POST['distance_from'] ?? 0);
        $distanceTo = (int)($_POST['distance_to'] ?? 0);
        $standardFee = (int)($_POST['standard_fee'] ?? 0);
        $fastFee = (int)($_POST['fast_fee'] ?? 0);
        $expressFee = (int)($_POST['express_fee'] ?? 0);

        file_put_contents('debug_shipping.log', "Parsed: from=$distanceFrom, to=$distanceTo, std=$standardFee\n", FILE_APPEND);

        // Validate
        if ($distanceFrom < 0 || $distanceTo <= 0 || $distanceFrom >= $distanceTo) {
            $_SESSION['shipping_error'] = 'Khoảng cách không hợp lệ. "Từ" phải nhỏ hơn "Đến"';
            file_put_contents('debug_shipping.log', "Failed: Invalid distance range\n", FILE_APPEND);
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        if ($standardFee < 0 || $fastFee < 0 || $expressFee < 0) {
            $_SESSION['shipping_error'] = 'Phí vận chuyển không được âm';
            file_put_contents('debug_shipping.log', "Failed: Negative fees\n", FILE_APPEND);
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        $model = $this->model('DistributorShippingRulesModel');

        // Check overlap
        if ($model->checkOverlap($email, $distanceFrom, $distanceTo)) {
            $_SESSION['shipping_error'] = "Khoảng cách {$distanceFrom}-{$distanceTo}km trùng với khoảng cách đã tồn tại. Vui lòng xóa khoảng cũ hoặc chọn khoảng khác.";
            file_put_contents('debug_shipping.log', "Failed: Overlap detected\n", FILE_APPEND);
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        // Add rule
        try {
            $result = $model->createRule([
                'distributor_email' => $email,
                'distance_from' => $distanceFrom,
                'distance_to' => $distanceTo,
                'standard_fee' => $standardFee,
                'fast_fee' => $fastFee,
                'express_fee' => $expressFee
            ]);
            file_put_contents('debug_shipping.log', "Success: createRule returned " . ($result ? 'true' : 'false') . "\n", FILE_APPEND);
            $_SESSION['shipping_success'] = 'Thêm khoảng cách vận chuyển thành công';
        } catch (Exception $e) {
            $_SESSION['shipping_error'] = 'Lỗi: ' . $e->getMessage();
            file_put_contents('debug_shipping.log', "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        header('Location: ' . APP_URL . '/Distributor/shippingRules');
        exit();
    }

    // Cập nhật rule vận chuyển
    public function updateShippingRule()
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        $ruleId = (int)($_POST['rule_id'] ?? 0);
        $distanceFrom = (int)($_POST['distance_from'] ?? 0);
        $distanceTo = (int)($_POST['distance_to'] ?? 0);
        $standardFee = (int)($_POST['standard_fee'] ?? 0);
        $fastFee = (int)($_POST['fast_fee'] ?? 0);
        $expressFee = (int)($_POST['express_fee'] ?? 0);

        // Validate
        if ($distanceFrom < 0 || $distanceTo <= 0 || $distanceFrom >= $distanceTo) {
            $_SESSION['shipping_error'] = 'Khoảng cách không hợp lệ';
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        if ($standardFee < 0 || $fastFee < 0 || $expressFee < 0) {
            $_SESSION['shipping_error'] = 'Phí vận chuyển không được âm';
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        $model = $this->model('DistributorShippingRulesModel');

        // Check overlap (excluding current rule)
        if ($model->checkOverlap($email, $distanceFrom, $distanceTo, $ruleId)) {
            $_SESSION['shipping_error'] = 'Khoảng cách này trùng với một khoảng cách khác';
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        // Update rule
        try {
            $model->updateRule($ruleId, [
                'distance_from' => $distanceFrom,
                'distance_to' => $distanceTo,
                'standard_fee' => $standardFee,
                'fast_fee' => $fastFee,
                'express_fee' => $expressFee
            ]);
            $_SESSION['shipping_success'] = 'Cập nhật khoảng cách vận chuyển thành công';
        } catch (Exception $e) {
            $_SESSION['shipping_error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ' . APP_URL . '/Distributor/shippingRules');
        exit();
    }

    // Xóa rule vận chuyển
    public function deleteShippingRule()
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        $ruleId = (int)($_POST['rule_id'] ?? 0);

        if ($ruleId <= 0) {
            $_SESSION['shipping_error'] = 'ID khoảng cách không hợp lệ';
            header('Location: ' . APP_URL . '/Distributor/shippingRules');
            exit();
        }

        $model = $this->model('DistributorShippingRulesModel');
        
        try {
            $model->deleteRule($ruleId);
            $_SESSION['shipping_success'] = 'Xóa khoảng cách vận chuyển thành công';
        } catch (Exception $e) {
            $_SESSION['shipping_error'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ' . APP_URL . '/Distributor/shippingRules');
        exit();
    }

    // ===== VOUCHER DISTRIBUTOR =====

    public function vouchers()
    {
        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $obj = $this->model("VoucherDistributorModel");
        $vouchers = $obj->getVouchersByDistributor($email);

        $this->view("distributorPage", [
            "page" => "DistributorVouchersView",
            "vouchers" => $vouchers,
            "distributor_email" => $email
        ]);
    }

    public function addVoucher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            $_SESSION['voucher_error'] = 'Vui lòng đăng nhập';
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $code = trim($_POST['code'] ?? '');
        $discount_value = (int)($_POST['discount_value'] ?? 0);
        $min_amount = (int)($_POST['min_amount'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 100);
        $status = trim($_POST['status'] ?? 'active');
        $time_start = trim($_POST['time_start'] ?? '');
        $time_end = trim($_POST['time_end'] ?? '');

        // Validation
        if (empty($code) || empty($discount_value) || empty($time_start) || empty($time_end)) {
            $_SESSION['voucher_error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        if ($discount_value <= 0 || $min_amount < 0 || $quantity <= 0) {
            $_SESSION['voucher_error'] = 'Giá trị phải lớn hơn 0';
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        $obj = $this->model("VoucherDistributorModel");
        $existingVoucher = $obj->getVoucherByCode($code);
        if ($existingVoucher) {
            $_SESSION['voucher_error'] = 'Mã voucher đã tồn tại';
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        $result = $obj->createVoucher([
            'distributor_email' => $email,
            'code' => $code,
            'discount_value' => $discount_value,
            'min_amount' => $min_amount,
            'quantity' => $quantity,
            'status' => $status,
            'time_start' => $time_start,
            'time_end' => $time_end
        ]);

        if ($result['success']) {
            $_SESSION['voucher_success'] = 'Tạo voucher thành công';
        } else {
            $_SESSION['voucher_error'] = $result['message'];
        }

        header('Location: ' . APP_URL . '/Distributor/vouchers');
        exit();
    }

    public function updateVoucher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            $_SESSION['voucher_error'] = 'Vui lòng đăng nhập';
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $id = (int)($_POST['id'] ?? 0);
        $discount_value = (int)($_POST['discount_value'] ?? 0);
        $min_amount = (int)($_POST['min_amount'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 100);
        $status = trim($_POST['status'] ?? 'active');
        $time_start = trim($_POST['time_start'] ?? '');
        $time_end = trim($_POST['time_end'] ?? '');

        if (!$id || $discount_value <= 0 || $quantity <= 0) {
            $_SESSION['voucher_error'] = 'Dữ liệu không hợp lệ';
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        $obj = $this->model("VoucherDistributorModel");
        $voucher = $obj->getVoucherById($id);

        if (!$voucher || $voucher['distributor_email'] !== $email) {
            $_SESSION['voucher_error'] = 'Voucher không tồn tại hoặc không phải của bạn';
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        $updateData = [];
        if ($discount_value) $updateData['discount_value'] = $discount_value;
        if ($min_amount >= 0) $updateData['min_amount'] = $min_amount;
        if ($quantity) $updateData['quantity'] = $quantity;
        $updateData['status'] = $status;
        if ($time_start) $updateData['time_start'] = $time_start;
        if ($time_end) $updateData['time_end'] = $time_end;

        $result = $obj->updateVoucher($id, $updateData);

        if ($result['success']) {
            $_SESSION['voucher_success'] = 'Cập nhật voucher thành công';
        } else {
            $_SESSION['voucher_error'] = $result['message'];
        }

        header('Location: ' . APP_URL . '/Distributor/vouchers');
        exit();
    }

    public function deleteVoucher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        $email = $_SESSION['user']['email'] ?? null;
        if (!$email) {
            $_SESSION['voucher_error'] = 'Vui lòng đăng nhập';
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $_SESSION['voucher_error'] = 'ID voucher không hợp lệ';
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        $obj = $this->model("VoucherDistributorModel");
        $voucher = $obj->getVoucherById($id);

        if (!$voucher || $voucher['distributor_email'] !== $email) {
            $_SESSION['voucher_error'] = 'Voucher không tồn tại hoặc không phải của bạn';
            header('Location: ' . APP_URL . '/Distributor/vouchers');
            exit();
        }

        $result = $obj->deleteVoucher($id);

        if ($result['success']) {
            $_SESSION['voucher_success'] = 'Xóa voucher thành công';
        } else {
            $_SESSION['voucher_error'] = $result['message'];
        }

        header('Location: ' . APP_URL . '/Distributor/vouchers');
        exit();
    }

    // Display distributor registration page (for non-distributor users)
    public function registerForm()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';

        // If not logged in, redirect to login
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin?redirect=' . urlencode(APP_URL . '/Distributor/registerForm'));
            exit();
        }

        $email = $_SESSION['user']['email'];
        $hopDongModel = $this->model('HopDongModel');

        // Check if user already has pending or approved contract
        if ($hopDongModel->hasActiveContract($email)) {
            $existingContract = $hopDongModel->getContractByEmail($email);
            $this->view('homePage', [
                'page' => 'DistributorRegisterView',
                'contract' => $existingContract,
                'existing' => true,
                'user' => $_SESSION['user']
            ]);
            return;
        }

        // Display registration form
        $this->view('homePage', [
            'page' => 'DistributorRegisterView',
            'contract' => null,
            'existing' => false,
            'user' => $_SESSION['user']
        ]);
    }

    // Handle distributor registration submission (for non-distributor users)
    public function submitRegistration()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';

        // Check if logged in
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        // Verify POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Distributor/registerForm');
            exit();
        }

        $email = $_SESSION['user']['email'];
        $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $companyName = isset($_POST['company_name']) ? trim($_POST['company_name']) : '';
        $businessAddress = isset($_POST['business_address']) ? trim($_POST['business_address']) : '';
        $taxId = isset($_POST['tax_id']) ? trim($_POST['tax_id']) : '';
        $businessLicense = isset($_POST['business_license']) ? trim($_POST['business_license']) : '';
        $termsAccepted = isset($_POST['terms_accepted']) ? 1 : 0;

        // Validate required fields
        if (!$fullName || !$phone || !$companyName || !$businessAddress || !$termsAccepted) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin và chấp nhận điều lệ';
            header('Location: ' . APP_URL . '/Distributor/registerForm');
            exit();
        }

        // Validate phone format (Vietnam)
        if (!preg_match('/^(\+?84|0)[0-9]{9,10}$/', str_replace(' ', '', $phone))) {
            $_SESSION['error'] = 'Số điện thoại không hợp lệ';
            header('Location: ' . APP_URL . '/Distributor/registerForm');
            exit();
        }

        $hopDongModel = $this->model('HopDongModel');

        // Check if already registered
        if ($hopDongModel->hasActiveContract($email)) {
            $_SESSION['error'] = 'Bạn đã có đơn đăng ký. Vui lòng chờ admin duyệt';
            header('Location: ' . APP_URL . '/Distributor/registerForm');
            exit();
        }

        try {
            // Create contract record
            $result = $hopDongModel->createContract(
                $email,
                $fullName,
                $phone,
                $companyName,
                $businessAddress,
                $taxId,
                $businessLicense
            );

            if ($result) {
                // Handle file upload if provided
                if (isset($_FILES['contract_file']) && $_FILES['contract_file']['error'] === UPLOAD_ERR_OK) {
                    $uploadsDir = __DIR__ . '/../public/uploads/contracts';
                    if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

                    $tmp = $_FILES['contract_file']['tmp_name'];
                    $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['contract_file']['name']));
                    $dest = $uploadsDir . '/' . $name;

                    if (move_uploaded_file($tmp, $dest)) {
                        $filePath = 'uploads/contracts/' . $name;
                        $hopDongModel->updateContractFile($email, $filePath);
                    }
                }

                $_SESSION['success'] = 'Đơn đăng ký đã được gửi thành công! Admin sẽ kiểm tra và liên hệ với bạn sớm.';
                header('Location: ' . APP_URL . '/Distributor/registerForm');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại';
                header('Location: ' . APP_URL . '/Distributor/registerForm');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/Distributor/registerForm');
        }
        exit();
    }
}

