<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
$game_id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch();

if (!$game) {
    echo "Игра не найдена!";
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];

    // Обработка нового изображения (если загружено)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_path = '../uploads/' . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    } else {
        $image_name = $game['image'];  // Оставляем старое изображение
    }

    // Проверка на пустоту полей
    if (empty($title)) {
        $errors[] = "Название игры не может быть пустым.";
    }
    if (empty($description)) {
        $errors[] = "Описание игры не может быть пустым.";
    }
    if (empty($price) || !is_numeric($price)) {
        $errors[] = "Некорректная цена.";
    }

    // Если ошибок нет, обновляем игру в базе данных
    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE games SET title = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $stmt->execute([$title, $description, $price, $image_name, $game_id]);

        // Перенаправляем на страницу управления играми
        header('Location: manage_games.php');
        exit();
    }
}

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Редактирование игры</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название:</label>
    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($game['title']); ?>" required><br>

    <label for="description">Описание:</label>
    <textarea name="description" id="description" required><?php echo htmlspecialchars($game['description']); ?></textarea><br>

    <label for="price">Цена:</label>
    <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($game['price']); ?>" required><br>

    <label for="image">Изображение (оставьте пустым, чтобы не изменять):</label>
    <input type="file" name="image" id="image"><br>
    <?php if ($game['image']): ?>
        <p>Текущее изображение:</p>
        <img src="../uploads/<?php echo htmlspecialchars($game['image']); ?>" width="200">
    <?php endif; ?>

    <button type="submit">Сохранить изменения</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
