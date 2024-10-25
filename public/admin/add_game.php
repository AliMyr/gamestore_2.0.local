<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}

// Обработка формы добавления игры
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Проверка на загрузку изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        // Путь для загрузки изображения
        $upload_dir = '../uploads/';
        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;

        // Загружаем файл
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Сохраняем информацию об игре в базе данных
            $stmt = $db->prepare("INSERT INTO games (title, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $price, $image_name]);

            // Перенаправляем на страницу управления играми
            header('Location: manage_games.php');
            exit();
        } else {
            $error = "Ошибка загрузки изображения.";
        }
    } else {
        $error = "Необходимо загрузить изображение.";
    }
}

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Добавление игры</h1>

<?php if (isset($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название игры:</label>
    <input type="text" id="title" name="title" required><br>

    <label for="description">Описание:</label>
    <textarea id="description" name="description" required></textarea><br>

    <label for="price">Цена (в тенге):</label>
    <input type="number" id="price" name="price" required><br>

    <label for="image">Изображение:</label>
    <input type="file" id="image" name="image" accept="image/*" required><br>

    <button type="submit">Добавить игру</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
