<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit();
}

include '../connect.php';

// Генерация простой математической капчи
if (!isset($_SESSION['captcha']))
{
    generateCaptcha();
}


function generateCaptcha() {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $_SESSION['captcha'] = $num1 + $num2;
    $_SESSION['captcha_question'] = "$num1 + $num2";
}
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
                <input class="raa" type="text" name="first_name" placeholder="Ваше имя"
                       value="<?= isset($_SESSION['form_data']['first_name']) ? htmlspecialchars($_SESSION['form_data']['first_name']) : '' ?>"
                       pattern="[A-Za-zА-Яа-яЁё\s]{2,50}" title="Только буквы (2-50 символов)" required>

                <label>Фамилия</label>
                <input class="raa" type="text" name="last_name" placeholder="Ваша фамилия"
                       value="<?= isset($_SESSION['form_data']['last_name']) ? htmlspecialchars($_SESSION['form_data']['last_name']) : '' ?>"
                       pattern="[A-Za-zА-Яа-яЁё\s]{2,50}" title="Только буквы (2-50 символов)" required>

                <label>Email</label>
                <input class="raa" type="email" name="email" required placeholder="Ваш Email"
                       value="<?= isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : '' ?>">

                <label>Телефон</label>
                <input class="raa" type="tel" name="phone" placeholder="Ваш телефон"
                       value="<?= isset($_SESSION['form_data']['phone']) ? htmlspecialchars($_SESSION['form_data']['phone']) : '' ?>"
                       pattern="[\+]{0,1}[0-9\s\-\(\)]{10,15}" title="Формат: +7 (XXX) XXX-XX-XX">

                <label>Логин</label>
                <input class="raa" type="text" name="login" required placeholder="Логин (от 3 до 10 символов)"
                       value="<?= isset($_SESSION['form_data']['login']) ? htmlspecialchars($_SESSION['form_data']['login']) : '' ?>"
                       pattern="[A-Za-z0-9]{3,10}" title="Латиница и цифры (3-10 символов)">

                <label>Пароль</label>
                <input class="raa" type="password" name="password" required placeholder="Пароль (от 4 до 16 символов)"
                       pattern=".{4,16}" title="4-16 символов">

                <label>Подтверждение пароля</label>
                <input class="raa" type="password" name="password_confirm" required placeholder="Подтвердите пароль">

                <!-- Простая математическая капча -->
                <label>Решите пример: <?= isset($_SESSION['captcha_question']) ? $_SESSION['captcha_question'] : generateCaptcha() ?> = ?</label>
                <input class="raa" type="text" name="captcha" required placeholder="Введите ответ">

                <button class="raa" type="submit" name="REGISTR">Зарегистрироваться</button>

                <p>У вас уже есть аккаунт? - <a href="../pages/auth.php" class="raa">авторизируйтесь</a>!</p>
                <?php
                if (isset($_SESSION['message'])) {
                    echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
                    unset($_SESSION['message']);
                }
                if (isset($_SESSION['form_data'])) {
                    unset($_SESSION['form_data']);
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