<?php
session_start();

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include '../config/config.php';  // Подключение к базе данных

// Получаем информацию об игре
$game_id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    echo "Игра не найдена!";
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $image = $_FILES['image']['name'];

    // Простая валидация
    if (empty($title)) {
        $errors[] = "Название игры не может быть пустым.";
    }
    if (empty($description)) {
        $errors[] = "Описание игры не может быть пустым.";
    }
    if (empty($price) || !is_numeric($price)) {
        $errors[] = "Цена должна быть числом.";
    }

    // Обновляем игру в базе данных
    if (empty($errors)) {
        if (!empty($image)) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

            $stmt = $db->prepare("UPDATE games SET title = ?, description = ?, price = ?, image = ? WHERE id = ?");
            $stmt->execute([$title, $description, $price, $image, $game_id]);
        } else {
            $stmt = $db->prepare("UPDATE games SET title = ?, description = ?, price = ? WHERE id = ?");
            $stmt->execute([$title, $description, $price, $game_id]);
        }

        header('Location: manage_games.php');  // Перенаправляем на страницу управления играми
        exit();
    }
}

include '../includes/admin/header.php';  // Подключаем шапку админки
?>

<h1>Редактировать игру</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название игры:</label>
    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($game['title']); ?>" required><br>

    <label for="description">Описание игры:</label>
    <textarea name="description" id="description" required><?php echo htmlspecialchars($game['description']); ?></textarea><br>

    <label for="price">Цена:</label>
    <input type="text" name="price" id="price" value="<?php echo htmlspecialchars($game['price']); ?>" required><br>

    <label for="image">Изображение:</label>
    <input type="file" name="image" id="image"><br>

    <button type="submit">Сохранить изменения</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал админки
?>
