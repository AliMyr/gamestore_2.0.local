<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Проверяем, передан ли ID игры
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Проверяем, куплена ли игра пользователем
    $stmt = $db->prepare("SELECT * FROM user_games WHERE user_id = ? AND game_id = ?");
    $stmt->execute([$user_id, $game_id]);

    if ($stmt->rowCount() === 0) {
        echo "У вас нет доступа к этой игре.";
        exit();
    }

    // Получаем информацию об игре
    $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$game_id]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        echo "Игра не найдена.";
        exit();
    }
} else {
    echo "ID игры не передан.";
    exit();
}

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1>Запуск игры: <?php echo htmlspecialchars($game['title']); ?></h1>
<img src="<?php echo htmlspecialchars($game['image']); ?>" alt="Изображение игры" style="max-width: 400px;">
<p><?php echo htmlspecialchars($game['description']); ?></p>
<p>Вы успешно запустили игру! Наслаждайтесь игрой.</p>

<a href="profile.php">Вернуться в профиль</a>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
