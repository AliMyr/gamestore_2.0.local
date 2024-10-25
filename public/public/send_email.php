<?php
// Подключаем Composer autoload для загрузки PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Убедись, что путь к autoload верный
require '../../vendor/autoload.php';  // Подключаем autoload.php

function sendVerificationEmail($email, $verification_code) {
    $mail = new PHPMailer(true);

    try {
        // Настройки сервера
        $mail->isSMTP();                                         // Используем SMTP
        $mail->Host       = 'smtp.gmail.com';                    // SMTP сервер
        $mail->SMTPAuth   = true;                                // Включаем SMTP авторизацию
        $mail->Username   = 'your_email@gmail.com';              // SMTP логин (твой email)
        $mail->Password   = 'your_password';                     // SMTP пароль (твой пароль)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Включаем шифрование TLS
        $mail->Port       = 587;                                 // SMTP порт

        // Настройки отправителя и получателя
        $mail->setFrom('your_email@gmail.com', 'Game Store');    // От кого письмо
        $mail->addAddress($email);                              // Кому отправляем

        // Содержимое письма
        $mail->isHTML(true);                                    // Устанавливаем формат письма в HTML
        $mail->Subject = 'Код подтверждения';
        $mail->Body    = "Ваш код подтверждения: $verification_code";

        $mail->send();
        echo 'Код подтверждения отправлен!';
    } catch (Exception $e) {
        echo "Ошибка отправки: {$mail->ErrorInfo}";
    }
}
?>
