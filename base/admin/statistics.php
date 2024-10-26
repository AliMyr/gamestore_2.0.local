<?php
include_once "../includes/db.php";
include_once "../includes/header.php";
include_once "../includes/admin_navbar.php";

// Получение количества зарегистрированных пользователей
$user_count_query = "SELECT COUNT(*) AS user_count FROM users";
$user_count_result = $conn->query($user_count_query);
$user_count = $user_count_result->fetch_assoc()['user_count'];

// Получение количества игр в каталоге
$game_count_query = "SELECT COUNT(*) AS game_count FROM games";
$game_count_result = $conn->query($game_count_query);
$game_count = $game_count_result->fetch_assoc()['game_count'];

// Получение даты последней регистрации
$last_registration_query = "SELECT MAX(registration_date) AS last_registration FROM users";
$last_registration_result = $conn->query($last_registration_query);
$last_registration = $last_registration_result->fetch_assoc()['last_registration'];
?>

<h2>Статистика</h2>

<div class="statistics">
    <p><strong>Количество зарегистрированных пользователей:</strong> <?php echo $user_count; ?></p>
    <p><strong>Количество игр в каталоге:</strong> <?php echo $game_count; ?></p>
    <p><strong>Дата последней регистрации:</strong> <?php echo $last_registration ? $last_registration : "Нет зарегистрированных пользователей"; ?></p>
</div>

<?php
include_once "../includes/footer.php";
$conn->close();
?>
