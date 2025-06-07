<?php
session_start();
if ($_SESSION['ADMIN']) {
    $flag = 1;
}
$usname = $_SESSION['ADMIN']['login'];
include '../connect.php';

// Обработка выхода должна быть в начале, до любого вывода HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exitA'])) {
    unset($_SESSION["ADMIN"]);
    header("location:../RAA/logout.php");
    exit; // Важно добавить exit после header
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AdminPANEL</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/color-text.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/shadow.css">
</head>
<body>
<div class="navbar">
    <div class="container">
        <div class="navbar-nav">
            <div class="navbar-brand">
                <a href="profileA.php"><img class="navbar-brand-png" src="../img/logo_main.png"></a>
            </div>
            <div class="navs" id="navs">
                <form method="post" class="raa">
                    <div class="navs-item"><input class="btn txt-uppercase shadow-sm" type="submit" name="exitA" value="Выход"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="jumbotron-item">
        <div class="features-box">
            <h1 class="features-t">Информация об администраторе</h1>
            <table class='table_lk' align="center">
                <tr><td>
                        <p class="text_lk_name">Профиль: <?= htmlspecialchars($_SESSION['ADMIN']['full_name'] ?? '') ?></p>
                    </td></tr>
                <tr><td align="left">
                        <div class="text_lk">Логин: <?= htmlspecialchars($_SESSION['ADMIN']['login'] ?? '') ?></div>
                    </td></tr>
            </table>
        </div>
    </div>
</div>

<div class="container">
    <div class="jumbotron-item">
        <div class="features-box">
            <h1 class="features-t">Редактирование данных</h1>
            <div class="navs-item" align="center"><a href="../adminpanel/redC.php"><button class="btn txt-uppercase">Клиенты</button></a></div>
            </br>
            <div class="navs-item" align="center"><a href="../adminpanel/redN.php"><button class="btn txt-uppercase">Фильмы</button></a></div>
            </br>
            <div class="navs-item" align="center"><a href="../adminpanel/redP.php"><button class="btn txt-uppercase">Сеансы</button></a></div>
        </div>
    </div>
</div>
</body>
</html>