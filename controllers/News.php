<?php
class News extends Controller
{
    private function ensureAdmin()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        require_once __DIR__ . '/../app/Auth.php';
        if (!Auth::hasRole('admin')) {
            header('Location: ' . APP_URL . '/Home/');
            exit();
        }
    }

    // User-facing list
    public function list()
    {
        $model = $this->model('NewsModel');
        $news = $model->getPublishedNews(50, 0);
        $this->view('homePage', [ 'page' => 'NewsListView', 'news' => $news ]);
    }

    // User-facing detail
    public function detail($id)
    {
        $model = $this->model('NewsModel');
        $item = $model->getNewsById($id);
        if (!$item || (int)$item['is_published'] !== 1) {
            header('Location: ' . APP_URL . '/News/list');
            exit();
        }
        $this->view('homePage', [ 'page' => 'NewsDetailView', 'item' => $item ]);
    }

    // Admin: list
    public function adminIndex()
    {
        $this->ensureAdmin();
        $model = $this->model('NewsModel');
        $list = $model->getAllNews();
        $this->view('adminPage', [ 'page' => 'AdminNewsListView', 'list' => $list ]);
    }

    // Admin: create
    public function adminCreate()
    {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $isPublished = isset($_POST['is_published']) ? 1 : 0;
            $author = $_SESSION['user']['email'] ?? 'admin@local';

            $imageName = null;
            if (!empty($_FILES['image']['name'])) {
                $safe = preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['image']['name']);
                $imageName = time() . '_' . $safe;
                $destDir = __DIR__ . '/../public/images/news/';
                if (!is_dir($destDir)) { @mkdir($destDir, 0777, true); }
                move_uploaded_file($_FILES['image']['tmp_name'], $destDir . $imageName);
            }

            $model = $this->model('NewsModel');
            $model->create($title, $content, $imageName, $author, $isPublished);
            header('Location: ' . APP_URL . '/News/adminIndex');
            exit();
        }
        $this->view('adminPage', [ 'page' => 'AdminNewsFormView' ]);
    }

    // Admin: edit
    public function adminEdit($id)
    {
        $this->ensureAdmin();
        $model = $this->model('NewsModel');
        $item = $model->getNewsById($id);
        if (!$item) { header('Location: ' . APP_URL . '/News/adminIndex'); exit(); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $isPublished = isset($_POST['is_published']) ? 1 : 0;

            $imageName = null;
            if (!empty($_FILES['image']['name'])) {
                $safe = preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['image']['name']);
                $imageName = time() . '_' . $safe;
                $destDir = __DIR__ . '/../public/images/news/';
                if (!is_dir($destDir)) { @mkdir($destDir, 0777, true); }
                move_uploaded_file($_FILES['image']['tmp_name'], $destDir . $imageName);
            }

            $model->updateNews($id, $title, $content, $imageName, $isPublished);
            header('Location: ' . APP_URL . '/News/adminIndex');
            exit();
        }

        $this->view('adminPage', [ 'page' => 'AdminNewsFormView', 'item' => $item ]);
    }

    // Admin: delete
    public function adminDelete($id)
    {
        $this->ensureAdmin();
        $model = $this->model('NewsModel');
        $model->deleteNews($id);
        header('Location: ' . APP_URL . '/News/adminIndex');
        exit();
    }

    // Admin: toggle publish (ẩn/hiện)
    public function adminToggle($id)
    {
        $this->ensureAdmin();
        $model = $this->model('NewsModel');
        $item = $model->getNewsById($id);
        if ($item) {
            $newState = ((int)$item['is_published'] === 1) ? 0 : 1;
            $model->setPublished($id, $newState);
        }
        header('Location: ' . APP_URL . '/News/adminIndex');
        exit();
    }
}
