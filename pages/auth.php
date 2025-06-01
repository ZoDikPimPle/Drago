<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit();
}

include '../connect.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Постеры</title>

    <!-- CSS -->
    <!-- Лого сайта -->
    <link rel="icon" type="image/png" href="../img/logo.png">

    <!-- Сзадний фон -->
    <link rel="stylesheet" type="text/css" href="../css/color-text.css">
    <!--Основа сайта-->
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <!--Шрифт-->
    <link rel="stylesheet" type="text/css" href="../css/shadow.css">

    <!-- css end -->

</head>
<body>
    <!-- navbar -->
    <div class="navbar">
        <div class="container">
            <div class="navbar-nav">
                <div class="navbar-brand">
                    <a href="../index.php"><img class="navbar-brand-png" src="../img/logo_main.png"></a>
                </div>
                <div class="navs" id="navs">
                    <div class="navs-item notbtn"><a href="galery.php" class="txt-uppercase">Постеры</a></div>
                    <div class="navs-item notbtn"><a href="cinema.php" class="txt-uppercase">Фильмы</a></div>
                    <div class="navs-item"><a href="../pages/auth.php"><button class="btn txt-uppercase shadow-sm">Войти</button></a></div>
                    <div class="navs-item"><a href="../pages/register.php"><button class="btn txt-uppercase shadow-sm">Регистрация</button></a></div>
                </div>
            </div>
        </div>
    </div>
    <!-- navbar end -->     

    <!-- jumbotron -->
    <div class="raa">
        <div class="container">
            <div class="features-box">
                <h1 class="features-t" id="Блог">Авторизация</h1>
                <!-- Форма авторизации -->
                <form action="../RAA/signin.php" method="post" class="raa">
                    <label>Логин</label>
                    <input class="raa" type="text" name="login" placeholder="Введите свой логин">
                    <label>Пароль</label>
                    <input class="raa" type="password" name="password" placeholder="Введите пароль">
                    <button class="raa" type="submit">Войти</button>
                    <p>
                        У вас нет аккаунта? - <a href="../pages/register.php" class="raa">зарегистрируйтесь</a><a href="../adminpanel/" class="raa">!</a>
                    </p>
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
                        unset($_SESSION['message']);
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
    <!-- НИЗ САЙТА -->
    <div class="footer">
        <div class="container">
            <div class="footer-items">
                <div class="footer-item">
                    <span></span>
                </div>              
            </div>
        </div>
    </div>
</body>
</html>
