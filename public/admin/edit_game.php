<?php
session_start();
include '../config/config.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Получаем ID игры из URL
$game_id = $_GET['id'];

// Получаем данные игры
$stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$game_id]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    echo "Игра не найдена!";
    exit();
}

// Обновляем данные игры, если форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Обрабатываем изображение
    $image = $game['image'];  // Оставляем текущее изображение, если новое не загружено
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $uploadDir = '../uploads/games/';
        $destination = $uploadDir . $imageName;

        // Перемещаем новое изображение
        if (move_uploaded_file($imageTmpPath, $destination)) {
            $image = $imageName;  // Обновляем изображение только если новое успешно загружено
        }
    }

    // Обновляем игру в базе данных
    $stmt = $db->prepare("UPDATE games SET title = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $description, $price, $image, $game_id]);

    echo "Игра успешно обновлена!";
    header("Location: admin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать игру</title>
</head>
<body>

<h1>Редактировать игру</h1>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Название игры:</label>
    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($game['title']); ?>" required><br>

    <label for="description">Описание:</label>
    <textarea name="description" id="description" required><?php echo htmlspecialchars($game['description']); ?></textarea><br>

    <label for="price">Цена:</label>
    <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($game['price']); ?>" required><br>

    <label for="image">Изображение:</label>
    <input type="file" name="image" id="image"><br>
    <?php if ($game['image']): ?>
        <p>Текущее изображение: <img src="../uploads/games/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" width="150"></p>
    <?php endif; ?>

    <button type="submit">Сохранить изменения</button>
</form>


</body>
</html>
