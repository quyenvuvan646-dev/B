<?php
require_once __DIR__ . '/../app/Controller.php';
require_once __DIR__ . '/../app/Auth.php';
require_once __DIR__ . '/../models/FavoriteModel.php';
require_once __DIR__ . '/../models/AdProducModel.php';

class Favorite extends Controller
{
    // Alias for default action 'Show' used by App router
    public function Show()
    {
        return $this->index();
    }

    public function index()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $user = Auth::user();
        if (!$user || !isset($user['email'])) {
            header('Location: ' . APP_URL . '/?url=Home/login');
            exit();
        }

        $favModel = new FavoriteModel();
        $favorites = $favModel->getFavoritesWithProducts($user['email']);

        $bannerModel = $this->model("BannerModel");
        $banners = $bannerModel->getActiveBanners();

        $favoritesMap = [];
        foreach ($favorites as $f) {
            $favoritesMap[$f['masp']] = true;
        }

        $this->view('homePage', [
            'page' => 'FavoriteListView',
            'productList' => $favorites,
            'searchTerm' => '',
            'banners' => $banners,
            'favoritesMap' => $favoritesMap
        ]);
    }

    public function toggle($productCode = null)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        header('Content-Type: application/json');

        $user = Auth::user();
        if (!$user || !isset($user['email'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để lưu yêu thích.']);
            return;
        }

        if (!$productCode) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Thiếu mã sản phẩm.']);
            return;
        }

        $favModel = new FavoriteModel();
        $nowFavorite = $favModel->toggle($user['email'], $productCode);

        echo json_encode([
            'success' => true,
            'favorited' => $nowFavorite
        ]);
    }
}
