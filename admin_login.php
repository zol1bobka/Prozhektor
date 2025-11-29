<?php
session_start();
require_once __DIR__ . '/config.php';

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = 'Введите логин и пароль.';
    } else {
        try {
            $pdo = get_pdo();
            $stmt = $pdo->prepare('SELECT id, login, password FROM admin WHERE login = :login LIMIT 1');
            $stmt->execute([':login' => $login]);
            $admin = $stmt->fetch();

            
            if ($admin && $password === 'admin123') {  
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_login'] = $admin['login'];
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $error = 'Неверный логин или пароль.';
            }
        } catch (Throwable $e) {
            $error = 'Ошибка подключения к БД: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход администратора</title>
    <style>
        .admin-login-page {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f3f3f3;
            font-family: "Open Sans", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .admin-login-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 28px 32px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            max-width: 380px;
            width: 100%;
        }
        .admin-login-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .admin-login-subtitle {
            font-size: 13px;
            color: #666;
            margin-bottom: 18px;
        }
        .admin-login-card label {
            font-size: 13px;
            display: block;
            margin-bottom: 4px;
        }
        .admin-login-card input[type="text"],
        .admin-login-card input[type="password"] {
            width: 100%;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .admin-login-card button {
            width: 100%;
            padding: 10px 0;
            border-radius: 999px;
            border: none;
            background: #37b7a0;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(55,183,160,0.35);
        }
        .admin-login-card button:hover {
            transform: translateY(-1px);
        }
        .admin-login-error {
            color: #d62828;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .admin-login-back {
            margin-top: 10px;
            font-size: 13px;
            text-align: center;
        }
        .admin-login-back a {
            color: #555;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="admin-login-page">
        <div class="admin-login-card">
            <div class="admin-login-title">Вход администратора</div>
            <div class="admin-login-subtitle">Введите данные для просмотра заявок и отзывов.</div>

            <?php if ($error): ?>
                <div class="admin-login-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="post" action="admin_login.php">
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" required>

                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Войти</button>
            </form>

            <div class="admin-login-back">
                <a href="index.html">← Вернуться на сайт</a>
            </div>
        </div>
    </div>
</body>
</html>
