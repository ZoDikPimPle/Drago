<?php
session_start();
include '../connect.php'; 

$first_name = mysqli_real_escape_string($dp, $_POST['first_name']);
$last_name = mysqli_real_escape_string($dp, $_POST['last_name']);
$email = mysqli_real_escape_string($dp, $_POST['email']);
$phone = mysqli_real_escape_string($dp, $_POST['phone']);
$login = mysqli_real_escape_string($dp, $_POST['login']);
$password = mysqli_real_escape_string($dp, $_POST['password']);
$password_confirm = mysqli_real_escape_string($dp, $_POST['password_confirm']);

$check_user = mysqli_fetch_array(mysqli_query($dp, "SELECT * FROM `клиенты` WHERE `Логин` = '$login'"));

if (empty($check_user)) {
    if (strlen($login) >= 3 && strlen($login) <= 10) {
        if (strlen($password) >= 4 && strlen($password) <= 16) {
            if ($password === $password_confirm) {
                $password = md5($password);

                mysqli_query($dp, "INSERT INTO `клиенты` (`Идентификатор`, `Имя`, `Фамилия`, `Электронная_почта`, `Телефон`, `Логин`, `Пароль`) VALUES (NULL, '$first_name', '$last_name', '$email', '$phone', '$login', '$password')");
                header('Location: ../pages/auth.php');
                exit();

            } else {
                $_SESSION['message'] = 'Пароли не совпадают';
                header('Location: ../pages/register.php');
                exit();
            }
        } else {
            $_SESSION['message'] = 'Длина пароля должна быть от 4 до 16 символов';
            header('Location: ../pages/register.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Длина логина должна быть от 3 до 10 символов';
        header('Location: ../pages/register.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'Аккаунт с данным логином уже существует в системе';
    header('Location: ../pages/register.php');
    exit();
}
?>
