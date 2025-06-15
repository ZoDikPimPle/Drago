<?php
$server = "localhost";
$user = "root";
$password = "";
$DB = "kino";

// Подключение с обработкой ошибок
$dp = mysqli_connect($server, $user, $password, $DB);

if (!$dp) {
    error_log("Ошибка подключения к MySQL: " . mysqli_connect_error());
    die("Ошибка подключения к базе данных. Пожалуйста, попробуйте позже.");
}

// Установка кодировки
if (!mysqli_set_charset($dp, "utf8")) {
    error_log("Ошибка установки кодировки UTF-8: " . mysqli_error($dp));
}
?>