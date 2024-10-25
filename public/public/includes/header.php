<nav>
    <ul>
        <li><a href="/public/index.php">Главная</a></li>
        <li><a href="/public/games.php">Игры</a></li>
        <li><a href="/public/profile.php">Профиль</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="/public/logout.php">Выход</a></li>
        <?php else: ?>
            <li><a href="/public/login.php">Вход</a></li>
            <li><a href="/public/register.php">Регистрация</a></li>
        <?php endif; ?>
    </ul>
</nav>
