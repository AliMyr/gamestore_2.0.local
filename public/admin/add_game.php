<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];

    // Обработка загрузки изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_path = '../uploads/' . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    } else {
        $image_name = null;  // Если изображение не загружено
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

    // Если ошибок нет, добавляем игру в базу данных
    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO games (title, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $price, $image_name]);

        // Перенаправляем на страницу управления играми
        header('Location: manage_games.php');
        exit();
    }
}

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Добавление новой игры</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название:</label>
    <input type="text" name="title" id="title" required><br>

    <label for="description">Описание:</label>
    <textarea name="description" id="description" required></textarea><br>

    <label for="price">Цена:</label>
    <input type="number" name="price" id="price" required><br>

    <label for="image">Изображение:</label>
    <input type="file" name="image" id="image"><br>

    <button type="submit">Добавить игру</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
