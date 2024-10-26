<?php
include '../../config/db.php';
session_start();

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    die("Пожалуйста, войдите в систему, чтобы скачать игру.");
}

if (isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];
    $user_id = $_SESSION['user_id'];

    // Проверка, действительно ли игра куплена пользователем
    $sql_check = "SELECT * FROM orders WHERE user_id='$user_id' AND game_id='$game_id'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows == 0) {
        die("Вы не купили эту игру.");
    }

    // Логика для скачивания файла игры
    $file_path = "../../games/$game_id.zip";
    if (file_exists($file_path)) {
        // Проверка токена доступа
        $token = md5($user_id . $game_id . 'секретный_ключ');
        if ($_GET['token'] !== $token) {
            die("Неверный токен доступа.");
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        die("Файл не найден.");
    }
}
?>