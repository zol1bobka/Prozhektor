<?php
session_start();
require_once __DIR__ . '/config.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

$pdo = get_pdo();

$registrations = $pdo->query('SELECT * FROM registrations ORDER BY created_at DESC')->fetchAll();
$reviews       = $pdo->query('SELECT * FROM reviews ORDER BY created_at DESC')->fetchAll();
$contacts      = $pdo->query('SELECT * FROM contacts ORDER BY created_at DESC')->fetchAll();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель — Прожектор</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            margin: 0;
            background: #f3f5f7;
        }
        .admin-header {
            background: #37b7a0;
            color: #fff;
            padding: 14px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header-title {
            font-size: 18px;
            font-weight: 600;
        }
        .admin-header-right a {
            color: #fff;
            text-decoration: underline;
            font-size: 14px;
        }
        .admin-container {
            max-width: 1200px;
            margin: 20px auto 40px;
            padding: 0 16px;
        }
        h2 {
            font-size: 18px;
            margin: 24px 0 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
            font-size: 14px;
        }
        th, td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e5e5;
            vertical-align: top;
        }
        th {
            background: #f0fbf8;
            text-align: left;
            font-weight: 600;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .empty {
            font-size: 14px;
            color: #666;
            margin: 6px 0 16px;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            background: #e9f5ff;
        }
        .contest-badge {
            background: #ffe8f3;
        }
        @media (max-width: 768px) {
            table, thead, tbody, tr, th, td {
                display: block;
            }
            th {
                display: none;
            }
            tr {
                margin-bottom: 10px;
                border-radius: 10px;
                border: 1px solid #e0e0e0;
                overflow: hidden;
            }
            td {
                border-bottom: 1px solid #eee;
            }
            td::before {
                content: attr(data-label);
                display: block;
                font-weight: 600;
                margin-bottom: 2px;
                color: #666;
            }
        }
    </style>
</head>
<body>
<div class="admin-header">
    <div class="admin-header-title">Админ-панель конкурса «Прожектор»</div>
    <div class="admin-header-right">
        Здравствуйте, <?= htmlspecialchars($_SESSION['admin_login'] ?? 'admin', ENT_QUOTES, 'UTF-8') ?> |
        <a href="logout.php">Выйти</a> |
        <a href="index.html">На сайт</a>
    </div>
</div>

<div class="admin-container">
    <h2>Заявки участников</h2>
    <?php if (!$registrations): ?>
        <div class="empty">Пока нет заявок.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Возраст</th>
                    <th>Конкурс</th>
                    <th>Фото</th>
                    <th>Музыка</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($registrations as $r): ?>
                <tr>
                    <td data-label="ID"><?= $r['id'] ?></td>
                    <td data-label="ФИО"><?= htmlspecialchars($r['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Телефон"><?= htmlspecialchars($r['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Возраст"><?= (int)$r['age'] ?></td>
                    <td data-label="Конкурс"><span class="badge contest-badge"><?= htmlspecialchars($r['contest'], ENT_QUOTES, 'UTF-8') ?></span></td>
                    <td data-label="Фото">
                        <?php if (!empty($r['photo_path'])): ?>
                            <a href="<?= htmlspecialchars($r['photo_path'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Открыть</a>
                        <?php endif; ?>
                    </td>
                    <td data-label="Музыка">
                        <?php if (!empty($r['music_path'])): ?>
                            <a href="<?= htmlspecialchars($r['music_path'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Открыть</a>
                        <?php endif; ?>
                    </td>
                    <td data-label="Дата"><?= htmlspecialchars($r['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2>Отзывы</h2>
    <?php if (!$reviews): ?>
        <div class="empty">Пока нет отзывов.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Оценка</th>
                    <th>Текст</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($reviews as $r): ?>
                <tr>
                    <td data-label="ID"><?= $r['id'] ?></td>
                    <td data-label="Имя"><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Оценка"><?= $r['rating'] ?></td>
                    <td data-label="Текст"><?= nl2br(htmlspecialchars($r['text'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td data-label="Дата"><?= htmlspecialchars($r['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2>Сообщения из формы «Связаться с нами»</h2>
    <?php if (!$contacts): ?>
        <div class="empty">Пока нет сообщений.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Сообщение</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($contacts as $c): ?>
                <tr>
                    <td data-label="ID"><?= $c['id'] ?></td>
                    <td data-label="Имя"><?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Email"><?= htmlspecialchars($c['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Сообщение"><?= nl2br(htmlspecialchars($c['message'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td data-label="Дата"><?= htmlspecialchars($c['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
