<nav>
    <a href="/index.php">Главная</a> |
    <a href="/public/pages/library.php">Моя библиотека</a> |
    <a href="/public/pages/review.php">Оставить отзыв</a> |
    <a href="/public/pages/profile.php">Личный кабинет</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        | <a href="/public/pages/logout.php">Выйти</a>
    <?php else: ?>
        | <a href="/public/pages/login.php">Войти</a> |
        <a href="/public/pages/register.php">Регистрация</a>
    <?php endif; ?>
</nav>