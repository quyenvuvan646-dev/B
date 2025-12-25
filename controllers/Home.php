<?php
class Home extends Controller
{
    // Hiển thị lịch sử đơn hàng cho người dùng đã đăng nhập
    public function orderHistory()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        $orderModel = $this->model('OrderModel');
        // Auto-cancel online payment orders that remain unpaid for more than 30 minutes
        try {
            $orderModel->autoCancelStaleOnlineOrders(30);
        } catch (Exception $e) {
            // ignore silently if auto-cancel fails
        }

        $orders = $orderModel->getOrdersByEmail($_SESSION['user']['email']);
        $this->view('homePage', [
            'page' => 'OrderHistoryView',
            'orders' => $orders
        ]);
    }
    // Lưu thông tin giao hàng, hóa đơn và chi tiết hóa đơn


    public  function show()
    {
        // Ensure session and auth helper available
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';

        $obj = $this->model("AdProducModel");
        $data = $obj->all("tblsanpham");

        // Load banners for slider
        $bannerModel = $this->model("BannerModel");
        $banners = $bannerModel->getActiveBanners();

        // If admin, redirect to admin dashboard
        if (Auth::hasRole('admin')) {
            header('Location: ' . APP_URL . '/Admin');
            exit();
        }

        // If distributor, redirect to distributor dashboard
        if (Auth::hasRole('distributor')) {
            header('Location: ' . APP_URL . '/Distributor');
            exit();
        }

        // If shipper, show shipper top-level page (shipperPage)
        if (Auth::hasRole('shipper')) {
            $this->view("shipperPage", ["page" => "ShipperHomeView", "productList" => $data, "banners" => $banners]);
            return;
        }

        // Default: show landing page without product list
        $this->view("homePage", ["page" => "LandingView", "productList" => [], "banners" => $banners]);
    }

    // Hiển thị danh sách sản phẩm cho khách/khách mua (không cần query string)
    public function products()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';

        // Check for search parameter
        $searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $searchType = isset($_GET['type']) ? trim($_GET['type']) : 'products'; // 'products' or 'shops'
        $distributorEmail = isset($_GET['distributor']) ? trim($_GET['distributor']) : ''; // Filter by distributor
        
        $obj = $this->model("AdProducModel");
        $data = [];
        $shopsData = [];
        
        // If distributor email is specified, get all products from that distributor
        if ($distributorEmail !== '') {
            $data = $obj->all("tblsanpham");
            $data = array_filter($data, function($product) use ($distributorEmail) {
                return isset($product['email']) && $product['email'] === $distributorEmail;
            });
        } else if ($searchKeyword !== '') {
            // If search keyword exists (and no distributor filter), get search results based on type
            if ($searchType === 'shops') {
                $shopsData = $obj->searchShops($searchKeyword);
            } else {
                $data = $obj->searchProducts($searchKeyword);
            }
        } else {
            // Default: all products
            $data = $obj->all("tblsanpham");
        }

        // Favorites map for current user
        $favoritesMap = [];
        $currentUser = Auth::user();
        if ($currentUser && isset($currentUser['email'])) {
            require_once __DIR__ . '/../models/FavoriteModel.php';
            $favModel = new FavoriteModel();
            $favoriteCodes = $favModel->getFavoriteCodes($currentUser['email']);
            foreach ($favoriteCodes as $code) {
                $favoritesMap[$code] = true;
            }
        }

        // Load banners for slider
        $bannerModel = $this->model("BannerModel");
        $banners = $bannerModel->getActiveBanners();

        if (Auth::hasRole('admin')) {
            header('Location: ' . APP_URL . '/Admin');
            exit();
        }

        if (Auth::hasRole('distributor')) {
            header('Location: ' . APP_URL . '/Distributor');
            exit();
        }

        if (Auth::hasRole('shipper')) {
            $this->view("shipperPage", ["page" => "ShipperHomeView", "productList" => $data, "banners" => $banners]);
            return;
        }

        $availableProducts = array_filter($data, function($product) {
            return intval($product['soluong']) > 0;
        });

        $availableProducts = $this->attachRatings($availableProducts);
        $availableProducts = $this->attachPromotions($availableProducts);

        // ✅ Apply filters and sorting (unless searching)
        if ($searchKeyword === '') {
            $availableProducts = $this->applyProductFilters($availableProducts);
        }

        // Default sort by rating (bán chạy nhất), or by relevance if searching
        $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'default';
        
        if ($searchKeyword !== '') {
            // If searching products, sort by relevance first, then rating
            usort($availableProducts, function ($a, $b) {
                $relA = $a['relevance'] ?? 999;
                $relB = $b['relevance'] ?? 999;
                if ($relA !== $relB) {
                    return $relA - $relB;
                }
                $ra = $a['avg_rating'] ?? 0;
                $rb = $b['avg_rating'] ?? 0;
                if ($ra === $rb) return 0;
                return ($ra > $rb) ? -1 : 1;
            });
        } else {
            // Normal sorting without search
            // 1) Prioritize promotional products to the top by discount percent
            usort($availableProducts, function ($a, $b) {
                $ap = ($a['has_promotion'] ?? false) ? 1 : 0;
                $bp = ($b['has_promotion'] ?? false) ? 1 : 0;
                if ($ap !== $bp) return $bp - $ap; // promos first
                $ad = (float)($a['discount_percent'] ?? 0);
                $bd = (float)($b['discount_percent'] ?? 0);
                if ($ad != $bd) return ($bd <=> $ad); // higher discount first
                // tie-breaker: by rating desc
                $ra = (float)($a['avg_rating'] ?? 0);
                $rb = (float)($b['avg_rating'] ?? 0);
                if ($ra != $rb) return ($rb <=> $ra);
                return 0;
            });

            // 2) Apply user-selected sort (consider discounted price when applicable)
            switch ($sort) {
                case 'rating':
                    usort($availableProducts, function ($a, $b) {
                        $ra = (float)($a['avg_rating'] ?? 0);
                        $rb = (float)($b['avg_rating'] ?? 0);
                        if ($ra === $rb) return 0;
                        return ($ra > $rb) ? -1 : 1;
                    });
                break;
                case 'price_low':
                    usort($availableProducts, function ($a, $b) {
                        $pa0 = (float)($a['giaXuat'] ?? 0);
                        $pb0 = (float)($b['giaXuat'] ?? 0);
                        $da = (float)($a['discount_percent'] ?? 0);
                        $db = (float)($b['discount_percent'] ?? 0);
                        $pa = $pa0 * (1 - $da/100.0);
                        $pb = $pb0 * (1 - $db/100.0);
                        if ($pa === $pb) return 0;
                        return ($pa < $pb) ? -1 : 1;
                    });
                break;
                case 'price_high':
                    usort($availableProducts, function ($a, $b) {
                        $pa0 = (float)($a['giaXuat'] ?? 0);
                        $pb0 = (float)($b['giaXuat'] ?? 0);
                        $da = (float)($a['discount_percent'] ?? 0);
                        $db = (float)($b['discount_percent'] ?? 0);
                        $pa = $pa0 * (1 - $da/100.0);
                        $pb = $pb0 * (1 - $db/100.0);
                        if ($pa === $pb) return 0;
                        return ($pa > $pb) ? -1 : 1;
                    });
                break;
                case 'newest':
                    usort($availableProducts, function ($a, $b) {
                        $da = strtotime($a['createDate'] ?? '1970-01-01');
                        $db = strtotime($b['createDate'] ?? '1970-01-01');
                        if ($da === $db) return 0;
                        return ($da > $db) ? -1 : 1;
                    });
                break;
                default:
                    // already prioritized by promotion and rating above
                break;
            }
        }

        // Determine which view to use
        if ($searchKeyword !== '') {
            $page = $searchType === 'shops' ? "ShopListView" : "ProductListView";
        } else {
            $page = "HomeView";
        }

        // Use ProductListView or ShopListView with homePage layout
        // ✅ Lấy danh sách loại sản phẩm
        $typeModel = $this->model("AdProductTypeModel");
        $allProductTypes = $typeModel->all("tblloaisp");
        
        $this->view("homePage", [
            "page" => $page, 
            "productList" => $availableProducts,
            "shopsData" => $shopsData,
            "searchTerm" => $searchKeyword,
            "searchType" => $searchType,
            "banners" => $banners,
            "favoritesMap" => $favoritesMap,
            "allProductTypes" => $allProductTypes
        ]);
    }

    // Trang landing giới thiệu/marketing
    public function landing()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $this->view("homePage", ["page" => "LandingView", "productList" => []]);
    }

    // Tìm kiếm sản phẩm hoặc shop theo từ khóa
    public function search()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';

        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $searchType = isset($_GET['search_type']) ? trim($_GET['search_type']) : 'products';

        // Nếu không có từ khóa, quay lại trang products
        if ($keyword === '') {
            header('Location: ' . APP_URL . '/Home/products');
            exit();
        }

        // Redirect tới /Home/products với search parameter để giữ URL
        $typeParam = ($searchType === 'shops') ? '&type=shops' : '';
        header('Location: ' . APP_URL . '/Home/products?search=' . urlencode($keyword) . $typeParam);
        exit();
    }

    // 🏪 Xem chi tiết shop
    public function shopDetail($shopEmail = null)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';

        if (!$shopEmail) {
            header('Location: ' . APP_URL . '/Home/products');
            exit();
        }

        $shopEmail = urldecode($shopEmail);

        // Lấy thông tin shop
        require_once __DIR__ . '/../app/DB.php';
        $db = new DB();

        $sqlShop = "SELECT user_id, email, fullname, phone, address FROM tbluser WHERE email = :email AND user_role = 4 AND (is_locked IS NULL OR is_locked = 0) LIMIT 1";
        $stmtShop = $db->getDB()->prepare($sqlShop);
        $stmtShop->bindParam(':email', $shopEmail);
        $stmtShop->execute();
        $shop = $stmtShop->fetch(PDO::FETCH_ASSOC);

        if (!$shop) {
            header('Location: ' . APP_URL . '/Home/products');
            exit();
        }

        // Lấy sản phẩm của shop
        $sqlProducts = "SELECT * FROM tblsanpham WHERE email = :email ORDER BY createDate DESC";
        $stmtProducts = $db->getDB()->prepare($sqlProducts);
        $stmtProducts->bindParam(':email', $shopEmail);
        $stmtProducts->execute();
        $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

        // Lấy rating trung bình
        $sqlAvgRating = "SELECT ROUND(AVG(c.rating), 2) as avg_rating, COUNT(c.id) as review_count
                        FROM comments c
                        JOIN tblsanpham p ON c.masp = p.masp
                        WHERE p.email = :email AND c.rating IS NOT NULL";
        $stmtRating = $db->getDB()->prepare($sqlAvgRating);
        $stmtRating->bindParam(':email', $shopEmail);
        $stmtRating->execute();
        $ratings = $stmtRating->fetch(PDO::FETCH_ASSOC);

        // Gắn rating cho từng sản phẩm
        $products = $this->attachRatings($products);
        $products = $this->attachPromotions($products);

        $shop['avg_rating'] = $ratings['avg_rating'] ?? 0;
        $shop['review_count'] = $ratings['review_count'] ?? 0;
        $shop['product_count'] = count($products);

        $this->view('homePage', [
            'page' => 'ShopDetailView',
            'shop' => $shop,
            'products' => $products
        ]);
    }

    // Gắn sao trung bình và số lượt đánh giá cho danh sách sản phẩm
    private function attachRatings($products)
    {
        if (empty($products)) return $products;

        $ids = array_column($products, 'masp');
        if (empty($ids)) return $products;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT masp, AVG(rating) AS avg_rating, COUNT(*) AS rating_count FROM comments WHERE rating IS NOT NULL AND is_visible = 1 AND is_deleted = 0 AND masp IN ($placeholders) GROUP BY masp";

        $commentModel = $this->model('CommentModel');
        $rows = $commentModel->select($sql, $ids);
        $map = [];
        foreach ($rows as $r) {
            $map[$r['masp']] = [
                'avg_rating' => round((float)$r['avg_rating'], 1),
                'rating_count' => (int)$r['rating_count']
            ];
        }

        foreach ($products as &$p) {
            $p['avg_rating'] = $map[$p['masp']]['avg_rating'] ?? 0;
            $p['rating_count'] = $map[$p['masp']]['rating_count'] ?? 0;
        }
        unset($p);

        return $products;
    }

    // Attach promotion information to products
    private function attachPromotions($products)
    {
        if (empty($products)) return $products;

        $ids = array_column($products, 'masp');
        if (empty($ids)) return $products;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT masp, MAX(phantram) AS phantram FROM khuyenmai WHERE masp IN ($placeholders) GROUP BY masp";

        require_once __DIR__ . '/../app/DB.php';
        $db = new DB();
        $stmt = $db->getDB()->prepare($sql);
        $stmt->execute($ids);
        $promoProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $promoMap = [];
        foreach ($promoProducts as $p) {
            $promoMap[$p['masp']] = [
                'has_promotion' => true,
                'discount_percent' => (float)($p['phantram'] ?? 0)
            ];
        }

        foreach ($products as &$p) {
            if (isset($promoMap[$p['masp']])) {
                $p['has_promotion'] = true;
                $p['discount_percent'] = $promoMap[$p['masp']]['discount_percent'];
            } else {
                $p['has_promotion'] = false;
                $p['discount_percent'] = 0;
            }
        }
        unset($p);

        return $products;
    }

    // ✅ Apply product filters (price, rating, stock)
    private function applyProductFilters($products)
    {
        $filtered = $products;

        // Filter by price ranges
        if (isset($_GET['price'])) {
            $priceRanges = is_array($_GET['price']) ? $_GET['price'] : [$_GET['price']];
            
            $filtered = array_filter($filtered, function($product) use ($priceRanges) {
                $price = intval($product['giaXuat'] ?? 0);
                
                foreach ($priceRanges as $range) {
                    $range = trim($range);
                    if (empty($range)) continue;
                    
                    if (strpos($range, '-') === false) continue;
                    
                    $parts = explode('-', $range);
                    $min = intval($parts[0] ?? 0);
                    $max = empty($parts[1]) ? PHP_INT_MAX : intval($parts[1]);
                    
                    if ($price >= $min && $price <= $max) {
                        return true;
                    }
                }
                return false;
            });
        }

        // Filter by rating
        if (isset($_GET['rating'])) {
            $minRatings = is_array($_GET['rating']) ? $_GET['rating'] : [$_GET['rating']];
            // Get the minimum rating among selected options
            $minRating = !empty($minRatings) ? min(array_map('floatval', $minRatings)) : 0;
            
            $filtered = array_filter($filtered, function($product) use ($minRating) {
                return ($product['avg_rating'] ?? 0) >= $minRating;
            });
        }

        // Filter by stock (in stock only)
        if (isset($_GET['stock']) && $_GET['stock'] === 'instock') {
            $filtered = array_filter($filtered, function($product) {
                return intval($product['soluong'] ?? 0) > 0;
            });
        }

        // Filter by product type (mã loại sản phẩm)
        if (isset($_GET['product_type'])) {
            $selectedTypes = is_array($_GET['product_type']) ? $_GET['product_type'] : [$_GET['product_type']];
            // Remove empty values
            $selectedTypes = array_filter($selectedTypes, function($type) {
                return !empty(trim($type));
            });
            
            if (!empty($selectedTypes)) {
                $filtered = array_filter($filtered, function($product) use ($selectedTypes) {
                    return in_array($product['maloaisp'] ?? '', $selectedTypes, true);
                });
            }
        }

        return $filtered;
    }

    public function detail($masp)
    {
        $discountModel = $this->model("AdKhuyenMai");
        $data = $discountModel->findWithDiscount($masp);

        if (!empty($data["phantram"])) {
            $data["gia_khuyenmai"] = $data["giaXuat"] * (1 - $data["phantram"] / 100);
        } else {
            $data["gia_khuyenmai"] = $data["giaXuat"];
        }

        // Load comments and permission to review
        $commentModel = $this->model('CommentModel');
        $comments = $commentModel->getCommentsByProduct($masp);

        $canReview = false;
        $hasReviewed = false;
        if (isset($_SESSION['user'])) {
            $email = $_SESSION['user']['email'];
            $hasReviewed = $commentModel->userHasReviewedProduct($email, $masp);
            $canReview = $commentModel->userHasBoughtProduct($email, $masp) && !$hasReviewed;
        }

        $this->view("homePage", ["page" => "DetailView", "product" => $data, 'comments' => $comments, 'canReview' => $canReview, 'hasReviewed' => $hasReviewed]);
    }

    // Trang so sánh sản phẩm dành cho user
    public function compare()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        // Danh sách tất cả sản phẩm cho dropdown
        $productModel = $this->model('AdProducModel');
        $allProducts = $productModel->all('tblsanpham');

        // Lấy 2 mã sản phẩm cần so sánh từ query string
        $id1 = isset($_GET['product1']) ? trim($_GET['product1']) : null;
        $id2 = isset($_GET['product2']) ? trim($_GET['product2']) : null;

        // Nếu chưa chọn gì, tự động chọn 2 sản phẩm đầu tiên để hiển thị mặc định
        if ((!$id1 || !$id2) && is_array($allProducts) && count($allProducts) >= 2) {
            $id1 = $id1 ?: ($allProducts[0]['masp'] ?? null);
            $id2 = $id2 ?: ($allProducts[1]['masp'] ?? null);
        }

        $p1 = null; $p2 = null;

        // Helpers
        $discountModel = $this->model('AdKhuyenMai');
        $commentModel  = $this->model('CommentModel');

        $enrich = function($prod) use ($discountModel, $commentModel) {
            if (!$prod) return null;
            $masp = $prod['masp'];

            // Giá sau khuyến mãi (ưu tiên bảng khuyến mãi đang hiệu lực)
            $withDiscount = $discountModel->findWithDiscount($masp);
            $phantram = (float)($withDiscount['phantram'] ?? ($prod['khuyenmai'] ?? 0));
            $giaGoc = (int)($prod['giaXuat'] ?? 0);
            $giaKM = (int) round($giaGoc * (1 - $phantram / 100));

            // Số sao trung bình
            $rows = $commentModel->select(
                "SELECT AVG(rating) AS avg_rating, COUNT(*) AS cnt FROM comments WHERE masp = ? AND rating IS NOT NULL AND is_visible = 1 AND is_deleted = 0",
                [$masp]
            );
            $avgRating = isset($rows[0]['avg_rating']) ? round((float)$rows[0]['avg_rating'], 1) : 0.0;
            $ratingCount = (int)($rows[0]['cnt'] ?? 0);

            // Số lượt mua (đã giao thành công)
            $sold = $commentModel->select(
                "SELECT SUM(od.quantity) AS qty FROM order_details od JOIN orders o ON o.id = od.order_id WHERE od.product_id = ? AND (o.delivery_status = 'da_nhan_hang' OR o.transaction_info = 'dathanhtoan')",
                [$masp]
            );
            $soldCount = (int)($sold[0]['qty'] ?? 0);

            $prod['gia_goc'] = $giaGoc;
            $prod['phantram'] = $phantram;
            $prod['gia_khuyenmai'] = $giaKM;
            $prod['avg_rating'] = $avgRating;
            $prod['rating_count'] = $ratingCount;
            $prod['sold_count'] = $soldCount;
            return $prod;
        };

        if ($id1) {
            $base1 = $productModel->find('tblsanpham', $id1);
            if ($base1) $p1 = $enrich($base1);
        }
        if ($id2) {
            $base2 = $productModel->find('tblsanpham', $id2);
            if ($base2) $p2 = $enrich($base2);
        }

        $this->view('homePage', [
            'page' => 'CompareView',
            'products' => $allProducts,
            'p1' => $p1,
            'p2' => $p2,
            'selected1' => $id1,
            'selected2' => $id2
        ]);
    }


   public function addtocard($masp)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Bắt buộc đăng nhập trước khi thêm giỏ
    if (!isset($_SESSION['user'])) {
        header('Location: ' . APP_URL . '/AuthController/ShowLogin');
        exit();
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $obj = $this->model("AdProducModel");
    $objKM = $this->model("AdKhuyenMai");
    $objCart = $this->model("CartModel");

    $email = $_SESSION['user']['email'] ?? null;

    // Lấy dữ liệu sản phẩm
    $data = $obj->find("tblsanpham", $masp);

    // Tìm khuyến mãi còn hạn (nếu có)
    $km = $objKM->findWithDiscount($masp);

    // Ưu tiên phantram trong bảng khuyenmai nếu có, fallback sang khuyenmai của sản phẩm
    $phantram = 0;
    if (!empty($km["phantram"])) {
        $phantram = (float)$km["phantram"];
    } elseif (!empty($data["khuyenmai"])) {
        $phantram = (float)$data["khuyenmai"];
    }

    $giaSauKM = $data['giaXuat'] * (1 - $phantram / 100);

    // ✅ 1️⃣ Cập nhật SESSION giỏ hàng
    if (isset($_SESSION['cart'][$masp])) {
        $_SESSION['cart'][$masp]['qty']++;
    } else {
        $_SESSION['cart'][$masp] = [
            'qty' => 1,
            'masp' => $data['masp'],
            'tensp' => $data['tensp'],
            'hinhanh' => $data['hinhanh'],
            'giaxuat' => $data['giaXuat'],
            'phantram' => $phantram,
        ];
    }

    // ✅ 2️⃣ Nếu user đã đăng nhập thì đồng bộ vào DB
    if ($email) {
        $cartDB = $objCart->getCartItem($email, $masp); // Lấy sản phẩm trong DB (nếu có)
        if ($cartDB) {
            // Đã có trong DB → +1 số lượng
            $newQty = ($cartDB['qty'] ?? $cartDB['soluong'] ?? 0) + 1;
        } else {
            // Chưa có → thêm mới
            $newQty = 1;
        }

        // Cập nhật DB (hàm addOrUpdateCart nên xử lý insert/update)
        $objCart->addOrUpdateCart($email, $masp, $newQty, $giaSauKM);
    }

    // ✅ Thay vì redirect, render lại trang order trực tiếp từ session
    // Điều này đảm bảo session được lấy ngay lập tức mà không cần reload từ DB
    $this->order();
    exit();
}



    public function delete($masp)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $objCart = $this->model("CartModel");
        $email = $_SESSION['user']['email'] ?? null;

        if (!$email) {
            header("Location: " . APP_URL . "/AuthController/dangnhap");
            exit();
        }

        // ✅ Xóa trong session
        if (isset($_SESSION['cart'][$masp])) {
            unset($_SESSION['cart'][$masp]);
        }

        // ✅ Xóa trong database
        $objCart->deleteCartItem($email, $masp);

        // ✅ Load lại giỏ hàng mới nhất (từ DB)
        $updatedCart = $objCart->getCartByEmail($email);

        // ✅ Gửi sang view
        $this->view("homePage", [
            "page" => "OrderView",
            "listProductOrder" => $updatedCart
        ]);
    }

  public function update()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $objCart = $this->model("CartModel");
    $objKM = $this->model("AdKhuyenMai");

    $email = $_SESSION['user']['email'] ?? null;
    if (!$email) {
        header("Location: " . APP_URL . "/Home/login");
        exit();
    }

    $quantities = $_POST['qty'] ?? [];
    $noteCart = $_POST['note_cart'] ?? [];

    // ✅ Cập nhật số lượng
    foreach ($quantities as $masp => $qty) {
        $qty = max(1, (int)$qty);
        $objCart->updateCartQty($email, $masp, $qty);
    }

    // ✅ Cập nhật trạng thái chọn (note_cart) - chỉ những sản phẩm được checked
    // Lấy tất cả sản phẩm trong giỏ và set note_cart = 0 trước
    $allCartItems = $objCart->getCartByEmail($email);
    foreach ($allCartItems as $masp => $item) {
        if (isset($noteCart[$masp])) {
            // Checkbox được checked
            $objCart->updateNoteCart($email, $masp, 1);
        } else {
            // Checkbox không được checked
            $objCart->updateNoteCart($email, $masp, 0);
        }
    }

    // ✅ Lấy lại giỏ hàng mới nhất từ DB
    $cart = $objCart->getCartByEmail($email);

    // ✅ Giống như hàm order(): tính khuyến mãi & tổng tiền
    $tongtien = 0;
    foreach ($cart as $masp => &$item) {
        $km = $objKM->findWithDiscount($masp);
        $phantram = !empty($km['phantram'])
            ? (float)$km['phantram']
            : ($item['phantram'] ?? 0);

        $giaSauKM = $item['giaxuat'] * (1 - $phantram / 100);
        $item['phantram'] = $phantram;
        $item['giaSauKM'] = $giaSauKM;

        // ✅ CHỈ tính tổng tiền nếu sản phẩm được chọn
        if (!empty($item['note_cart']) && $item['note_cart'] == 1) {
            $tongtien += $giaSauKM * $item['qty'];
        }
    }

    // ✅ Lưu tổng tiền vào session để dùng cho VNPAY
    $_SESSION['cart_total'] = $tongtien;

    // ✅ Cập nhật session giỏ hàng
    $_SESSION['cart'] = $cart;

    // ✅ Render lại trang order
    $this->view("homePage", [
        "page" => "OrderView",
        "listProductOrder" => $cart,
        "amount" => $tongtien,
        "success" => "Giỏ hàng đã được cập nhật thành công!"
    ]);
}




  public function order()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $objCart = $this->model("CartModel");
    $objKM = $this->model("AdKhuyenMai");
    $objVoucher = $this->model("Advoucher");

    $email = $_SESSION['user']['email'] ?? null;

    if (!$email) {
        header("Location: " . APP_URL . "/Home/login");
        exit();
    }

    // ✅ Lấy giỏ hàng từ DB trực tiếp
    $cart = $objCart->getCartByEmail($email);

    // ✅ Tính khuyến mãi & tổng tiền (giống hàm update())
    $tongtien = 0;
    foreach ($cart as $masp => &$item) {
        // Đảm bảo có trường note_cart
        if (!isset($item['note_cart'])) {
            $item['note_cart'] = 0;
        }

        $km = $objKM->findWithDiscount($masp);
        $phantram = !empty($km['phantram'])
            ? (float)$km['phantram']
            : ($item['phantram'] ?? 0);

        $giaSauKM = $item['giaxuat'] * (1 - $phantram / 100);
        $item['phantram'] = $phantram;
        $item['giaSauKM'] = $giaSauKM;

        // ✅ CHỈ tính tổng tiền nếu sản phẩm được chọn
        if (!empty($item['note_cart']) && $item['note_cart'] == 1) {
            $tongtien += $giaSauKM * $item['qty'];
        }
    }

    // ✅ Lưu tổng tiền vào session để dùng cho VNPAY
    $_SESSION['cart_total'] = $tongtien;

    // ✅ Lấy danh sách voucher áp dụng được
    $availableVouchers = $objVoucher->getApplicableVouchers($cart);

    // ✅ Cập nhật session
    $_SESSION['cart'] = $cart;

    // ✅ Gửi sang view
    $this->view("homePage", [
        "page" => "OrderView",
        "listProductOrder" => $cart,
        "amount" => $tongtien,
        "availableVouchers" => $availableVouchers
    ]);
}


public function orderDetail($id)
{
    $orderModel = $this->model('OrderModel');
    $order = $orderModel->getOrderById($id);
    $orderItems = $orderModel->getOrderItemsWithProduct($id);

    $this->view('homePage',[
        'page' => 'orderDetailView', 
        'order' => $order,
        'orderItems' => $orderItems
    ]);
}

    // Shipper API: update delivery status (requires shipper role)
    public function shipperUpdateOrder()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';
        // only shipper or admin
        if (!Auth::hasRole('shipper') && !Auth::hasRole('admin')) {
            http_response_code(403);
            echo 'Forbidden';
            exit();
        }

        $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';
        $returnReason = isset($_POST['return_reason']) ? trim($_POST['return_reason']) : null;
        $markPaid = ($status === 'da_nhan_hang');

        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadsDir = __DIR__ . '/../public/uploads/delivery';
            if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
            $tmp = $_FILES['photo']['tmp_name'];
            $name = time() . '_' . basename($_FILES['photo']['name']);
            $dest = $uploadsDir . '/' . $name;
            if (move_uploaded_file($tmp, $dest)) {
                // store relative path from public
                $photoPath = 'uploads/delivery/' . $name;
            }
        }

        $orderModel = $this->model('OrderModel');
        
        // ✅ Xử lý trả hàng: lưu lý do và cộng lại số lượng sản phẩm
        if ($status === 'da_tra_hang') {
            $reason = $returnReason ?? 'Không có lý do';
            $orderModel->markAsReturned($orderId, $reason);
            $orderModel->restoreProductQuantity($orderId);
        } else {
            $ok = $orderModel->setDeliveryStatusById($orderId, $status, $photoPath, $markPaid);

            // if markPaid set is_paid, also update transaction_info to 'dathanhtoan'
            if ($ok && $markPaid) {
                $order = $orderModel->getOrderById($orderId);
                if ($order && (!isset($order['transaction_info']) || $order['transaction_info'] !== 'dathanhtoan')) {
                    $orderModel->updateOrderStatus($order['order_code'], 'dathanhtoan');
                }
            }
        }

        // Sau khi cập nhật, điều hướng phù hợp từng vai trò
        if (Auth::hasRole('admin')) {
            header('Location: ' . APP_URL . '/Admin/orders');
        } elseif (Auth::hasRole('shipper')) {
            header('Location: ' . APP_URL . '/Shipper');
        } else {
            header('Location: ' . APP_URL . '/Home/orderDetail/' . intval($orderId));
        }
        exit();
    }

    // User cancel order (allowed only for certain statuses)
    public function userCancelOrder()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $cancelReason = isset($_POST['cancel_reason']) ? trim($_POST['cancel_reason']) : 'Không có lý do';

        if ($orderId <= 0) {
            header('Location: ' . APP_URL . '/Home/orderHistory');
            exit();
        }

        $orderModel = $this->model('OrderModel');
        
        // Verify order belongs to user
        $order = $orderModel->getOrderById($orderId);
        if (!$order || $order['user_email'] !== $_SESSION['user']['email']) {
            header('Location: ' . APP_URL . '/Home/orderHistory');
            exit();
        }

        // Check if order can be cancelled
        if ($orderModel->canCancelOrder($orderId)) {
            $orderModel->cancelOrder($orderId, $cancelReason);
            // Restore product quantity back to inventory
            $orderModel->restoreProductQuantity($orderId);
        }

        header('Location: ' . APP_URL . '/Home/orderDetail/' . $orderId);
        exit();
    }





    // Xử lý đặt hàng: chỉ cho phép khi đã đăng nhập
    // public function checkout() {
    //     $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    //     // If user not logged in
    //     if (!isset($_SESSION['user'])) {
    //         if (!empty($cart)) {
    //             // Has cart but not logged in -> go to login page
    //             header('Location: ' . APP_URL . '/AuthController/ShowLogin');
    //             exit();
    //         }
    //         // No cart and not logged in -> go to homepage
    //         header('Location: ' . APP_URL . '/Home');
    //         exit();
    //     }

    //     // User is logged in
    //     if (empty($cart)) {
    //         // Logged in but cart empty -> go to homepage or show order page with empty list
    //         $this->view("homePage", [
    //             "page" => "OrderView",
    //             "listProductOrder" => [],
    //             "success" => "Giỏ hàng trống!"
    //         ]);
    //         return;
    //     }
    //     header('Location: ' . APP_URL . '/Home');
    //     exit();
    // }
    public function checkout()
    {
        // Lấy thông tin giỏ hàng và đăng nhập
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $isLoggedIn = isset($_SESSION['user']);

        // 1. Đã đăng nhập và có sản phẩm trong giỏ hàng
        if ($isLoggedIn && !empty($cart)) {
            $this->view("homePage", [
                "page" => "CheckoutInfoView"
            ]);
            return;
        }

        // 2. Chưa đăng nhập nhưng có sản phẩm trong giỏ hàng
        if (!$isLoggedIn && !empty($cart)) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        // 3. Đã đăng nhập nhưng giỏ hàng trống
        if ($isLoggedIn && empty($cart)) {
            $this->view("homePage", [
                "page" => "OrderView",
                "listProductOrder" => [],
                "success" => "Giỏ hàng trống!"
            ]);
            return;
        }

        // 4. Chưa đăng nhập và giỏ hàng trống
        if (!$isLoggedIn && empty($cart)) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }

        // Trường hợp không xác định
        header('Location: ' . APP_URL . '/Home');
        exit();
    }

    // Xử lý gửi đánh giá/bình luận (từ DetailView) - hỗ trợ upload ảnh
    public function submitComment()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $masp = $_POST['masp'] ?? '';
        $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
        $content = trim($_POST['content'] ?? '');

        if (empty($masp) || (empty($rating) && $rating !== 0) || $content === '') {
            header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp) . '?error=missing');
            exit();
        }

        $commentModel = $this->model('CommentModel');
        $user = $_SESSION['user'];
        // nếu chưa mua thì redirect
        if (!$commentModel->userHasBoughtProduct($user['email'], $masp)) {
            header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp) . '?error=not_purchased');
            exit();
        }

        // nếu đã đánh giá rồi thì redirect
        if ($commentModel->userHasReviewedProduct($user['email'], $masp)) {
            header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp) . '?error=already_reviewed');
            exit();
        }

        // Upload ảnh nếu có
        $image = null;
        if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] === UPLOAD_ERR_OK) {
            $uploadsDir = __DIR__ . '/../public/uploads/comments';
            if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
            $tmp = $_FILES['comment_image']['tmp_name'];
            $name = time() . '_' . basename($_FILES['comment_image']['name']);
            $dest = $uploadsDir . '/' . $name;
            if (move_uploaded_file($tmp, $dest)) {
                $image = 'uploads/comments/' . $name;
            }
        }

        // Kiểm tra từ cấm - nếu có từ cấm sẽ không cho phép bình luận
        require_once __DIR__ . '/../models/BannedWordModel.php';
        $bannedModel = new BannedWordModel();
        $flagged = $bannedModel->containsBannedWord($content);
        
        if ($flagged) {
            header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp) . '?error=banned_words');
            exit();
        }

        $commentModel = $this->model('CommentModel');
        $commentModel->addComment($masp, $user['email'], $user['fullname'] ?? $user['email'], $rating, $content, null, null, $image, true);

        header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp) . '?success=reviewed');
        exit();
    }

    // Xóa comment (chỉ tác giả mới có thể xóa)
    public function deleteComment($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $commentModel = $this->model('CommentModel');
        $comment = $commentModel->getCommentById($id);

        if (!$comment) {
            http_response_code(404);
            echo 'Comment not found';
            exit();
        }

        if ($comment['user_email'] !== $_SESSION['user']['email']) {
            http_response_code(403);
            echo 'Unauthorized';
            exit();
        }

        // Lấy masp trước khi xóa
        $masp = $comment['masp'];
        $commentModel->deleteComment($id);

        header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp) . '?success=deleted');
        exit();
    }

    // Cập nhật comment (chỉ tác giả mới có thể sửa)
    public function updateComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo 'Invalid request';
            exit();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $id = (int)($_POST['comment_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        if (!$id || !$content) {
            http_response_code(400);
            echo 'Missing fields';
            exit();
        }

        $commentModel = $this->model('CommentModel');
        $comment = $commentModel->getCommentById($id);

        if (!$comment) {
            http_response_code(404);
            echo 'Comment not found';
            exit();
        }

        if ($comment['user_email'] !== $_SESSION['user']['email']) {
            http_response_code(403);
            echo 'Unauthorized';
            exit();
        }

        // Upload ảnh mới nếu có
        $image = $comment['image']; // giữ ảnh cũ
        if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] === UPLOAD_ERR_OK) {
            $uploadsDir = __DIR__ . '/../public/uploads/comments';
            if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
            $tmp = $_FILES['comment_image']['tmp_name'];
            $name = time() . '_' . basename($_FILES['comment_image']['name']);
            $dest = $uploadsDir . '/' . $name;
            if (move_uploaded_file($tmp, $dest)) {
                $image = 'uploads/comments/' . $name;
            }
        }

        $commentModel->updateComment($id, $content, $image);

        header('Location: ' . APP_URL . '/Home/detail/' . urlencode($comment['masp']) . '?success=updated');
        exit();
    }

    // Reply to comment
    public function replyToComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo 'Invalid request';
            exit();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $parent_id = (int)($_POST['parent_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        if (!$parent_id || !$content) {
            http_response_code(400);
            echo 'Missing fields';
            exit();
        }

        $commentModel = $this->model('CommentModel');
        $parent = $commentModel->getCommentById($parent_id);

        if (!$parent) {
            http_response_code(404);
            echo 'Parent comment not found';
            exit();
        }

        // Chỉ tác giả comment chính mới có thể reply
        if (!$commentModel->canReplyToComment($parent_id, $_SESSION['user']['email'])) {
            http_response_code(403);
            echo 'Only the comment author can reply';
            exit();
        }

        // Upload ảnh nếu có
        $image = null;
        if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] === UPLOAD_ERR_OK) {
            $uploadsDir = __DIR__ . '/../public/uploads/comments';
            if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
            $tmp = $_FILES['comment_image']['tmp_name'];
            $name = time() . '_' . basename($_FILES['comment_image']['name']);
            $dest = $uploadsDir . '/' . $name;
            if (move_uploaded_file($tmp, $dest)) {
                $image = 'uploads/comments/' . $name;
            }
        }

        $user = $_SESSION['user'];
        // Kiểm tra từ cấm
        require_once __DIR__ . '/../models/BannedWordModel.php';
        $bannedModel = new BannedWordModel();
        $flagged = $bannedModel->containsBannedWord($content);
        
        if ($flagged) {
            if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
            $_SESSION['comment_violation'] = 'Bình luận thiếu tiêu chuẩn!';
        }

        $commentModel = $this->model('CommentModel');
        $commentModel->replyToComment($parent_id, $user['email'], $user['fullname'] ?? $user['email'], $content, $image, !$flagged);

        $redirect = APP_URL . '/Home/detail/' . urlencode($parent['masp']);
        $redirect .= $flagged ? '?violation=1' : '?success=replied';
        header('Location: ' . $redirect);
        exit();
    }

    // AJAX endpoint: geocode address via cmap (server-side)
    public function geocodeAddress()
    {
        // Accept both GET and POST
        $address = $_GET['address'] ?? $_POST['address'] ?? null;
        header('Content-Type: application/json');
        
        if (!$address) {
            echo json_encode(['success' => false, 'message' => 'No address provided']);
            exit();
        }

        try {
            // Parse the address to extract detailed components
            // Expected format: "Số nhà, đường phố, phường/xã, quận/huyện, thành phố"
            $parts = array_map('trim', explode(',', $address));
            
            // Clean up and format address parts
            $fullAddress = $address;
            
            // If address has multiple parts, ensure it's properly formatted
            if (count($parts) >= 2) {
                $fullAddress = implode(', ', $parts);
            }
            
            // Fallback: Return success without coordinates (will use address string in map embed)
            // No need for cmap Client - just return the address
            echo json_encode([
                'success' => true,
                'lat' => null,
                'lng' => null,
                'address' => $fullAddress,
                'parts_count' => count($parts)
            ]);
            exit();
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
            exit();
        }
    }




    public function checkoutSave()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/Show');
            exit();
        }

        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (empty($cart)) {
            $this->view("homePage", [
                "page" => "OrderView",
                "listProductOrder" => [],
                "success" => "Giỏ hàng trống!"
            ]);
            return;
        }

        $receiver = isset($_POST['receiver']) ? trim($_POST['receiver']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        $distributorVoucherId = isset($_POST['distributor_voucher']) ? trim($_POST['distributor_voucher']) : '';
        $adminGocVoucherId = isset($_POST['admin_goc_voucher']) ? trim($_POST['admin_goc_voucher']) : '';
        $adminShipVoucherId = isset($_POST['admin_ship_voucher']) ? trim($_POST['admin_ship_voucher']) : '';
        $distanceFee = isset($_POST['distance_fee']) ? (float)$_POST['distance_fee'] : 0;
        $shippingMethodId = isset($_POST['shipping_method']) ? (int)$_POST['shipping_method'] : 1;

        if ($receiver === '' || $phone === '' || $address === '') {
            echo '<div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin giao hàng!</div>';
            $this->view("homePage", ["page" => "CheckoutInfoView"]);
            return;
        }

        // Kiểm tra chọn phương thức vận chuyển
        if (!$shippingMethodId || $shippingMethodId < 1 || $shippingMethodId > 3) {
            echo '<div class="alert alert-danger">Vui lòng chọn phương thức vận chuyển!</div>';
            $this->view("homePage", ["page" => "CheckoutInfoView"]);
            return;
        }

        $orderModel = $this->model("OrderModel");
        $orderDetailModel = $this->model("OrderDetailModel");
        $voucherModel = $this->model("Advoucher");
        $objCart = $this->model("CartModel");
        $objKM = $this->model("AdKhuyenMai");
        
        $user = $_SESSION['user'];
        $orderCode = 'HD' . time();
        $payment_method = $_POST['payment_method'] ?? 'cod';
        // Set transaction_info based on payment method
        // 'chothanhtoan' = pay on delivery (COD)
        // 'thanhtoanvnpay' = awaiting online payment (VNPAY)
        $transaction_info = ($payment_method === 'vnpay') ? 'thanhtoanvnpay' : 'chothanhtoan';
        $created_at = date('Y-m-d H:i:s');

        // ✅ LẤY tổng tiền từ frontend (đã tính sẵn bao gồm ship, voucher)
        $totalAmount = isset($_POST['total_amount_calculated']) ? (float)$_POST['total_amount_calculated'] : 0;
        
        // ✅ Luôn lấy cart từ DB để lưu vào order_details
        $cart = $objCart->getCartByEmail($user['email']);
        
        // Tính lại giá khuyến mãi cho từng sản phẩm
        foreach ($cart as $masp => &$item) {
            $km = $objKM->findWithDiscount($masp);
            $phantram = !empty($km['phantram'])
                ? (float)$km['phantram']
                : ($item['phantram'] ?? 0);

            $giaSauKM = $item['giaxuat'] * (1 - $phantram / 100);
            $item['phantram'] = $phantram;
            $item['giaSauKM'] = $giaSauKM;
        }
        unset($item);
        
        // ✅ Nếu không có giá trị từ frontend, tính lại để đảm bảo an toàn
        if ($totalAmount <= 0) {
            // Tính tổng tiền hàng
            $totalProductPrice = 0;
            foreach ($cart as $item) {
                $giaSauKM = isset($item['giaSauKM']) ? $item['giaSauKM'] : ($item['giaxuat'] * (1 - ($item['phantram'] ?? 0) / 100));
                $thanhtien = $giaSauKM * $item['qty'];
                $totalProductPrice += $thanhtien;
            }
            
            // Apply vouchers
            $distributorVoucherDiscount = 0;
            $adminGocVoucherDiscount = 0;
            $adminShipVoucherDiscount = 0;
            
            if (!empty($distributorVoucherId)) {
                $distributorVoucherModel = $this->model("VoucherDistributorModel");
                $voucherResult = $distributorVoucherModel->validateVoucher($distributorVoucherId, $totalProductPrice);
                if ($voucherResult['valid']) {
                    $distributorVoucherDiscount = $voucherResult['voucher']['discount_value'];
                    $distributorVoucherModel->useVoucher($voucherResult['voucher']['id']);
                }
            }
            
            if (!empty($adminGocVoucherId)) {
                $gocVouchers = $voucherModel->getVouchersByType('goc');
                foreach ($gocVouchers as $v) {
                    if ($v['vc_id'] === $adminGocVoucherId) {
                        $adminGocVoucherDiscount = min($v['giagiam'], $totalProductPrice);
                        $voucherModel->useVoucher($adminGocVoucherId);
                        break;
                    }
                }
            }
            
            if (!empty($adminShipVoucherId)) {
                $shipVouchers = $voucherModel->getVouchersByType('ship');
                foreach ($shipVouchers as $v) {
                    if ($v['vc_id'] === $adminShipVoucherId) {
                        $adminShipVoucherDiscount = min($v['giagiam'], $distanceFee);
                        $voucherModel->useVoucher($adminShipVoucherId);
                        break;
                    }
                }
            }
            
            $totalVoucherDiscount = $distributorVoucherDiscount + $adminGocVoucherDiscount;
            $totalAmount = $totalProductPrice - $totalVoucherDiscount;
            $finalShippingFee = max(0, $distanceFee - $adminShipVoucherDiscount);
            $totalAmount += $finalShippingFee;
        } else {
            // ✅ Có tổng tiền từ frontend, chỉ cần tính shipping fee để lưu vào DB
            $adminShipVoucherDiscount = 0;
            if (!empty($adminShipVoucherId)) {
                $voucherModel->useVoucher($adminShipVoucherId);
                $shipVouchers = $voucherModel->getVouchersByType('ship');
                foreach ($shipVouchers as $v) {
                    if ($v['vc_id'] === $adminShipVoucherId) {
                        $adminShipVoucherDiscount = min($v['giagiam'], $distanceFee);
                        break;
                    }
                }
            }
            $finalShippingFee = max(0, $distanceFee - $adminShipVoucherDiscount);
            
            // ✅ Sử dụng các voucher khác nếu có (không ảnh hưởng đến totalAmount từ frontend)
            if (!empty($distributorVoucherId)) {
                $distributorVoucherModel = $this->model("VoucherDistributorModel");
                $distributorVoucherModel->useVoucherByCode($distributorVoucherId);
            }
            if (!empty($adminGocVoucherId)) {
                $voucherModel->useVoucher($adminGocVoucherId);
            }
        }

        // Lưu đơn hàng
        $orderId = $orderModel->createOrderWithShipping(
            $orderCode,
            $totalAmount,
            $user['email'],
            $receiver,
            $phone,
            $address,
            $created_at,
            $transaction_info,
            $shippingMethodId,
            $finalShippingFee
        );

        // Lưu chi tiết từng sản phẩm
        foreach ($cart as $item) {
            $giaSauKM = isset($item['giaSauKM']) ? $item['giaSauKM'] : ($item['giaxuat'] * (1 - ($item['phantram'] ?? 0) / 100));
            $thanhtien = $giaSauKM * $item['qty'];

            $orderDetailModel->addOrderDetail(
                $orderId,
                $item['masp'],
                $item['qty'],
                $item['giaxuat'],
                $giaSauKM,
                $thanhtien,
                $item['hinhanh'],
                $item['tensp']
            );
        }

        // ✅ Giảm số lượng sản phẩm sau khi tạo đơn hàng
        $orderModel->decreaseProductQuantity($orderId);

        // ✅ Xóa giỏ hàng từ session và DB
        $_SESSION['orderCode'] = $orderCode;
        $_SESSION['totalAmount'] = $totalAmount;
        $_SESSION['cart_total'] = $totalAmount;
        $_SESSION['cart'] = [];
        
        // Xóa từ DB
        $objCart->deleteAllCartByEmail($user['email']);
        
        if ($payment_method == 'vnpay') {
            // Redirect to VNPAY payment page
            header('Location: ' . APP_URL . '/vnpay_php/vnpay_pay.php');
            exit();
        } elseif ($payment_method == 'cod') {
            // Gửi tin nhắn tự động cho buyer từ seller
            require_once __DIR__ . '/../app/MessageHelper.php';
            \MessageHelper::sendAutoOrderMessage($orderId);
            // Redirect to invoice page
            header('Location: ' . APP_URL . '/Home/invoice/' . $orderId);
            exit();
        }
    }


    // Xem tin nhắn của buyer
    public function messages()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        $email = $_SESSION['user']['email'];
        $orderModel = $this->model('OrderModel');
        $orders = $orderModel->getOrdersByEmail($email);
        $this->view('homePage', [
            'page' => 'BuyerMessagesView',
            'orders' => $orders
        ]);
    }

    // Xử lý khi VNPAY redirect về
    public function vnpayReturn()
    {
        // Lấy tất cả params VNPAY trả về
        $data = $_GET;
        //$vnp_HashSecret = defined('VNP_HASH_SECRET') ? VNP_HASH_SECRET : '';
        $vnp_HashSecret = "LSP2R1CSHB88VUCXTLE9A36CEWIL92IW";
        if (isset($data['vnp_SecureHash'])) {
            $secureHash = $data['vnp_SecureHash'];
            unset($data['vnp_SecureHash']);
            unset($data['vnp_SecureHashType']);
            ksort($data);
            $hashData = '';
            foreach ($data as $key => $value) {
                if (($key !== 'vnp_SecureHash') && ($key !== 'vnp_SecureHashType')) {
                    $hashData .= $key . '=' . $value . '&';
                }
            }
            $hashData = rtrim($hashData, '&');
            $calculatedHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

            if ($calculatedHash === $secureHash) {
                // signature ok -> kiểm tra mã trả về
                $vnp_ResponseCode = isset($_GET['vnp_ResponseCode']) ? $_GET['vnp_ResponseCode'] : '';
                $vnp_TxnRef = isset($_GET['vnp_TxnRef']) ? $_GET['vnp_TxnRef'] : '';

                if ($vnp_ResponseCode === '00') {
                    // Thanh toán thành công
                    $message = "Thanh toán VNPAY thành công. Mã đơn: $vnp_TxnRef";
                } else {
                    $message = "Thanh toán VNPAY không thành công. Mã trả về: " . htmlspecialchars($vnp_ResponseCode);
                }
            } else {
                $message = 'Chu ky khong hop le.';
            }
        } else {
            $message = 'Tham so chua duoc truyen.';
        }

        $this->view('homePage', [
            'page' => 'OrderView',
            'listProductOrder' => [],
            'success' => $message
        ]);
    }

    // Hiển thị form nhập thông tin giao hàng sau khi đăng ký hoặc đăng nhập
    public function checkoutInfo()
    {
        if (!isset($_SESSION['user'])) {
            header('location: ' . APP_URL . '/AuthController/Showlogin');
            exit();
        }
        $this->view("homePage", ["page" => "CheckoutInfoView"]);
    }

    // AJAX endpoint: calculate distance from 15 Nguyễn Khang to checkout address
    public function calculateDistance()
    {
        // Accept both GET and POST
        $address = $_GET['address'] ?? $_POST['address'] ?? null;
        header('Content-Type: application/json');
        
        if (!$address) {
            echo json_encode(['success' => false, 'message' => 'No address provided']);
            exit();
        }

        // Store origin
        $origin = '15 Nguyễn Khang, Cầu Giấy, Hà Nội';

        try {
            // Try Google Distance Matrix for accurate distance store -> customer
            $GOOGLE_MAPS_API_KEY = defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : null;
            if ($GOOGLE_MAPS_API_KEY) {
                $apiUrl = 'https://maps.googleapis.com/maps/api/distancematrix/json?' .
                    'origins=' . urlencode($origin) .
                    '&destinations=' . urlencode($address) .
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
                    if (($data['status'] ?? '') === 'OK' && isset($data['rows'][0]['elements'][0])) {
                        $el = $data['rows'][0]['elements'][0];
                        if (($el['status'] ?? '') === 'OK' && isset($el['distance']['value'])) {
                            $distance_km = round($el['distance']['value'] / 1000, 2);
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

            // Fallback: Estimate distance based on address keywords (Hanoi districts + nearby provinces)
            $districtCoords = [
                // Store location reference (15 Nguyễn Khang, Cầu Giấy, Hà Nội)
                'nguyễn khang' => 0.0,  // Exact store location
                'cầu giấy' => 0.5,
                
                // Central Hanoi (0-5km)
                'hoàn kiếm' => 1.8,
                'hai bà trưng' => 2.2,
                'đống đa' => 2.0,
                'ba đình' => 2.5,
                
                // Inner Hanoi (3-10km)
                'tây hồ' => 3.2,
                'thanh xuân' => 3.5,
                'long biên' => 4.0,
                'bắc từ liêm' => 4.2,
                'nam từ liêm' => 5.5,
                
                // Outer Hanoi (8-20km)
                'hà đông' => 8.0,
                'thanh trì' => 10.0,
                'gia lâm' => 12.0,
                'hoài đức' => 15.0,
                'thạch thất' => 18.0,
                'quốc oai' => 22.0,
                'chương mỹ' => 25.0,
                'sơn tây' => 35.0,
                'ba vì' => 50.0,
                'mỹ đức' => 45.0,
                'phúc thọ' => 40.0,
                
                // Nearby provinces (30-200km)
                'hưng yên' => 50.0,
                'hà nam' => 60.0,
                'hải phòng' => 120.0,
                'hải dương' => 90.0,
                'bắc ninh' => 50.0,
                'bắc giang' => 80.0,
                'vĩnh phúc' => 80.0,
                'thái nguyên' => 150.0,
                'hà giang' => 300.0,
                'cao bằng' => 250.0,
                'lạng sơn' => 200.0,
                'tuyên quang' => 180.0,
                'yên bái' => 200.0,
                'lào cai' => 280.0,
                'điện biên' => 450.0,
                'sơn la' => 350.0,
                'hòa bình' => 120.0,
                'ninh bình' => 100.0,
                'thanh hóa' => 150.0,
                'nghệ an' => 300.0,
                'hà tĩnh' => 350.0,
                'quảng bình' => 400.0,
                'quảng trị' => 500.0,
                'thừa thiên huế' => 600.0,
                'đà nẵng' => 750.0,
                'quảng nam' => 800.0,
                'quảng ngãi' => 850.0,
                'bình định' => 1000.0,
            ];
            
            // Try to match address with known districts/provinces
            $addressLower = mb_strtolower($address, 'UTF-8');
            $distance_km = 100.0;  // Default distance for unknown areas (100km)
            
            // First check if it contains "Nguyễn Khang" (store location)
            if (strpos($addressLower, 'nguyễn khang') !== false) {
                $distance_km = 0.0;  // Same location as store
            } else {
                foreach ($districtCoords as $district => $dist) {
                    if (strpos($addressLower, $district) !== false) {
                        $distance_km = $dist;
                        break;
                    }
                }
            }
            
            // Cap at 1000km maximum
            if ($distance_km > 1000) {
                $distance_km = 1000;
            }
            
            echo json_encode([
                'success' => true,
                'distance_km' => round($distance_km, 2),
                'distance_text' => round($distance_km, 2) . ' km (estimate)',
                'duration' => 'N/A'
            ]);
            exit();
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        }
    }

    // Haversine formula to calculate distance between two coordinates
    private function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; // Earth's radius in kilometers
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }

    // AJAX endpoint: get cart total for display
    public function getCartTotal()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['email'])) {
            echo json_encode(['success' => false, 'total_products' => 0]);
            exit();
        }

        $objCart = $this->model("CartModel");
        $objKM = $this->model("AdKhuyenMai");
        $email = $_SESSION['user']['email'];
        
        // Load cart from database to ensure latest prices
        $cart = $objCart->getCartByEmail($email);
        
        // Calculate total with discount for ALL products (tính tất cả sản phẩm)
        $totalAmount = 0;
        foreach ($cart as $masp => $item) {
            $km = $objKM->findWithDiscount($masp);
            $phantram = !empty($km['phantram'])
                ? (float)$km['phantram']
                : ($item['phantram'] ?? 0);

            $giaSauKM = $item['giaxuat'] * (1 - $phantram / 100);
            $thanhtien = $giaSauKM * $item['qty'];
            $totalAmount += $thanhtien;
        }

        echo json_encode([
            'success' => true,
            'total_products' => $totalAmount
        ]);
        exit();
    }

    /**
     * Get cart items with product details
     */
    public function getCartItems()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['cart'])) {
            echo json_encode(['success' => false, 'items' => []]);
            exit();
        }

        $items = [];
        foreach ($_SESSION['cart'] as $item) {
            $items[] = [
                'product_id' => $item['id'] ?? null,
                'product_name' => $item['tensanpham'] ?? '',
                'product_seller' => $item['product_seller'] ?? null,
                'quantity' => $item['qty'] ?? 0,
                'price' => $item['giaxuat'] ?? 0
            ];
        }

        echo json_encode([
            'success' => true,
            'items' => $items
        ]);
        exit();
    }

    // AJAX endpoint: get available vouchers for checkout
    public function getAvailableVouchers()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            echo json_encode([
                'success' => false,
                'goc_vouchers' => [],
                'ship_vouchers' => []
            ]);
            exit();
        }

        $voucherModel = $this->model("Advoucher");
        $cart = $_SESSION['cart'];
        
        // Get applicable vouchers based on cart - split by type
        $gocVouchers = $voucherModel->getGocVouchers();
        $shipVouchers = $voucherModel->getShipVouchers();
        
        // Format goc vouchers
        $formattedGocVouchers = [];
        foreach ($gocVouchers as $v) {
            // Kiểm tra giá tối thiểu có hợp lệ không
            $totalPrice = 0;
            foreach ($cart as $item) {
                $totalPrice += (float)$item['giaxuat'] * (int)($item['soluong'] ?? 1);
            }
            
            if ($totalPrice >= $v['giatoithieu']) {
                $formattedGocVouchers[] = [
                    'vc_id' => $v['vc_id'],
                    'giagiam' => $v['giagiam'],
                    'giatoithieu' => $v['giatoithieu'],
                    'chuc_nang' => 'goc',
                    'display' => $v['vc_id'] . ' - Giảm ' . number_format($v['giagiam'], 0, ',', '.') . 'đ'
                ];
            }
        }
        
        // Format ship vouchers
        $formattedShipVouchers = [];
        foreach ($shipVouchers as $v) {
            $formattedShipVouchers[] = [
                'vc_id' => $v['vc_id'],
                'giagiam' => $v['giagiam'],
                'chuc_nang' => 'ship',
                'display' => $v['vc_id'] . ' - Giảm ' . number_format($v['giagiam'], 0, ',', '.') . 'đ ship'
            ];
        }

        echo json_encode([
            'success' => true,
            'goc_vouchers' => $formattedGocVouchers,
            'ship_vouchers' => $formattedShipVouchers
        ]);
        exit();
    }

    // AJAX endpoint: validate voucher and get discount amount
    public function validateVoucher()
    {
        header('Content-Type: application/json');
        
        $voucherId = isset($_GET['voucherId']) ? trim($_GET['voucherId']) : '';
        $voucherType = isset($_GET['type']) ? trim($_GET['type']) : null; // goc | ship | null
        
        if (empty($voucherId) || !isset($_SESSION['cart'])) {
            echo json_encode(['success' => false, 'discount' => 0]);
            exit();
        }

        $voucherModel = $this->model("Advoucher");
        $cart = $_SESSION['cart'];
        
        // Validate voucher and optionally filter by chức năng
        $voucher = $voucherModel->isVoucherValid($voucherId, $cart);
        if ($voucher && $voucherType) {
            // Nếu có truyền type, yêu cầu phải khớp
            if (($voucher['chuc_nang'] ?? 'goc') !== $voucherType) {
                $voucher = null;
            }
        }
        
        if ($voucher) {
            echo json_encode([
                'success' => true,
                'discount' => $voucher['giagiam'],
                'vc_id' => $voucher['vc_id'],
                'chuc_nang' => $voucher['chuc_nang'] ?? 'goc'
            ]);
        } else {
            echo json_encode(['success' => false, 'discount' => 0]);
        }
        exit();
    }

    // AJAX endpoint: return pending orders for shippers (paid & not yet delivered)
    public function getPendingOrders()
    {
        header('Content-Type: application/json');
        $orderModel = $this->model('OrderModel');
        $orders = $orderModel->getPendingOrders();
        echo json_encode(['success' => true, 'orders' => $orders]);
        exit();
    }

    // AJAX endpoint: Get shipping fee based on method and distance
    public function getShippingFee()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'fee' => null, 'message' => 'Not authenticated']);
            exit();
        }

        $methodId = isset($_GET['method_id']) ? (int)$_GET['method_id'] : 1;
        $distance = isset($_GET['distance']) ? (float)$_GET['distance'] : 0;

        if ($methodId < 1 || $methodId > 3 || $distance <= 0) {
            echo json_encode(['success' => false, 'fee' => null]);
            exit();
        }

        // Get distributor shipping rules model
        $distributorShippingModel = $this->model('DistributorShippingRulesModel');
        
        // Get all distributors from current cart - check if there's custom shipping rule
        $cart = $_SESSION['cart'] ?? [];
        
        // If cart is empty, use global rates
        if (empty($cart)) {
            $shippingMethodModel = $this->model('ShippingMethodModel');
            $method = $shippingMethodModel->getById($methodId);
            if ($method) {
                $fee = ceil($distance * $method['price_per_km']);
                echo json_encode(['success' => true, 'fee' => $fee, 'from' => 'global']);
            } else {
                echo json_encode(['success' => false, 'fee' => null]);
            }
            exit();
        }

        // Connect to database to get product distributor info
        $pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');
        
        // Check if all items in cart are from same distributor
        $distributorEmails = [];
        
        foreach ($cart as $item) {
            $masp = $item['masp'];
            // Get distributor email for this product (column name is 'email' not 'email_distributor')
            $stmt = $pdo->prepare("
                SELECT email FROM tblsanpham WHERE masp = ?
            ");
            $stmt->execute([$masp]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product && $product['email']) {
                $distributorEmails[] = $product['email'];
            }
        }

        // Debug log
        file_put_contents('debug_shipping_fee.log', date('Y-m-d H:i:s') . " - getShippingFee\n", FILE_APPEND);
        file_put_contents('debug_shipping_fee.log', "Distance: $distance km, Method: $methodId\n", FILE_APPEND);
        file_put_contents('debug_shipping_fee.log', "Cart items: " . count($cart) . "\n", FILE_APPEND);
        file_put_contents('debug_shipping_fee.log', "Distributor emails: " . json_encode($distributorEmails) . "\n", FILE_APPEND);

        // If all from same distributor, check custom rules
        if (!empty($distributorEmails) && count(array_unique($distributorEmails)) === 1) {
            $distributorEmail = $distributorEmails[0];
            
            file_put_contents('debug_shipping_fee.log', "Single distributor: $distributorEmail\n", FILE_APPEND);
            
            // Try to get custom fee
            $result = $distributorShippingModel->getShippingFee($distributorEmail, $distance, $methodId);
            
            file_put_contents('debug_shipping_fee.log', "Custom fee result: " . json_encode($result) . "\n", FILE_APPEND);
            
            if ($result !== null) {
                $fee = is_array($result) ? $result['fee'] : $result;
                $days = is_array($result) ? $result['days'] : 3;
                echo json_encode([
                    'success' => true, 
                    'fee' => $fee, 
                    'days' => $days,
                    'from' => 'distributor', 
                    'distributor' => $distributorEmail
                ]);
                exit();
            }
        } else {
            file_put_contents('debug_shipping_fee.log', "Multiple/no distributors, using global\n", FILE_APPEND);
        }

        // Fallback to global rates
        $shippingMethodModel = $this->model('ShippingMethodModel');
        $method = $shippingMethodModel->getById($methodId);
        if ($method) {
            $fee = ceil($distance * $method['price_per_km']);
            
            // Calculate estimated days based on global logic
            $estimatedDays = 3; // default
            if ($distance < 10) {
                $estimatedDays = 3;
            } elseif ($distance <= 100) {
                $estimatedDays = 5;
            } else {
                $estimatedDays = 7;
            }
            
            // Adjust based on method
            if ($methodId == 2) { // fast
                $estimatedDays = max(1, $estimatedDays - 1);
            } elseif ($methodId == 3) { // express
                $estimatedDays = max(1, $estimatedDays - 2);
            }
            
            echo json_encode([
                'success' => true, 
                'fee' => $fee,
                'days' => $estimatedDays,
                'from' => 'global'
            ]);
        } else {
            echo json_encode(['success' => false, 'fee' => null]);
        }
        exit();
    }

    /**
     * Lấy danh sách voucher khả dụng của distributor
     */
    public function getDistributorVouchers()
    {
        header('Content-Type: application/json');

        $distributorEmail = $_GET['distributor_email'] ?? null;

        if (!$distributorEmail) {
            echo json_encode(['success' => false, 'vouchers' => []]);
            exit();
        }

        $voucherModel = $this->model('VoucherDistributorModel');
        $vouchers = $voucherModel->getAvailableVouchers($distributorEmail);

        echo json_encode([
            'success' => true,
            'vouchers' => $vouchers ?? []
        ]);
        exit();
    }

    /**
     * Kiểm tra tính hợp lệ của voucher distributor
     */
    public function validateDistributorVoucher()
    {
        header('Content-Type: application/json');

        $code = $_POST['code'] ?? null;
        $cartTotal = (int)($_POST['cart_total'] ?? 0);

        if (!$code) {
            echo json_encode(['valid' => false, 'message' => 'Mã voucher không hợp lệ']);
            exit();
        }

        $voucherModel = $this->model('VoucherDistributorModel');
        $result = $voucherModel->validateVoucher($code, $cartTotal);

        echo json_encode($result);
        exit();
    }

    // Hiển thị hóa đơn
    public function invoice($orderId = null)
    {
        if (!$orderId) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $orderModel = $this->model('OrderModel');
        $orderDetailModel = $this->model('OrderDetailModel');
        
        $order = $orderModel->getOrderById($orderId);
        
        // Kiểm tra order tồn tại và thuộc về user hiện tại
        if (!$order || $order['user_email'] !== $_SESSION['user']['email']) {
            echo '<div class="alert alert-danger">Đơn hàng không tồn tại hoặc không có quyền xem!</div>';
            return;
        }

        $orderDetails = $orderDetailModel->getOrderDetailsByOrderId($orderId);

        $this->view("homePage", [
            "page" => "InvoiceView",
            "order" => $order,
            "orderDetails" => $orderDetails
        ]);
    }

    // Display distributor registration form
    public function distributorRegister()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';

        // If not logged in, redirect to login
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin?redirect=' . urlencode(APP_URL . '/Home/distributorRegister'));
            exit();
        }

        // If already distributor, redirect to distributor dashboard
        if (Auth::hasRole('distributor')) {
            header('Location: ' . APP_URL . '/Distributor');
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

    // Handle distributor registration submission
    public function submitDistributorRegistration()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';

        // Check if logged in
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        // Verify POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Yêu cầu không hợp lệ';
            header('Location: ' . APP_URL . '/Home/distributorRegister');
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
            header('Location: ' . APP_URL . '/Home/distributorRegister');
            exit();
        }

        // Validate phone format (Vietnam)
        if (!preg_match('/^(\+?84|0)[0-9]{9,10}$/', str_replace(' ', '', $phone))) {
            $_SESSION['error'] = 'Số điện thoại không hợp lệ (VD: 0123456789 hoặc +84123456789)';
            header('Location: ' . APP_URL . '/Home/distributorRegister');
            exit();
        }

        try {
            $hopDongModel = $this->model('HopDongModel');

            // Check if already registered
            if ($hopDongModel->hasActiveContract($email)) {
                $_SESSION['error'] = 'Bạn đã có đơn đăng ký. Vui lòng chờ admin duyệt';
                header('Location: ' . APP_URL . '/Home/distributorRegister');
                exit();
            }

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
                    if (!is_dir($uploadsDir)) {
                        @mkdir($uploadsDir, 0755, true);
                    }

                    $tmp = $_FILES['contract_file']['tmp_name'];
                    $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['contract_file']['name']));
                    $dest = $uploadsDir . '/' . $name;

                    if (move_uploaded_file($tmp, $dest)) {
                        $filePath = 'uploads/contracts/' . $name;
                        $hopDongModel->updateContractFile($email, $filePath);
                    }
                }

                $_SESSION['success'] = 'Chờ admin duyệt, vui lòng chờ!';
                header('Location: ' . APP_URL . '/Home/distributorRegister');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại';
                header('Location: ' . APP_URL . '/Home/distributorRegister');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/Home/distributorRegister');
        }
        exit();
    }

    // 👤 Hiển thị thông tin cá nhân của user
    public function profile()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $userModel = $this->model('UserModel');
        $userStmt = $userModel->findByEmail($_SESSION['user']['email']);
        $user = $userStmt ? $userStmt->fetch(PDO::FETCH_ASSOC) : null;

        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tìm thấy';
            header('Location: ' . APP_URL . '/Home/show');
            exit();
        }

        $this->view('homePage', [
            'page' => 'UserProfileView',
            'user' => $user
        ]);
    }

    // 💾 Cập nhật thông tin cá nhân của user
    public function updateProfile()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/Home/profile');
            exit();
        }

        $email = $_SESSION['user']['email'];
        $fullname = $_POST['fullname'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';

        // Validate input
        if (empty($fullname) || strlen($fullname) < 2) {
            $_SESSION['error'] = 'Họ tên phải có ít nhất 2 ký tự';
            header('Location: ' . APP_URL . '/Home/profile');
            exit();
        }

        $userModel = $this->model('UserModel');
        $result = $userModel->updateProfile($email, $fullname, $phone, $address);

        if ($result) {
            $_SESSION['user']['fullname'] = $fullname;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['address'] = $address;
            $_SESSION['success'] = 'Cập nhật thông tin thành công!';
        } else {
            $_SESSION['error'] = 'Cập nhật thất bại. Vui lòng thử lại!';
        }

        header('Location: ' . APP_URL . '/Home/profile');
        exit();
    }

    // Hiển thị đơn hàng dạng hợp đồng
    public function orderContract($orderId = null)
    {
        if (!$orderId) {
            header('Location: ' . APP_URL . '/Home');
            exit();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $orderModel = $this->model('OrderModel');
        $orderDetailModel = $this->model('OrderDetailModel');
        
        $order = $orderModel->getOrderById($orderId);
        
        // Kiểm tra order tồn tại và thuộc về user hiện tại
        if (!$order || $order['user_email'] !== $_SESSION['user']['email']) {
            echo '<div class="alert alert-danger">Đơn hàng không tồn tại hoặc không có quyền xem!</div>';
            return;
        }

        $orderDetails = $orderDetailModel->getOrderDetailsByOrderId($orderId);

        // Hiển thị hợp đồng trực tiếp (không qua homePage layout)
        $data = [
            'order' => $order,
            'order_details' => $orderDetails
        ];
        extract($data);
        include __DIR__ . '/../views/Font_end/OrderContractView.php';
    }
}

