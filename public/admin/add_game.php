<?php
session_start();

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include '../config/config.php';  // Подключение к базе данных

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
    if (empty($image)) {
        $errors[] = "Пожалуйста, загрузите изображение.";
    }

    // Если ошибок нет, загружаем изображение и добавляем игру
    if (empty($errors)) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        // Добавляем игру в базу данных
        $stmt = $db->prepare("INSERT INTO games (title, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $price, $image]);

        header('Location: manage_games.php');  // Перенаправляем на страницу управления играми
        exit();
    }
}

include '../includes/admin/header.php';  // Подключаем шапку админки
?>

<h1>Добавить новую игру</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название игры:</label>
    <input type="text" name="title" id="title" required><br>

    <label for="description">Описание игры:</label>
    <textarea name="description" id="description" required></textarea><br>

    <label for="price">Цена:</label>
    <input type="text" name="price" id="price" required><br>

    <label for="image">Изображение:</label>
    <input type="file" name="image" id="image" required><br>

    <button type="submit">Добавить игру</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал админки
?>
