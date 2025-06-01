<?php 
session_start();
include '../connect.php'; 

if (isset($_SESSION['user'])) {
    $flag = 1;
    $user_id = $_SESSION['user']['id']; // Получаем идентификатор клиента
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Профиль пользователя</title>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/color-text.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/shadow.css">
    <!-- CSS end -->
    <script>
    $(document).ready(function() {
        $('form.delete-booking-form').submit(function(e) {
            e.preventDefault(); // Предотвращаем отправку формы по умолчанию
            var form = $(this);
            if (confirm("Вы уверены, что хотите удалить это бронирование?")) {
                $.post(form.attr('action'), form.serialize(), function(response) {
                    if (response.success) {
                        // Успешное удаление - перезагружаем страницу
                        location.reload();
                    } else {
                        alert("Ошибка удаления бронирования.");
                    }
                }, 'json');
            }
        });
    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <!-- Navbar -->
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
                        <div class="navs-item"><a href="#"><button class="btn shadow-sm blue"><?= $_SESSION['user']['first_name'] ?></button></a></div>
                        <div class="navs-item"><a href="../RAA/logout.php"><button class="btn txt-uppercase shadow-sm">Выход</button></a></div>
                    <?php else: ?>
                        <div class="navs-item"><a href="auth.php"><button class="btn txt-uppercase shadow-sm">Войти</button></a></div>
                        <div class="navs-item"><a href="register.php"><button class="btn txt-uppercase shadow-sm">Регистрация</button></a></div>
                    <?php endif; ?> 
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar end -->     

    <!-- Profile -->
    <div class="container">
        <div class="jumbotron-item">
            <div class="features-box">
                <h1 class="features-t">Личный кабинет: Информация</h1>

                <table class="table_lk" align="center">
                    <tr>
                        <td>
                            <p class="text_lk_name">Привет, <?= $_SESSION['user']['first_name'] ?>!</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left">
                            <div class="text_lk">Логин: <?= $_SESSION['user']['login'] ?></div>
                            <div class="text_lk">Почта: <?= $_SESSION['user']['email'] ?></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- Profile end -->

   <!-- Cart -->
   <div class="container">
        <div class="jumbotron-item">
            <div class="features-box">
                <h1 class="features-t">Личный кабинет: Бронирования</h1>

                <table align="center" class="cart-tables">
                    <tr>
                        <th>Кинотеатр</th>
                        <th>Фильм</th>
                        <th>Дата и время</th>
                        <th>Ряд</th>
                        <th>Место</th>
                        <th>Действия</th>
                    </tr>
                    <?php
                    $bookings = mysqli_query($dp, "SELECT * FROM бронирования WHERE Идентификатор_клиента = '$user_id'");
                    while($booking = mysqli_fetch_assoc($bookings)) {
                        $session_id = $booking['Идентификатор_сеанса'];
                        $session_info = mysqli_fetch_assoc(mysqli_query($dp, "SELECT * FROM сеансы WHERE Идентификатор = '$session_id'"));
                        $theater_id = $session_info['Идентификатор_кинотеатра'];
                        $theater_info = mysqli_fetch_assoc(mysqli_query($dp, "SELECT Название FROM кинотеатры WHERE Идентификатор = '$theater_id'"));
                        $movie_id = $session_info['Идентификатор_фильма'];
                        $movie_info = mysqli_fetch_assoc(mysqli_query($dp, "SELECT Название FROM фильмы WHERE Идентификатор = '$movie_id'"));

                        echo '<tr id="booking-row-' . $booking['Идентификатор'] . '">';
                        echo '<td>' . $theater_info['Название'] . '</td>';
                        echo '<td>' . $movie_info['Название'] . '</td>';
                        echo '<td>' . $session_info['Время_начала'] . '</td>';
                        echo '<td>' . $booking['Ряд'] . '</td>';
                        echo '<td>' . $booking['Место'] . '</td>';
                        echo '<td>';
                        // Форма для удаления бронирования
                        echo '<form class="delete-booking-form" action="' . $_SERVER['PHP_SELF'] . '" method="post">';
                        echo '<input type="hidden" name="delete_booking" value="true">';
                        echo '<input type="hidden" name="booking_id" value="' . $booking['Идентификатор'] . '">';
                        echo '<button type="submit">Удалить</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <!-- Cart end -->

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="footer-items">
                <div class="footer-item">
                    <span></span>
                </div>              
            </div>
        </div>
    </div>
    <!-- Footer end -->

    <?php 
    if(isset($_POST["delete_booking"])){
        $booking_id = $_POST["booking_id"];
        $result = mysqli_query($dp, "DELETE FROM бронирования WHERE Идентификатор = '$booking_id'");
        if ($result) {
            // Успешное удаление, отправляем JSON-ответ
        } else {
            // Ошибка при удалении, отправляем JSON-ответ с ошибкой
        }
        exit;
    }
    ?>
</body>
</html>
