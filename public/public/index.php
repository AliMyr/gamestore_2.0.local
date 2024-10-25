<?php
session_start();
include '../config/config.php';  // Подключение к базе данных
include '../includes/public/header.php';  // Подключаем шапку

// Получаем список игр из базы данных
$stmt = $db->query("SELECT * FROM games ORDER BY created_at DESC");
$games = $stmt->fetchAll();
?>

<h2>Каталог игр</h2>

<div class="container">
    <?php if (count($games) > 0): ?>
        <div class="game-grid">
            <?php foreach ($games as $game): ?>
                <div class="game-card">
                    <h3><?php echo htmlspecialchars($game['title']); ?></h3>
                    <?php if ($game['image']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
                    <?php endif; ?>
                    <p><?php echo htmlspecialchars($game['description']); ?></p>
                    <p>Цена: <?php echo htmlspecialchars($game['price']); ?> тенге</p>
                    <a href="game.php?id=<?php echo $game['id']; ?>">Подробнее</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Игр пока нет.</p>
    <?php endif; ?>
</div>

<?php
include '../includes/public/footer.php';  // Подключаем подвал
?>
