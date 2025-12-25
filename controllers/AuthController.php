    
<?php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

//session_start();

class AuthController extends Controller {
    // Hiển thị form đăng ký
    //http://localhost/MVC3/AuthController/Show
    public function Show() {
        $this->view("homePage",["page"=>"RegisterView"]);
    }
    // Xử lý đăng ký, gửi OTP
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if ($fullname === '' || $email === '' || $password === '') {
                echo '<div class="container mt-5"><div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin!</div></div>';
                $this->view("homePage",["page"=>"RegisterView"]);
                return;
            }

            // Tạo mã OTP
            $otp = rand(100000, 999999);
            $_SESSION['register'] = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'otp' => $otp
            ];
            // Gửi OTP qua email
            $this->sendOtpEmail($email, $otp);

            // Hiển thị form nhập OTP
            $this->view("homePage",["page"=>"OtpView"]);
        }
    }

    // Gửi OTP qua Gmail
    private function sendOtpEmail($email, $otp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your email'; // Thay bằng Gmail của bạn
            $mail->Password = 'your password'; // Thay bằng App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your email', 'Your App');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Mã OTP xác thực đăng ký";
            $mail->Body = "Mã OTP của bạn là: <b>$otp</b>";

            $mail->send();
        } catch (Exception $e) {
            echo "Gửi email thất bại: {$mail->ErrorInfo}";
        }
    }

    // Xác thực OTP
    public function verifyOtp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputOtp = $_POST['otp'];
            if (isset($_SESSION['register']) && $_SESSION['register']['otp'] == $inputOtp) {
                // Lưu user vào DB
                $user = $this->model('UserModel');
                $email = $_SESSION['register']['email'];
                if ($user->emailExists($email)) {
                    echo '<div class="container mt-5"><div class="alert alert-danger">Email đã được đăng ký. Vui lòng sử dụng email khác!</div></div>';
                    unset($_SESSION['register']);
                    $this->view("homePage",["page"=>"RegisterView"]);
                    return;
                }
                $user->email = $email;
                $user->password = $_SESSION['register']['password'];
                $user->fullname = $_SESSION['register']['fullname'];
                $user->token = bin2hex(random_bytes(16));
                $user->create();
                unset($_SESSION['register']);
                echo '<div class="container mt-5"><div class="alert alert-success">Đăng ký thành viên thành công! Bạn có thể <a href="' . APP_URL . '/AuthController/ShowLogin" class="btn btn-success ms-2">Đăng nhập để đặt hàng</a></div></div>';
                // Hoặc tự động đăng nhập và chuyển sang trang đặt hàng nếu muốn
                // Load created user to get id/user_id
                $created = $this->model('UserModel')->findByEmail($email);
                $createdUser = $created ? $created->fetch(PDO::FETCH_ASSOC) : null;
                if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
                $_SESSION['user'] = [
                    'user_id' => $createdUser['user_id'] ?? $createdUser['id'] ?? null,
                    'email' => $user->email,
                    'fullname' => $user->fullname
                ];
                header('Location: ' . APP_URL . '/Home');
                exit();
            } else {
                echo '<div class="container mt-5"><div class="alert alert-danger">Mã OTP không đúng!</div></div>';
                $this->view("homePage",["page"=>"OtpView"]);
            }
        }
    }
    public function dangnhap(){
        // Render standalone login page without site navbar/header
        $this->view("Font_end/LoginView");
    }
    // Hiển thị form đăng nhập
    public function ShowLogin() {
      //  $this->view("Font_end/LoginView");
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
        elseif (!$isLoggedIn && !empty($cart)) {
            if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
            // Lưu đường dẫn đích để chuyển hướng về sau khi đăng nhập
            $_SESSION['return_to'] = APP_URL . '/Home/checkoutInfo';
            header('Location: ' . APP_URL . '/AuthController/dangnhap');
            exit();
        }

        // 3. Đã đăng nhập nhưng giỏ hàng trống
        elseif ($isLoggedIn && empty($cart)) {
            $this->view("homePage", [
                "page" => "OrderView",
                "listProductOrder" => [],
                "success" => "Giỏ hàng trống!"
            ]);
            return;
        }

        // 4. Chưa đăng nhập và giỏ hàng trống
        elseif (!$isLoggedIn && empty($cart)) {
            header('Location: ' . APP_URL . '/AuthController/dangnhap');
            exit();
        }
        else {
            $this->view("Font_end/LoginView");
        }
        //}
        // Trường hợp không xác định
        // header('Location: ' . APP_URL . '/Home');
        // exit();
    }

    

        // Xử lý đăng nhập
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $userModel = $this->model('UserModel');
            $stmt = $userModel->findByEmail($email);
            $user = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
            // Chặn tài khoản bị khóa
            if ($user && isset($user['is_locked']) && intval($user['is_locked']) === 1) {
                echo '<div class="container mt-5"><div class="alert alert-danger">Tài khoản của bạn đã bị khóa.</div></div>';
                $this->view("Font_end/LoginView");
                return;
            }
            // Normal user login — set session and redirect to Home (Home::show will route by role)
            if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
                if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
                $_SESSION['user'] = [
                    'user_id' => $user['user_id'] ?? $user['id'] ?? null,
                    'email' => $user['email'],
                    'fullname' => $user['fullname'],
                    'user_role' => $user['user_role'] ?? null,
                ];
                // If the user is a shipper, require entering current address before accessing shipper pages
                $isShipper = isset($user['user_role']) && intval($user['user_role']) === 2;
                if ($isShipper) {
                    // Redirect to shipper address entry page
                    header('Location: ' . APP_URL . '/Shipper/address');
                    exit();
                }
                // Redirect về Home
                header('Location: ' . APP_URL . '/Home');
                exit();
            }

            // Super-admin fallback (hardcoded credentials in config)
            if (defined('SUPER_ADMIN_EMAIL') && defined('SUPER_ADMIN_PASSWORD') &&
                strtolower($email) === strtolower(SUPER_ADMIN_EMAIL) && $password === SUPER_ADMIN_PASSWORD) {
                if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
                $_SESSION['user'] = [
                    'id' => 0,
                    'email' => SUPER_ADMIN_EMAIL,
                    'fullname' => 'Admin Tổng',
                    'is_admin' => 1
                ];
                // Redirect to Home — Home::show will detect admin role and redirect to Admin
                header('Location: ' . APP_URL . '/Home');
                exit();
            }

            // Failed login
            echo '<div class="container mt-5"><div class="alert alert-danger">Email hoặc mật khẩu không đúng!</div></div>';
            $this->view("Font_end/LoginView");
        }
    }


    // Đăng xuất
    public function logout() {
        session_destroy();
        header('Location: ' . APP_URL . '/Home');
        exit();
    }

    // Hiển thị form quên mật khẩu
    public function forgotPassword() {
        //$this->view("Font_end/ForgotPasswordView");
        $this->view("homePage",["page"=>"ForgotPasswordView"]);
    }

    // Xử lý gửi lại mật khẩu mới qua email
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $userModel = $this->model('UserModel');
            $stmt = $userModel->findByEmail($email);
            $user = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;

            $message = null;
            $messageType = 'danger';

            if ($user) {
                $newPass = substr(bin2hex(random_bytes(4)), 0, 8);
                $userModel->updatePassword($email, password_hash($newPass, PASSWORD_DEFAULT));
                $this->sendNewPasswordEmail($email, $newPass);
                $message = 'Mật khẩu mới đã được gửi về email của bạn. Bạn có thể đăng nhập bằng mật khẩu mới và đổi mật khẩu luôn.';
                $messageType = 'success';
            } else {
                $message = 'Email không tồn tại!';
            }

            $this->view("homePage",[
                "page"=>"ForgotPasswordView",
                "message" => $message,
                "messageType" => $messageType,
                "prefillEmail" => $email
            ]);
            
        }
    }

    // Gửi mật khẩu mới qua email
    private function sendNewPasswordEmail($email, $newPass) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'Nhập email của m vào con chó';
            $mail->Password = 'mật khẩu';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('your email', 'Your App');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Mật khẩu mới cho tài khoản của bạn";
            $mail->Body = "Mật khẩu mới của bạn là: <b>$newPass</b>";
            $mail->send();
        } catch (Exception $e) {
            // Không echo lỗi ra ngoài
        }
    }

    // Hiển thị form đổi mật khẩu (user đã đăng nhập)
    public function showChangePassword() {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        $this->view("homePage",["page"=>"ChangePasswordView"]);
    }

    // Xử lý đổi mật khẩu
    public function changePassword() {
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($current === '' || $new === '' || $confirm === '') {
                echo '<div class="container mt-3"><div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin.</div></div>';
                $this->view("homePage",["page"=>"ChangePasswordView"]);
                return;
            }
            if (strlen($new) < 6) {
                echo '<div class="container mt-3"><div class="alert alert-danger">Mật khẩu mới phải tối thiểu 6 ký tự.</div></div>';
                $this->view("homePage",["page"=>"ChangePasswordView"]);
                return;
            }
            if ($new !== $confirm) {
                echo '<div class="container mt-3"><div class="alert alert-danger">Xác nhận mật khẩu không khớp.</div></div>';
                $this->view("homePage",["page"=>"ChangePasswordView"]);
                return;
            }

            $email = $_SESSION['user']['email'] ?? null;
            if (!$email) {
                echo '<div class="container mt-3"><div class="alert alert-danger">Không xác định được tài khoản.</div></div>';
                $this->view("homePage",["page"=>"ChangePasswordView"]);
                return;
            }

            $userModel = $this->model('UserModel');
            $stmt = $userModel->findByEmail($email);
            $user = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
            if (!$user || !isset($user['password']) || !password_verify($current, $user['password'])) {
                echo '<div class="container mt-3"><div class="alert alert-danger">Mật khẩu hiện tại không đúng.</div></div>';
                $this->view("homePage",["page"=>"ChangePasswordView"]);
                return;
            }

            $hash = password_hash($new, PASSWORD_DEFAULT);
            $updated = $userModel->updatePassword($email, $hash);
            if ($updated) {
                echo '<div class="container mt-3"><div class="alert alert-success">Đổi mật khẩu thành công.</div></div>';
            } else {
                echo '<div class="container mt-3"><div class="alert alert-danger">Đổi mật khẩu thất bại. Vui lòng thử lại.</div></div>';
            }
            $this->view("homePage",["page"=>"ChangePasswordView"]);
        } else {
            header('Location: ' . APP_URL . '/AuthController/showChangePassword');
            exit();
        }
    }

}
