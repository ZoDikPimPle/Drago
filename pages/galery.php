<?php
session_start();
include '../connect.php'; // Подключаемся к базе данных

// Проверяем, авторизован ли пользователь
if ($_SESSION['user']) {
    $flag = 1;
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Постеры</title>

    <!-- CSS -->
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/color-text.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/shadow.css">
    <style>
        .film-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .film-item {
            width: 240px;
        }
        .film-item img {
            width: 100%;
            height: auto;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 800px;
        }
        .modal-content img {
            width: 100%;
            height: auto;
        }
        .close {
            color: #ccc;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- Навигационное меню -->
    <div class="navbar">
        <div class="container">
            <div class="navbar-nav">
                <div class="navbar-brand">
                    <a href="../index.php"><img class="navbar-brand-png" src="../img/logo_main.png"></a>
                </div>
                <div class="navs" id="navs">
                    <div class="navs-item notbtn"><a href="galery.php" class="txt-uppercase">Постеры</a></div>
                    <div class="navs-item notbtn"><a href="cinema.php" class="txt-uppercase">Фильмы</a></div>
                    <?php if($flag == 1): ?>
                        <div class="navs-item"><a href="profile.php"><button class="btn  shadow-sm blue"><?= $_SESSION['user']['first_name'] ?></button></a></div>
                        <div class="navs-item"><a href="../RAA/logout.php"><button class="btn txt-uppercase shadow-sm">Выход</button></a></div>
                    <?php else: ?>
                        <div class="navs-item"><a href="auth.php"><button class="btn txt-uppercase shadow-sm">Войти</button></a></div>
                        <div class="navs-item"><a href="register.php"><button class="btn txt-uppercase shadow-sm">Регистрация</button></a></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Навигационное меню end -->

    <!-- Главный контент -->
    <div class="container">
        <div class="jumbotron-item">
            <div class="features-box">
                <h1 class="features-t">Постеры фильмов</h1>

                <div class="film-container">
                    <?php 
                    // Выбираем все фильмы из базы данных
                    $films_query = mysqli_query($dp, "SELECT * FROM фильмы"); 
                    while($film = mysqli_fetch_array($films_query)):
                    ?>
                        <div class="film-item">
                            <img src="<?= $film['Постер'] ?>" alt="<?= $film['title'] ?>" width="240" height="360" onclick="openModal('<?= $film['Постер'] ?>')">
                            <p><?= $film['title'] ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Модальное окно для увеличенного изображения -->
                <div id="modal" class="modal" onclick="closeModal()">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <div class="modal-content">
                        <img id="modal-image" src="" alt="Увеличенное изображение">
                    </div>
                </div>
                <!-- Модальное окно end -->

            </div>
        </div>
    </div>
    <!-- Главный контент end -->

    <!-- Нижний колонтитул -->
    <div class="footer">
        <div class="container">
            <div class="footer-items">
                <div class="footer-item">
                    <span></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Нижний колонтитул end -->

    <!-- JavaScript для модального окна -->
    <script>
        function openModal(imageUrl) {
            document.getElementById("modal-image").src = imageUrl;
            document.getElementById("modal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }
    </script>
</body>

</html>
