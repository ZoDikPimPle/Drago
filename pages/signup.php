<?php
session_start();

include '../connect.php';

// Функция генерации капчи
function generateCaptcha() {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $_SESSION['captcha'] = $num1 + $num2;
    $_SESSION['captcha_question'] = "$num1 + $num2";
}

// Сохраняем данные формы для повторного заполнения
$_SESSION['form_data'] = $_POST;

// Проверка математической капчи
if (empty($_POST['captcha']) || (int)$_POST['captcha'] !== $_SESSION['captcha']) {
    generateCaptcha(); // Генерируем новую капчу при ошибке
    $_SESSION['message'] = 'Неверно решен пример! Попробуйте еще раз.';
    header('Location: ../pages/register.php');
    exit();
}

// Проверка данных
$errors = [];

$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$login = trim($_POST['login']);
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

// Валидация имени и фамилии
if (!preg_match('/^[A-Za-zА-Яа-яЁё\s]{2,50}$/u', $first_name)) {
    $errors[] = 'Имя должно содержать только буквы (2-50 символов)';
}

if (!preg_match('/^[A-Za-zА-Яа-яЁё\s]{2,50}$/u', $last_name)) {
    $errors[] = 'Фамилия должна содержать только буквы (2-50 символов)';
}

// Валидация email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Некорректный email';
} elseif (strlen($email) > 100) {
    $errors[] = 'Email слишком длинный (максимум 100 символов)';
}

// Валидация телефона (необязательное поле)
if (!empty($phone) && !preg_match('/^[\+]{0,1}[0-9\s\-\(\)]{10,15}$/', $phone)) {
    $errors[] = 'Некорректный формат телефона';
}

// Валидация логина
if (!preg_match('/^[A-Za-z0-9]{3,10}$/', $login)) {
    $errors[] = 'Логин должен содержать только латинские буквы и цифры (3-10 символов)';
}

// Валидация пароля
if (strlen($password) < 4 || strlen($password) > 16) {
    $errors[] = 'Пароль должен быть от 4 до 16 символов';
} elseif ($password !== $password_confirm) {
    $errors[] = 'Пароли не совпадают';
}

// Проверка на существующий логин или email
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = ? OR email = ?");
$stmt->execute([$login, $email]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    $errors[] = 'Пользователь с таким логином или email уже существует';
}

// Если есть ошибки - выводим их
if (!empty($errors)) {
    $_SESSION['message'] = implode('<br>', $errors);
    header('Location: ../pages/register.php');
    exit();
}

// Хеширование пароля
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Защита от SQL-инъекций через подготовленные выражения
try {
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, login, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $email, $phone, $login, $password_hash]);

    $_SESSION['message'] = 'Регистрация прошла успешно!';
    unset($_SESSION['form_data']);
    unset($_SESSION['captcha']);
    unset($_SESSION['captcha_question']);
    header('Location: ../pages/auth.php');
} catch (PDOException $e) {
    // Логирование ошибки
    error_log("Registration error: " . $e->getMessage());

    // Фатальные ошибки
    $_SESSION['message'] = 'Произошла ошибка при регистрации. Пожалуйста, попробуйте позже.';
    header('Location: ../pages/register.php');
}
?>