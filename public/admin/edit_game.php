<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');  // Перенаправляем на страницу входа, если не авторизован
    exit();
}

// Получаем ID игры из URL
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];

    // Получаем данные об игре
    $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        echo "Игра не найдена.";
        exit();
    }
} else {
    echo "ID игры не указан.";
    exit();
}

// Обработка формы редактирования игры
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_name = $game['image'];  // Используем текущее изображение

    // Проверка, загружено ли новое изображение
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        // Путь для загрузки нового изображения
        $upload_dir = '../uploads/';
        $new_image_name = basename($_FILES['image']['name']);
        $new_image_path = $upload_dir . $new_image_name;

        // Загрузка файла
        if (move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path)) {
            $image_name = $new_image_name;  // Обновляем имя изображения
        } else {
            $error = "Ошибка загрузки нового изображения.";
        }
    }

    // Обновляем данные игры в базе данных
    $stmt = $db->prepare("UPDATE games SET title = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $description, $price, $image_name, $game_id]);

    // Перенаправляем на страницу управления играми
    header('Location: manage_games.php');
    exit();
}

include '../includes/admin/header.php';  // Подключаем шапку для админки
?>

<h1>Редактирование игры</h1>

<?php if (isset($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название игры:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($game['title']); ?>" required><br>

    <label for="description">Описание:</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($game['description']); ?></textarea><br>

    <label for="price">Цена (в тенге):</label>
    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($game['price']); ?>" required><br>

    <label for="image">Изображение (текущее: <?php echo htmlspecialchars($game['image']); ?>):</label>
    <input type="file" id="image" name="image" accept="image/*"><br>

    <button type="submit">Сохранить изменения</button>
</form>

<?php
include '../includes/admin/footer.php';  // Подключаем подвал для админки
?>
