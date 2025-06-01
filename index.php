<?php 
session_start();
include 'connect.php'; 

if (isset($_SESSION['user'])) {
    $flag = 1;
} else {
    $flag = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Киноафиша</title>

    <!-- CSS -->
    <!-- Лого сайта -->
    <link rel="icon" type="image/png" href="./img/logo.png">
    
    <!-- Сзадний фон -->
    <link rel="stylesheet" type="text/css" href="./css/color-text.css">
    <!--Основа сайта-->
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <!--Шрифт-->
    <link rel="stylesheet" type="text/css" href="./css/shadow.css">
    <!-- css end -->

</head>
<body>
    <!-- navbar -->
    <div class="navbar">
        <div class="container">
            <div class="navbar-nav">
                <div class="navbar-brand">
                    <a href="#"><img class="navbar-brand-png" src="./img/logo_main.png"></a>
                </div>
                <div class="navs" id="navs">
                    <div class="navs-item notbtn"><a href="pages/galery.php" class="txt-uppercase">Постеры</a></div>
                    <div class="navs-item notbtn"><a href="pages/cinema.php" class="txt-uppercase">Фильмы</a></div>
                    <?php if($flag == 1): ?>
                        <div class="navs-item"><a href="pages/profile.php"><button class="btn  shadow-sm blue"><?= $_SESSION['user']['first_name'] ?></button></a></div>
                        <div class="navs-item"><a href="RAA/logout.php"><button class="btn txt-uppercase shadow-sm">Выход</button></a></div>
                    <?php else: ?>
                        <div class="navs-item"><a href="pages/auth.php"><button class="btn txt-uppercase shadow-sm">Войти</button></a></div>
                        <div class="navs-item"><a href="pages/register.php"><button class="btn txt-uppercase shadow-sm">Регистрация</button></a></div>
                    <?php endif; ?> 
                </div>
            </div>
        </div>
    </div>
    <!-- navbar end -->        

    <!-- jumbotron -->
    <div class="jumbotron">
        <div class="container">
            <div class="jumbotron-items">
                <div class="jumbotron-item">
                    <span class="jumbotron-t">Киноафиша</span>
                    <p class="jumbotron-d">На нашем сайте вы можете найти информацию о фильмах и сеансах в кинотеатрах!</p>
                </div>
                <div class="jumbotron-item">
                    <img src="./img/img1.png" alt="image">
                </div>
            </div>
        </div>
    </div>
    <!-- jumbotron end -->

    <!-- Блог -->
    <div class="features-box">
        <h1 class="features-t" id="Блог">А что посмотреть?</h1>
        <div class="container">
            <div class="features">
                <?php 
                $result = mysqli_query($dp, "SELECT * FROM фильмы ORDER BY RAND() LIMIT 1");
                while ($row = mysqli_fetch_array($result)) {
                    echo "<div class='feature'>";
                    echo "<h2 class='feature-info-t'>" . $row['Название'] . "</h2>";
                    echo "<p class='feature-info-d'>" . $row['Описание'] . "</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Блог end -->

    <!-- НИЗ САЙТА -->
    <div class="footer">
        <div class="container">
            <div class="footer-items">
                <div class="footer-item">
                    <span>&copy; <?php echo date("Y"); ?> Киноафиша</span>
                </div>              
            </div>
        </div>
    </div>
    <!-- НИЗ САЙТА end -->

</body>
</html>

