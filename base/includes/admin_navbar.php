<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Определяем базовый URL динамически
$base_url = "https://" . $_SERVER['HTTP_HOST'] . "/admin";
?>

<nav>
    <ul>
        <li><a href="<?php echo $base_url; ?>/index.php">Главная</a></li>
        <li><a href="<?php echo $base_url; ?>/manage_games.php">Управление играми</a></li>
        <li><a href="<?php echo $base_url; ?>/add_game.php">Добавить игру</a></li>
        <li><a href="<?php echo $base_url; ?>/manage_users.php">Управление пользователями</a></li>
        <li><a href="<?php echo $base_url; ?>/manage_reviews.php">Управление отзывами</a></li>
        <li><a href="<?php echo $base_url; ?>/statistics.php">Статистика</a></li>
        <li><a href="<?php echo $base_url; ?>/sales_report.php">Отчет о продажах</a></li>
    </ul>
</nav>
