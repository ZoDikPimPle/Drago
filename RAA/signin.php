<?php
session_start();
include '../connect.php'; 

$login = mysqli_real_escape_string($dp, $_POST['login']);
$password = mysqli_real_escape_string($dp, $_POST['password']);

$password = md5($password);

$check_user = mysqli_query($dp, "SELECT * FROM `клиенты` WHERE `Логин` = '$login' AND `Пароль` = '$password'");
if (mysqli_num_rows($check_user) > 0) {

    $user = mysqli_fetch_assoc($check_user);

    $_SESSION['user'] = [
        "id" => $user['Идентификатор'],
        "first_name" => $user['Имя'],
        "last_name" => $user['Фамилия'],
        "login" => $user['Логин'],
        "email" => $user['Электронная_почта'],
        "phone" => $user['Телефон']
    ];

    header('Location: ../pages/auth.php');
    exit();

} else {
    $_SESSION['message'] = 'Неверный логин или пароль';
    header('Location: ../pages/auth.php');
    exit();
}
?>
