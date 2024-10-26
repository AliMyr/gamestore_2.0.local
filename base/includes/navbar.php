<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <ul>
        <li><a href="https://gamestore.local/user/index.php">Главная</a></li>
        <li><a href="https://gamestore.local/user/catalog.php">Каталог игр</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="https://gamestore.local/user/profile.php">Мой профиль</a></li>
            <li><a href="https://gamestore.local/user/logout.php">Выйти</a></li>
        <?php else: ?>
            <li><a href="https://gamestore.local/user/login.php">Войти</a></li>
            <li><a href="https://gamestore.local/user/register.php">Регистрация</a></li>
        <?php endif; ?>
    </ul>
</nav>
