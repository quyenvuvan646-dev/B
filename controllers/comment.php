<?php
class comment extends Controller{
    public function show(){
        $obj=$this->model("commentModel");
        $data=$obj->all("tblsanpham");
        $this->view("HomePage",["page"=>"ProductListView","productList"=>$data]);
    }

    public function create(){
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';

        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để bình luận.';
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $user = $_SESSION['user'];
        $user_email = $user['email'] ?? null;
        $user_name = $user['fullname'] ?? 'Người dùng';

        $masp = $_POST['masp'] ?? null;
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
        $content = trim($_POST['content'] ?? '');

        if (!$masp || $content === '') {
            $_SESSION['error'] = 'Thiếu dữ liệu bình luận.';
            header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp));
            exit();
        }

        // Check user has bought the product
        $commentModel = $this->model('CommentModel');
        $hasBought = $commentModel->userHasBoughtProduct($user_email, $masp);
        if (!$hasBought) {
            $_SESSION['error'] = 'Chỉ người đã mua sản phẩm mới có thể đánh giá.';
            header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp));
            exit();
        }

        // Optional: prevent duplicate main review
        if ($commentModel->userHasReviewedProduct($user_email, $masp)) {
            $_SESSION['error'] = 'Bạn đã đánh giá sản phẩm này rồi.';
            header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp));
            exit();
        }

        // Basic banned words filter
        $bannedWordsPath = __DIR__ . '/../data/banned_words.json';
        if (file_exists($bannedWordsPath)) {
            $words = json_decode(file_get_contents($bannedWordsPath), true) ?: [];
            foreach ($words as $w) {
                if ($w && stripos($content, $w) !== false) {
                    $_SESSION['error'] = 'Nội dung chứa từ không phù hợp.';
                    header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp));
                    exit();
                }
            }
        }

        // Save comment
        $commentId = $commentModel->addComment($masp, $user_email, $user_name, $rating, $content);
        $_SESSION['success'] = 'Đã gửi đánh giá thành công.';
        header('Location: ' . APP_URL . '/Home/detail/' . urlencode($masp));
        exit();
    }

}