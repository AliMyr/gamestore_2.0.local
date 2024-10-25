<?php
session_start();
include '../config/config.php';  // Подключение к базе данных

// Получаем список всех игр
$stmt = $db->prepare("SELECT * FROM games ORDER BY created_at DESC");
$stmt->execute();
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/public/header.php';  // Подключаем шапку
?>

<h1>Список игр</h1>

<ul>
    <?php foreach ($games as $game): ?>
        <li>
            <h2><a href="game.php?id=<?php echo $game['id']; ?>"><?php echo htmlspecialchars($game['title']); ?></a></h2>
            <p><?php echo htmlspecialchars($game['description']); ?></p>
            <p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>
        </li>
    <?php endforeach; ?>
</ul>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
