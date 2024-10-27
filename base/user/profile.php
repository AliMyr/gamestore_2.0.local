<?php
session_start();
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/navbar.php";

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Получение данных о пользователе
$user_sql = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Получение списка купленных игр
$purchased_games_sql = "
    SELECT games.title, games.cover_image, games.price, sales.sale_date, sales.activation_code 
    FROM sales
    JOIN games ON sales.game_id = games.game_id
    WHERE sales.user_id = ?
    ORDER BY sales.sale_date DESC";
$purchased_games_stmt = $conn->prepare($purchased_games_sql);
$purchased_games_stmt->bind_param("i", $user_id);
$purchased_games_stmt->execute();
$purchased_games = $purchased_games_stmt->get_result();
?>

<h2>Профиль пользователя</h2>
<div class="profile-info">
    <p><strong>Имя пользователя:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Дата регистрации:</strong> <?php echo htmlspecialchars($user['registration_date']); ?></p>
</div>

<h3>Купленные игры</h3>
<?php if ($purchased_games->num_rows > 0): ?>
    <div class="purchased-games">
        <?php while ($game = $purchased_games->fetch_assoc()): ?>
            <div class="game-item">
                <img src="<?php echo htmlspecialchars($game['cover_image'] ?? 'https://via.placeholder.com/200x300'); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" class="game-cover">
                <p><strong><?php echo htmlspecialchars($game['title']); ?></strong></p>
                <p class="price">Цена: <?php echo number_format($game['price'], 2); ?> ₸</p>
                <p>Дата покупки: <?php echo htmlspecialchars($game['sale_date']); ?></p>
                <p class="activation-code"><strong>Код активации:</strong> <?php echo htmlspecialchars($game['activation_code']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>Вы еще не приобрели ни одной игры.</p>
<?php endif; ?>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
