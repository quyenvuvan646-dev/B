<?php
class Shipper extends Controller
{
    // Show shipper assignment page
    public function index()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        // Ensure user is logged and has shipper role
        require_once __DIR__ . '/../app/Auth.php';
        if (!isset($_SESSION['user']) || !\Auth::hasRole('shipper')) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        // Load pending orders for initial render (optional)
        $orderModel = $this->model('OrderModel');
        $orders = $orderModel->getPendingOrders();

        $shipperAddress = $_SESSION['shipper_location']['address'] ?? '';
        $this->view('shipperPage', [
            'page' => 'ShipperAssignmentView',
            'orders' => $orders,
            'shipper_address' => $shipperAddress
        ]);
    }

    // Framework expects a Show method as default action; delegate to index
    public function Show()
    {
        return $this->index();
    }

    // Show shipper address form
    public function address()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';
        if (!isset($_SESSION['user']) || !\Auth::hasRole('shipper')) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $this->view('homePage', [
            'page' => 'ShipperAddressView'
        ]);
    }

    // Save shipper address (POST)
    public function saveAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Shipper/address');
            exit();
        }
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $address = trim($_POST['shipper_address'] ?? '');
        if (empty($address)) {
            // Return back with an error (simple flow)
            $_SESSION['shipper_error'] = 'Vui lòng nhập địa chỉ của bạn.';
            header('Location: ' . APP_URL . '/Shipper/address');
            exit();
        }

        // Save in session for now (persistence can be added later)
        $_SESSION['shipper_location'] = [
            'address' => $address,
            'saved_at' => date('Y-m-d H:i:s')
        ];

        // Redirect to shipper dashboard
        header('Location: ' . APP_URL . '/Shipper');
        exit();
    }

    // AJAX endpoint: return only orders with delivery_status = 'da_giao_dvvc' (đã giao cho đơn vị vận chuyển)
    public function getPendingOrders()
    {
        header('Content-Type: application/json');
        $orderModel = $this->model('OrderModel');
        // ✅ Get only orders with delivery_status = 'da_giao_dvvc' (admin đã bàn giao cho đơn vị vận chuyển)
        $orders = $orderModel->getAssignedOrders();
        echo json_encode(['success' => true, 'orders' => $orders]);
        exit();
    }

    // AJAX endpoint: calculate distance from shipper address to customer address using Google Maps Distance Matrix API
    public function calculateDistance()
    {
        header('Content-Type: application/json');

        // Accept shipper_address and customer_address via GET/POST
        $shipper = $_GET['shipper_address'] ?? $_POST['shipper_address'] ?? null;
        $customer = $_GET['customer_address'] ?? $_POST['customer_address'] ?? null;

        if (!$shipper || !$customer) {
            echo json_encode(['success' => false, 'message' => 'Missing addresses']);
            exit();
        }

        try {
            // Get API key from config
            $GOOGLE_MAPS_API_KEY = defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : null;

            // Use Google Maps Distance Matrix API for accurate distance calculation
            if ($GOOGLE_MAPS_API_KEY) {
                $apiUrl = 'https://maps.googleapis.com/maps/api/distancematrix/json?' .
                    'origins=' . urlencode($shipper) . 
                    '&destinations=' . urlencode($customer) .
                    '&key=' . $GOOGLE_MAPS_API_KEY .
                    '&language=vi&region=vn';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($response && $httpCode === 200) {
                    $data = json_decode($response, true);

                    if ($data && isset($data['status']) && $data['status'] === 'OK') {
                        if (isset($data['rows'][0]['elements'][0])) {
                            $element = $data['rows'][0]['elements'][0];

                            // Check if distance is available
                            if (isset($element['status']) && $element['status'] === 'OK' && 
                                isset($element['distance']) && isset($element['distance']['value'])) {
                                $distance_m = $element['distance']['value']; // in meters
                                $distance_km = round($distance_m / 1000, 2);

                                echo json_encode([
                                    'success' => true,
                                    'distance_km' => $distance_km,
                                    'distance_text' => $distance_km . ' km'
                                ]);
                                exit();
                            }
                        }
                    }
                }
            }

            // Fallback if API fails or returns no distance (simple heuristic)
            $districtKeywords = [
                'cầu giấy', 'hoàn kiếm', 'hai bà trưng', 'đống đa', 'ba đình',
                'tây hồ', 'thanh xuân', 'long biên', 'bắc từ liêm', 'nam từ liêm',
                'hà đông', 'thanh trì', 'gia lâm', 'hoài đức', 'thạch thất',
                'quốc oai', 'chương mỹ', 'sơn tây', 'ba vì', 'mỹ đức', 'phúc thọ'
            ];

            $shipperLower = mb_strtolower($shipper, 'UTF-8');
            $customerLower = mb_strtolower($customer, 'UTF-8');
            
            $shipper_district = null;
            $customer_district = null;

            foreach ($districtKeywords as $district) {
                if (strpos($shipperLower, $district) !== false) {
                    $shipper_district = $district;
                    break;
                }
            }
            foreach ($districtKeywords as $district) {
                if (strpos($customerLower, $district) !== false) {
                    $customer_district = $district;
                    break;
                }
            }

            if ($shipper_district && $customer_district && $shipper_district === $customer_district) {
                $distance_km = 0.50; // same district heuristic
            } else {
                $distance_km = 5.0; // default heuristic for different districts
            }

            echo json_encode([
                'success' => true,
                'distance_km' => round($distance_km, 2),
                'distance_text' => round($distance_km, 2) . ' km'
            ]);
            exit();
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }

    // AJAX endpoint: update order delivery status
    public function updateOrderStatus()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit();
        }

        // Get JSON data
        $data = json_decode(file_get_contents('php://input'), true);
        $order_id = $data['order_id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$order_id || !$status) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit();
        }

        // Validate status
        $validStatuses = ['da_nhan_hang', 'da_tra_hang'];
        if (!in_array($status, $validStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            exit();
        }

        try {
            $orderModel = $this->model('OrderModel');
            
            // Update delivery status
            if ($status === 'da_nhan_hang') {
                // Mark order as delivered and mark as paid
                $orderModel->setDeliveryStatusById($order_id, 'da_nhan_hang', null, true);
            } elseif ($status === 'da_tra_hang') {
                // Mark order as returned with reason
                $returnReason = $data['return_reason'] ?? 'Không có lý do';
                $orderModel->markAsReturned($order_id, $returnReason);
                // ✅ Cộng lại số lượng sản phẩm khi shipper trả hàng
                $orderModel->restoreProductQuantity($order_id);
            }

            echo json_encode(['success' => true, 'message' => 'Status updated']);
            exit();
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }
}
