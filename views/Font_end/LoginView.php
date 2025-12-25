<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --bg1: #0b1220;
            --bg2: #0f172a;
            --card: #101726;
            --border: #1f2a44;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --accent: #3b82f6;
            --accent-2: #a855f7;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(59,130,246,0.14), transparent 25%),
                        radial-gradient(circle at 80% 0%, rgba(168,85,247,0.12), transparent 30%),
                        linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 70%, #0a0f1d 100%);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }
        .login-shell {
            width: min(420px, 100%);
            background: linear-gradient(145deg, rgba(16,23,42,0.95), rgba(11,17,31,0.98));
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.35);
            position: relative;
            overflow: hidden;
        }
        .login-shell::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 20%, rgba(59,130,246,0.18), transparent 35%),
                        radial-gradient(circle at 80% 0%, rgba(168,85,247,0.16), transparent 35%);
            pointer-events: none;
        }
        .login-header {
            position: relative;
            z-index: 1;
            margin-bottom: 16px;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(59,130,246,0.35);
            background: rgba(59,130,246,0.12);
            color: #93c5fd;
            font-weight: 700;
            font-size: 13px;
        }
        .login-title {
            margin: 10px 0 4px;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .login-sub {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }
        .form-group { margin-top: 14px; position: relative; z-index: 1; }
        label { color: var(--text); font-weight: 600; margin-bottom: 6px; display: block; font-size: 14px; }
        input[type=email], input[type=password] {
            width: 100%;
            background: #0c1322;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px;
            color: var(--text);
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.25);
        }
        .actions { display: flex; flex-direction: column; gap: 10px; margin-top: 16px; z-index:1; position:relative; }
        .btn-primary {
            width: 100%;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: #fff;
            box-shadow: 0 12px 30px rgba(59,130,246,0.35);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 14px 36px rgba(124,58,237,0.32); }
        .btn-ghost {
            width: 100%;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
            color: var(--text);
            border-radius: 10px;
            padding: 11px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
        }
        .links { display: flex; justify-content: space-between; align-items: center; margin-top: 6px; color: var(--muted); font-size: 13px; }
        .links a { color: #93c5fd; text-decoration: none; font-weight: 600; }
        .links a:hover { text-decoration: underline; }
        @media (max-width: 480px) { body { padding: 24px 12px; } .login-shell { padding: 22px; } }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-header">
            <span class="pill"><i class="bi bi-shield-lock"></i> Đăng nhập an toàn</span>
            <div class="login-title">Chào mừng trở lại</div>
            <p class="login-sub">Đăng nhập để tiếp tục mua sắm và theo dõi đơn hàng.</p>
        </div>
        <form action="<?php echo APP_URL; ?>/AuthController/login" method="POST" class="form" autocomplete="on">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>
            <div class="actions">
                <button type="submit" class="btn-primary">Đăng nhập</button>
                <a class="btn-ghost" href="<?php echo APP_URL; ?>/AuthController/Show">Tạo tài khoản mới</a>
            </div>
            <div class="links">
                <a href="<?php echo APP_URL; ?>/AuthController/forgotPassword">Quên mật khẩu?</a>
                <span>Hỗ trợ: support@example.com</span>
            </div>
        </form>
    </div>
</body>
</html>
