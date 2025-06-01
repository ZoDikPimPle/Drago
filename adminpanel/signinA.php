<?php

    session_start();
    include '../connect.php';

    $login = $_POST['login'];
    $password = $_POST['password'];

    $check_admins = mysqli_query($dp, "SELECT * FROM `Администраторы` WHERE `Логин` = '$login' AND `Пароль` = '$password'");
    if (mysqli_num_rows($check_admins) > 0) {

        $admin = mysqli_fetch_assoc($check_admins);

        $_SESSION['ADMIN'] = [
            "id" => $admin['Идентификатор'],
            "full_name" => $admin['Имя'] . ' ' . $admin['Фамилия'],
             "login" => $admin['Логин']
        ];

        header('Location: ../adminpanel/profileA.php');

    } else {
        $_SESSION['message'] = 'Не верный логин или пароль';
        header("location:index.php");
    }
    ?>

<pre>
    <?php
    print_r($check_user);
    print_r($user);
    ?>
</pre>
