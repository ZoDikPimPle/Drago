<?php 
session_start();
include '../connect.php'; 

if ($_SESSION['user']) {
    $flag = 1;
}
// Устанавливаем значения сортировки по умолчанию
$sort_by = "Название";
$order = "ASC";

// Обработка запроса сортировки
if(isset($_GET['sort_by']) && isset($_GET['order'])) {
    $sort_by = $_GET['sort_by'];
    $order = $_GET['order'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Выбор кинотеатров и фильмов</title>

    <!-- CSS -->
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/color-text.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/shadow.css">
    <!-- CSS end -->
</head>
<body>
    <div class="navbar">
        <div class="container">
            <div class="navbar-nav">
                <div class="navbar-brand">
                    <a href="../index.php"><img class="navbar-brand-png" src="../img/logo_main.png"></a>
                </div>
                <div class="navs" id="navs">
                    <div class="navs-item notbtn"><a href="galery.php" class="txt-uppercase">Постеры</a></div>
                    <div class="navs-item notbtn"><a href="" class="txt-uppercase">Фильмы</a></div>
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
    <div class="container">
        <div class="jumbotron-item">
            <div class="features-box">
                <h1 class="features-t">Выбор кинотеатра</h1>
                    <!-- Форма для выбора типа сортировки -->
                    <div style="margin: 0 auto; text-align: center;">
                        <form method="GET" class="sort-form">
                            <label for="sort_by">Сортировать по:</label>
                            <select name="sort_by" id="sort_by">
                                <option value="Название" <?php if($sort_by == "Название") echo "selected"; ?>>Названию</option>
                                <option value="Адрес" <?php if($sort_by == "Адрес") echo "selected"; ?>>Адресу</option>
                            </select>
                            <label for="order">Порядок:</label>
                            <select name="order" id="order">
                                <option value="ASC" <?php if($order == "ASC") echo "selected"; ?>>По возрастанию</option>
                                <option value="DESC" <?php if($order == "DESC") echo "selected"; ?>>По убыванию</option>
                            </select>
                            <input type="submit" value="Применить">
                        </form>
                    </div>



                <table align="center" class="serv-tables" >
                    <tr>
                        <th>Название</th>
                        <th>Адрес</th>
                        <th>Телефон</th>
                        <th>Сеансы</th>
                    </tr>
                    <?php 
                    // Запрос кинотеатров с сортировкой
                    $theaters = mysqli_query($dp, "SELECT * FROM кинотеатры ORDER BY $sort_by $order"); 
                    while($theater = mysqli_fetch_array($theaters)) {
                        echo '<tr>';
                        echo '<td>' . $theater['Название'] . '</td>';
                        echo '<td>' . $theater['Адрес'] . '</td>';
                        echo '<td>' . $theater['Телефон'] . '</td>';
                        echo '<td><form method="POST"><input type="hidden" name="theater_id" value="' . $theater['Идентификатор'] . '"><input type="submit" name="submit_theater" value="Показать сеансы"></form></td>';
                        echo '</tr>';
                    }
                    ?>
                </table>

                <?php 
                if(isset($_POST["submit_theater"])){
                    $theater_id = $_POST["theater_id"];
                    echo "<div align='center'><h1>Фильмы в кинотеатре</h1></div>";
                    $movies_query = mysqli_query($dp, "SELECT фильмы.*, сеансы.Время_начала FROM фильмы INNER JOIN сеансы ON фильмы.Идентификатор = сеансы.Идентификатор_фильма WHERE сеансы.Идентификатор_кинотеатра = '$theater_id'");
                    echo "<form method='POST'>";
                    echo "<table align='center' class='serv-tables'>";
                    echo "<tr>
                            <th>Фильм</th>
                            <th>Режиссер</th>
                            <th>Год выпуска</th>
                            <th>Описание</th>
                            <th>Время начала</th>";
                            if($flag == 1){
                                echo "<th>Ряд</th>";
                                echo "<th>Место</th>";
                                echo "<th>Выбор</th>";
                            }
                    echo "</tr>";
                    while($movie = mysqli_fetch_array($movies_query)) {
                        echo '<tr>';
                        echo '<td>' . $movie['Название'] . '</td>';
                        echo '<td>' . $movie['Режиссер'] . '</td>';
                        echo '<td>' . $movie['Год_выпуска'] . '</td>';
                        echo '<td>' . $movie['Описание'] . '</td>';
                        echo '<td>' . $movie['Время_начала'] . '</td>';
                        if($flag == 1) {
                            echo '<td><input type="number" name="row_' . $movie['Идентификатор'] . '" placeholder="Ряд" min="1" max="20"></td>';
                            echo '<td><input type="number" name="seat_' . $movie['Идентификатор'] . '" placeholder="Место" min="1" max="20"></td>';
                            echo '<td><input value="' . $movie['Идентификатор'] . '" name="ArrCart[]" type="checkbox"></td>';
                        } else 
                        echo '</tr>';
                    }
                    echo "</table>";
                    if($flag == 1){
                        echo "<div align='center'><input type='submit' name='addcart' value='Забронировать'></div>";
                    }
                    echo "</form>";
                }
                ?>

                <?php 
                if(isset($_POST["addcart"])){
                    if(isset($_POST["ArrCart"])){
                        $user_id = $_SESSION['user']['id'];
                        $dates = 1;
                        foreach($_POST["ArrCart"] as $prod){
                            // Получаем идентификатор сеанса по идентификатору фильма
                            $film_id = $prod;
                            $session_query = mysqli_query($dp, "SELECT Идентификатор FROM сеансы WHERE Идентификатор_фильма = '$film_id'");
                            $session_row = mysqli_fetch_assoc($session_query);
                            $session_id = $session_row['Идентификатор'];
                            
                            // Получаем введенные пользователем данные о ряде и месте
                            $row = $_POST["row_$film_id"];
                            $seat = $_POST["seat_$film_id"];
                            
                            // Вставляем запись в таблицу бронирования с использованием идентификатора сеанса и введенных данных о ряде и месте
                            mysqli_query($dp, "INSERT INTO `бронирования` (`Идентификатор`, `Идентификатор_клиента`, `Идентификатор_сеанса`, `Количество_мест`, `Ряд`, `Место`) VALUES (NULL, '$user_id', '$session_id', '$dates', '$row', '$seat')");
                        }
                        echo "Фильмы добавлены в бронь!";
                    } else {
                        echo "Выберите фильмы для бронирования.";
                    }
                }
                ?>
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
</body>
</html>
