<?php
// Simple auth/permission helpers
class Auth {
    // Return current logged user array from session or null
    public static function user()
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    // Return current user id if available
    public static function id()
    {
        $u = self::user();
        if (!$u) return null;
        // Ưu tiên user_id nếu có
        if (isset($u['user_id'])) return $u['user_id'];
        if (isset($u['id'])) return $u['id'];
        return null;
    }

    // Check whether current user has a role by slug
    public static function hasRole($slug)
    {
        // Quick shortcut: if current user's email matches SUPER_ADMIN_EMAIL, treat as admin
        $user = self::user();
        if ($user && defined('SUPER_ADMIN_EMAIL') && isset($user['email']) && strtolower($user['email']) === strtolower(SUPER_ADMIN_EMAIL)) {
            return ($slug === 'admin');
        }

        $uid = self::id();
        if (!$uid) return false;
        // Use UserModel to check
        require_once __DIR__ . '/../models/UserModel.php';
        $um = new UserModel();
        return $um->hasRoleBySlug($uid, $slug);
    }

    // Require role: if user doesn't have role, redirect to home or show 403
    public static function requireRole($slug)
    {
        if (!self::hasRole($slug)) {
            // If not logged in, redirect to login
            if (!self::user()) {
                header('Location: ' . APP_URL . '/?url=Home/login');
                exit();
            }
            // Otherwise show simple 403
            http_response_code(403);
            echo '<h1>403 Forbidden</h1><p>Bạn không có quyền truy cập trang này.</p>';
            exit();
        }
    }
}
