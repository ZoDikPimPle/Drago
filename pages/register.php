<?php
// Запускаем сессию и обработчик ошибок
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Упрощенный пользовательский обработчик ошибок
function userErrorHandler($errno, $errmsg, $filename, $linenum) {
    $dt = date("Y-m-d H:i:s");
    $errortype = [
        E_ERROR => 'Фатальная ошибка',
        E_WARNING => 'Предупреждение',
        E_PARSE => 'Ошибка разбора',
        E_NOTICE => 'Уведомление',
        E_CORE_ERROR => 'Фатальная ошибка ядра',
        E_CORE_WARNING => 'Предупреждение ядра',
        E_COMPILE_ERROR => 'Фатальная ошибка компиляции',
        E_COMPILE_WARNING => 'Предупреждение компиляции',
        E_USER_ERROR => 'Пользовательская ошибка',
        E_USER_WARNING => 'Пользовательское предупреждение',
        E_USER_NOTICE => 'Пользовательское уведомление',
        E_STRICT => 'Строгий стандарт',
        E_RECOVERABLE_ERROR => 'Фатальная восстанавливаемая ошибка',
        E_DEPRECATED => 'Устаревшая функция',
        E_USER_DEPRECATED => 'Пользовательское устаревание'
    ];

    $errorType = $errortype[$errno] ?? 'Неизвестная ошибка';
    $error_msg = "[{$dt}] {$errorType}: {$errmsg} в файле {$filename} на строке {$linenum}\n";

    // Убедимся, что директория существует и доступна для записи
    $logFile = __DIR__ . "/error.log";
    if (is_writable(dirname($logFile)) || (!file_exists($logFile) && is_writable(__DIR__))) {
        error_log($error_msg, 3, $logFile);
    }

    // Для фатальных ошибок - прерываем выполнение
    $fatalErrors = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR];
    if (in_array($errno, $fatalErrors, true)) {
        // Безопасный вывод ошибки
        echo '<div style="color: red; padding: 10px; margin: 10px; border: 1px solid red;">';
        echo 'Произошла критическая ошибка. Пожалуйста, попробуйте позже.';
        echo '</div>';
        exit(1);
    }
}
set_error_handler("userErrorHandler");

// Проверка авторизации
if (!empty($_SESSION['user'])) {
    header('Location: profile.php');
    exit();
}

// Подключение к базе данных с обработкой ошибок
$dp = null;
try {
    require_once __DIR__ . '/../connect.php';

    // Проверяем соединение
    if (!$dp || mysqli_connect_errno()) {
        throw new Exception("Ошибка подключения к базе данных: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['message'] = 'Ошибка подключения к базе данных. Пожалуйста, попробуйте позже.';
    // Не прерываем выполнение, чтобы показать форму
}

// Генерация капчи
if (empty($_SESSION['captcha'])) {
    generateCaptcha();
}

function generateCaptcha() {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $_SESSION['captcha'] = $num1 + $num2;
    $_SESSION['captcha_question'] = "$num1 + $num2";
}

// Функции для валидации данных
function sanitizeInput($data) {
    if (!is_string($data)) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function validateName($name) {
    return is_string($name) && preg_match('/^[A-Za-zА-Яа-яЁё\s]{2,50}$/u', $name);
}

function validateEmail($email) {
    return is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return is_string($phone) && preg_match('/^[\+]{0,1}[0-9\s\-\(\)]{10,15}$/', $phone);
}

function validateLogin($login) {
    return is_string($login) && preg_match('/^[A-Za-z0-9]{3,10}$/', $login);
}

function validatePassword($password) {
    return is_string($password) && strlen($password) >= 4 && strlen($password) <= 16;
}

function validateDate($date) {
    return is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
}

// Обработка ошибок при выводе данных из сессии
function getSessionData($key) {
    if (!isset($_SESSION['form_data'][$key])) {
        return '';
    }

    $value = $_SESSION['form_data'][$key];
    return is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : '';
}

// Безопасное получение вопроса капчи
function getCaptchaQuestion() {
    return isset($_SESSION['captcha_question']) ? htmlspecialchars($_SESSION['captcha_question']) : '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Регистрация</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/color-text.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/shadow.css">
    <style>
        nav {
            display: block;
            border-radius: 10px;
            padding-top: 10px;
            padding-bottom: 10px;
            background: #E0FFFF;
            margin-left: -10px;
            margin-right: -10px;
            text-align: center;
            opacity: 90%;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }
        .a_nav {
            margin: 7px;
            color: rgb(8, 54, 28);
            text-decoration: none;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .msg {
            color: green;
            font-size: 0.9em;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<!-- Навигация -->
<div class="navbar">
    <div class="container">
        <div class="navbar-nav">
            <div class="navbar-brand">
                <a href="../index.php"><img class="navbar-brand-png" src="../img/logo_main.png" alt="Логотип"></a>
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


<!-- Форма регистрации -->
<div class="raa">
    <div class="container">
        <div class="features-box">
            <h1 class="features-t">Регистрация</h1>
            <form action="../RAA/signup.php" method="post" enctype="multipart/form-data" class="raa" id="registrationForm" novalidate>
                <!-- Имя -->
                <label for="first_name">Имя</label>
                <input class="raa" type="text" name="first_name" id="first_name" placeholder="Ваше имя" required
                       value="<?= getSessionData('first_name') ?>">
                <div class="error" id="first_name_error"></div>

                <!-- Фамилия -->
                <label for="last_name">Фамилия</label>
                <input class="raa" type="text" name="last_name" id="last_name" placeholder="Ваша фамилия" required
                       value="<?= getSessionData('last_name') ?>">
                <div class="error" id="last_name_error"></div>

                <!-- Email -->
                <label for="email">Email</label>
                <input class="raa" type="email" name="email" id="email" required placeholder="Ваш Email"
                       value="<?= getSessionData('email') ?>">
                <div class="error" id="email_error"></div>

                <!-- Телефон -->
                <label for="phone">Телефон</label>
                <input class="raa" type="tel" name="phone" id="phone" placeholder="Ваш телефон"
                       value="<?= getSessionData('phone') ?>">
                <div class="error" id="phone_error"></div>

                <!-- Логин -->
                <label for="login">Логин</label>
                <input class="raa" type="text" name="login" id="login" required placeholder="Логин (от 3 до 10 символов)"
                       value="<?= getSessionData('login') ?>">
                <div class="error" id="login_error"></div>

                <!-- Пароль -->
                <label for="password">Пароль</label>
                <input class="raa" type="password" name="password" id="password" required placeholder="Пароль (от 4 до 16 символов)">
                <div class="error" id="password_error"></div>

                <!-- Подтверждение пароля -->
                <label for="password_confirm">Подтверждение пароля</label>
                <input class="raa" type="password" name="password_confirm" id="password_confirm" required placeholder="Подтвердите пароль">
                <div class="error" id="password_confirm_error"></div>

                <!-- Дата рождения -->
                <label for="birthdate">Дата рождения</label>
                <input class="raa" type="date" name="birthdate" id="birthdate" required
                       value="<?= getSessionData('birthdate') ?>">
                <div class="error" id="birthdate_error"></div>

                <!-- Капча -->
                <label for="captcha">Решите пример: <?= getCaptchaQuestion() ?> = ?</label>
                <input class="raa" type="text" name="captcha" id="captcha" required placeholder="Введите ответ">
                <div class="error" id="captcha_error"></div>

                <button class="raa" type="submit" name="REGISTR">Зарегистрироваться</button>

                <p>У вас уже есть аккаунт? - <a href="../pages/auth.php" class="raa">авторизируйтесь</a>!</p>
                <?php
                if (!empty($_SESSION['message'])) {
                    echo '<p class="msg">' . htmlspecialchars($_SESSION['message']) . '</p>';
                    unset($_SESSION['message']);
                }
                if (!empty($_SESSION['form_data'])) {
                    unset($_SESSION['form_data']);
                }
                ?>
            </form>
        </div>
    </div>
</div>

<div class="footer">
    <div class="container">
        <div class="footer-items">
            <div class="footer-item">
                <span></span>
            </div>
        </div>
    </div>
</div>

<script>
    // Клиентская валидация формы
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let valid = true;
        const errors = {};

        // Валидация имени
        const firstName = document.getElementById('first_name').value.trim();
        if (!/^[A-Za-zА-Яа-яЁё\s]{2,50}$/u.test(firstName)) {
            errors.first_name = 'Только буквы (2-50 символов)';
            valid = false;
        }

        // Валидация фамилии
        const lastName = document.getElementById('last_name').value.trim();
        if (!/^[A-Za-zА-Яа-яЁё\s]{2,50}$/u.test(lastName)) {
            errors.last_name = 'Только буквы (2-50 символов)';
            valid = false;
        }

        // Валидация email
        const email = document.getElementById('email').value.trim();
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = 'Некорректный email';
            valid = false;
        }

        // Валидация телефона (необязательное поле)
        const phone = document.getElementById('phone').value.trim();
        if (phone && !/^[\+]{0,1}[0-9\s\-\(\)]{10,15}$/.test(phone)) {
            errors.phone = 'Некорректный формат телефона';
            valid = false;
        }

        // Валидация логина
        const login = document.getElementById('login').value.trim();
        if (!/^[A-Za-z0-9]{3,10}$/.test(login)) {
            errors.login = 'Латиница и цифры (3-10 символов)';
            valid = false;
        }

        // Валидация пароля
        const password = document.getElementById('password').value;
        if (password.length < 4 || password.length > 16) {
            errors.password = 'Длина пароля должна быть 4-16 символов';
            valid = false;
        }

        // Подтверждение пароля
        const passwordConfirm = document.getElementById('password_confirm').value;
        if (password !== passwordConfirm) {
            errors.password_confirm = 'Пароли не совпадают';
            valid = false;
        }

        // Валидация даты рождения
        const birthdate = document.getElementById('birthdate').value;
        if (!birthdate) {
            errors.birthdate = 'Укажите дату рождения';
            valid = false;
        }

        // Валидация капчи
        const captcha = document.getElementById('captcha').value.trim();
        if (!captcha) {
            errors.captcha = 'Введите ответ';
            valid = false;
        }

        // Показываем ошибки
        for (const field in errors) {
            const errorElement = document.getElementById(field + '_error');
            if (errorElement) {
                errorElement.textContent = errors[field] || '';
            }
        }

        // Если форма валидна, отправляем ее
        if (valid) {
            this.submit();
        }
    });
</script>
</body>
</html>