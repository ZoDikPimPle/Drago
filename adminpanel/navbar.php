<?php
// Проверяем, есть ли сессия администратора
$isAdmin = isset($_SESSION['ADMIN']);
$username = $isAdmin ? htmlspecialchars($_SESSION['ADMIN']['login']) : '';
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
    <link rel="stylesheet" type="text/css" href="../css/navbar.css">
</head>
<body>
<div class="navbar">
    <div class="container">
        <div class="navbar-nav">
            <div class="navbar-brand">
                <a href="profileA.php"><img class="navbar-brand-png" src="../img/logo_main.png"></a>
            </div>
            <div class="navs" id="navs">
                <?php if ($isAdmin): ?>
                    <form method="post" class="raa">
                        <div class="navs-item">
                            <span class="welcome-text">Добро пожаловать, <?= $username ?></span>
                            <input class="btn txt-uppercase shadow-sm" type="submit" name="exitA" value="Выход">
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>