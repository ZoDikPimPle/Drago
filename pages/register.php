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
    <title>Регистрация</title>

    <!-- CSS -->
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/color-text.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/shadow.css">
    <!-- CSS end -->

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
    <!-- Registration form -->
    <div class="raa">
        <div class="container">
            <div class="features-box">
                <h1 class="features-t">Регистрация</h1>
                <form action="../RAA/signup.php" method="post" enctype="multipart/form-data" class="raa">
                    <label>Имя</label>
                    <input class="raa" type="text" name="first_name" placeholder="Ваше имя">

                    <label>Фамилия</label>
                    <input class="raa" type="text" name="last_name" placeholder="Ваша фамилия">

                    <label>Email</label>
                    <input class="raa" type="email" name="email" required placeholder="Ваш Email">

                    <label>Телефон</label>
                    <input class="raa" type="text" name="phone" placeholder="Ваш телефон">

                    <label>Логин</label>
                    <input class="raa" type="text" name="login" required placeholder="Логин (от 3 до 10 символов)">

                    <label>Пароль</label>
                    <input class="raa" type="password" name="password" required placeholder="Пароль (от 4 до 16 символов)">

                    <label>Подтверждение пароля</label>
                    <input class="raa" type="password" name="password_confirm" required placeholder="Подтвердите пароль">

                    <button class="raa" type="submit" name="REGISTR">Зарегистрироваться</button>

                    <p>У вас уже есть аккаунт? - <a href="../pages/auth.php" class="raa">авторизируйтесь</a>!</p>
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
    <!-- Registration form end -->
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
