<?php
include '../config/config.php';  // Подключение к базе данных
include '../includes/public/header.php';  // Подключаем шапку

// Получаем список игр из базы данных
$stmt = $db->query("SELECT * FROM games ORDER BY created_at DESC");
$games = $stmt->fetchAll();
?>

<h1>Каталог игр</h1>

<?php if (count($games) > 0): ?>
    <ul>
        <?php foreach ($games as $game): ?>
            <li>
                <h2><?php echo htmlspecialchars($game['title']); ?></h2>
                <p><?php echo htmlspecialchars($game['description']); ?></p>
                <p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>
                <a href="game.php?id=<?php echo $game['id']; ?>">Подробнее</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Игр пока нет.</p>
<?php endif; ?>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
