<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Определяем базовый URL динамически
$base_url = "https://" . $_SERVER['HTTP_HOST'] . "/user";
?>

<nav>
    <ul>
        <li><a href="<?php echo $base_url; ?>/index.php">Главная</a></li>
        <li><a href="<?php echo $base_url; ?>/catalog.php">Каталог игр</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="<?php echo $base_url; ?>/profile.php">Мой профиль</a></li>
            <li><a href="<?php echo $base_url; ?>/logout.php">Выйти</a></li>
        <?php else: ?>
            <li><a href="<?php echo $base_url; ?>/login.php">Войти</a></li>
            <li><a href="<?php echo $base_url; ?>/register.php">Регистрация</a></li>
        <?php endif; ?>
    </ul>
</nav>
