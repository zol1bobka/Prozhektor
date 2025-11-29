<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html#block4');
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$age = (int)($_POST['age'] ?? 0);
$contest = $_POST['contest'] ?? '';

$errors = [];
if ($fullName === '') $errors[] = 'Не указано ФИО';
if ($phone === '') $errors[] = 'Не указан телефон';
if ($age <= 0) $errors[] = 'Некорректный возраст';
if ($contest === '') $errors[] = 'Не выбран конкурс';

if (!empty($errors)) {
    echo 'Ошибка: ' . implode(', ', $errors);
    echo '<br><a href="index.html#block4">Вернуться назад</a>';
    exit;
}

$uploadsDir = __DIR__ . '/uploads';
$photoDir = $uploadsDir . '/photos';
$musicDir = $uploadsDir . '/music';

if (!is_dir($photoDir)) mkdir($photoDir, 0777, true);
if (!is_dir($musicDir)) mkdir($musicDir, 0777, true);

$photoPathRel = null;
$musicPathRel = null;

if (!empty($_FILES['photo']['name'] ?? '')) {
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $newName = 'photo_' . uniqid() . '.' . $ext;
    $photoPathAbs = $photoDir . '/' . $newName;
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPathAbs)) {
        $photoPathRel = 'uploads/photos/' . $newName;
    }
}

if (!empty($_FILES['music']['name'] ?? '')) {
    $ext = pathinfo($_FILES['music']['name'], PATHINFO_EXTENSION);
    $newName = 'music_' . uniqid() . '.' . $ext;
    $musicPathAbs = $musicDir . '/' . $newName;
    if (move_uploaded_file($_FILES['music']['tmp_name'], $musicPathAbs)) {
        $musicPathRel = 'uploads/music/' . $newName;
    }
}

try {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        INSERT INTO registrations (full_name, phone, age, contest, photo_path, music_path, created_at)
        VALUES (:full_name, :phone, :age, :contest, :photo_path, :music_path, NOW())
    ");
    $stmt->execute([
        ':full_name' => $fullName,
        ':phone' => $phone,
        ':age' => $age,
        ':contest' => $contest,
        ':photo_path' => $photoPathRel,
        ':music_path' => $musicPathRel,
    ]);

    header('Location: index.html#block4');
    exit;
} catch (Throwable $e) {
    echo 'Ошибка при сохранении данных: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    echo '<br><a href="index.html#block4">Вернуться назад</a>';
    exit;
}
