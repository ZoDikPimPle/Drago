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
if (isset($_GET['sort_by']) && isset($_GET['order'])) {
    $sort_by = $_GET['sort_by'];
    $order = $_GET['order'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Выбор кинотеатров и фильмов</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/color-text.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/shadow.css">
    <style>
        .seat-map {
            display: grid;
            grid-template-columns: repeat(8, 30px);
            gap: 5px;
            margin: 10px 0;
        }
        .seat {
            width: 30px;
            height: 30px;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 3px;
        }
        .seat.selected {
            background-color: #4CAF50;
            color: white;
        }
        .seat.occupied {
            background-color: #f44336;
            cursor: not-allowed;
        }
        .seat-label {
            font-size: 10px;
        }
        .seat-container {
            margin: 20px 0;
        }
    </style>
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
                <?php if ($flag == 1): ?>
                    <div class="navs-item"><a href="profile.php"><button class="btn shadow-sm blue"><?= $_SESSION['user']['first_name'] ?></button></a></div>
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
            <div style="margin: 0 auto; text-align: center;">
                <form method="GET" class="sort-form">
                    <label for="sort_by">Сортировать по:</label>
                    <select name="sort_by" id="sort_by">
                        <option value="Название" <?php if ($sort_by == "Название") echo "selected"; ?>>Названию</option>
                        <option value="Адрес" <?php if ($sort_by == "Адрес") echo "selected"; ?>>Адресу</option>
                    </select>
                    <label for="order">Порядок:</label>
                    <select name="order" id="order">
                        <option value="ASC" <?php if ($order == "ASC") echo "selected"; ?>>По возрастанию</option>
                        <option value="DESC" <?php if ($order == "DESC") echo "selected"; ?>>По убыванию</option>
                    </select>
                    <input type="submit" value="Применить">
                </form>
            </div>
            <table align="center" class="serv-tables">
                <tr>
                    <th>Название</th>
                    <th>Адрес</th>
                    <th>Телефон</th>
                    <th>Сеансы</th>
                </tr>
                <?php
                $theaters = mysqli_query($dp, "SELECT * FROM кинотеатры ORDER BY $sort_by $order");
                while ($theater = mysqli_fetch_array($theaters)) {
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
            if (isset($_POST["submit_theater"])) {
                $theater_id = $_POST["theater_id"];
                echo "<div align='center'><h1>Фильмы в кинотеатре</h1></div>";

                // Проверяем, что $theater_id установлен и выполняем запрос
                if (isset($theater_id)) {
                    $movies_query = mysqli_query($dp, "SELECT фильмы.*, сеансы.Время_начала, сеансы.Цена, сеансы.Идентификатор as session_id FROM фильмы INNER JOIN сеансы ON фильмы.Идентификатор = сеансы.Идентификатор_фильма WHERE сеансы.Идентификатор_кинотеатра = '$theater_id'");

                    // Проверяем, что запрос выполнен успешно
                    if ($movies_query) {
                        echo "<form method='POST' id='booking-form'>";
                        echo "<table align='center' class='serv-tables'>";
                        echo "<tr>
                    <th>Фильм</th>
                    <th>Режиссер</th>
                    <th>Год выпуска</th>
                    <th>Описание</th>
                    <th>Время начала</th>
                    <th>Цена в руб.</th>";
                        if ($flag == 1) {
                            echo "<th>Места</th>";
                            echo "<th>Выбор</th>";
                        }
                        echo "</tr>";

                        while ($movie = mysqli_fetch_array($movies_query)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($movie['Название']) . '</td>';
                            echo '<td>' . htmlspecialchars($movie['Режиссер']) . '</td>';
                            echo '<td>' . htmlspecialchars($movie['Год_выпуска']) . '</td>';
                            echo '<td>' . htmlspecialchars($movie['Описание']) . '</td>';

                            // Преобразование даты в нужный формат
                            $formatted_date = date("d.m.Y", strtotime($movie['Время_начала']));
                            echo '<td>' . $formatted_date . '</td>';

                            echo '<td>' . htmlspecialchars($movie['Цена']) . '</td>';

                            if ($flag == 1) {
                                echo '<td>';
                                echo '<div class="seat-container">';
                                echo '<div class="seat-map" id="seat-map-' . $movie['Идентификатор'] . '">';

                                // Получаем занятые места для этого сеанса
                                $occupied_seats = [];
                                $occupied_query = mysqli_query($dp, "SELECT Ряд, Место FROM бронирования WHERE Идентификатор_сеанса = '".$movie['session_id']."'");
                                while ($seat = mysqli_fetch_assoc($occupied_query)) {
                                    $occupied_seats[] = $seat['Ряд'] . '-' . $seat['Место'];
                                }

                                // Генерируем карту мест 8x8
                                for ($row = 1; $row <= 8; $row++) {
                                    for ($seat_num = 1; $seat_num <= 8; $seat_num++) {
                                        $seat_id = $row . '-' . $seat_num;
                                        $is_occupied = in_array($seat_id, $occupied_seats);
                                        echo '<div class="seat ' . ($is_occupied ? 'occupied' : '') . '" 
                                              data-row="' . $row . '" 
                                              data-seat="' . $seat_num . '" 
                                              data-movie="' . $movie['Идентификатор'] . '"
                                              onclick="selectSeat(this, ' . $movie['Идентификатор'] . ')">
                                              <span class="seat-label">' . $row . '-' . $seat_num . '</span>
                                              <input type="hidden" name="seat_' . $movie['Идентификатор'] . '_' . $row . '_' . $seat_num . '" value="0">
                                              </div>';
                                    }
                                }
                                echo '</div>';
                                echo '</div>';
                                echo '</td>';

                                echo '<td><input value="' . $movie['Идентификатор'] . '" name="ArrCart[]" type="checkbox"></td>';
                            }
                            echo '</tr>';
                        }
                        echo "</table>";
                        if ($flag == 1) {
                            echo "<div align='center'><input type='submit' name='addcart' value='Забронировать'></div>";
                        }
                        echo "</form>";
                    } else {
                        echo "Ошибка выполнения запроса: " . mysqli_error($dp);
                    }
                } else {
                    echo "Идентификатор кинотеатра не установлен.";
                }
            }
            ?>


            <?php
            if (isset($_POST["addcart"])) {
                if (isset($_POST["ArrCart"])) {
                    $user_id = $_SESSION['user']['id'];
                    $dates = 1;
                    foreach ($_POST["ArrCart"] as $prod) {
                        $film_id = $prod;
                        $session_query = mysqli_query($dp, "SELECT Идентификатор FROM сеансы WHERE Идентификатор_фильма = '$film_id'");
                        $session_row = mysqli_fetch_assoc($session_query);
                        $session_id = $session_row['Идентификатор'];

                        // Обрабатываем выбранные места
                        foreach ($_POST as $key => $value) {
                            if (strpos($key, 'seat_' . $film_id . '_') === 0 && $value == 1) {
                                $parts = explode('_', $key);
                                $row = $parts[2];
                                $seat = $parts[3];

                                // Проверка на забронированные места
                                $check_query = mysqli_query($dp, "SELECT * FROM бронирования WHERE Идентификатор_сеанса = '$session_id' AND Ряд = '$row' AND Место = '$seat'");
                                if (mysqli_num_rows($check_query) > 0) {
                                    echo "Место $row-$seat уже забронировано для этого сеанса.<br>";
                                } else {
                                    // Вставка записи в таблицу бронирования
                                    mysqli_query($dp, "INSERT INTO `бронирования` (`Идентификатор`, `Идентификатор_клиента`, `Идентификатор_сеанса`, `Количество_мест`, `Ряд`, `Место`) VALUES (NULL, '$user_id', '$session_id', '$dates', '$row', '$seat')");
                                    echo "Место $row-$seat успешно забронировано!<br>";
                                }
                            }
                        }
                    }
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

<script>
    function selectSeat(element, movieId) {
        if (element.classList.contains('occupied')) {
            return; // Нельзя выбрать занятое место
        }

        element.classList.toggle('selected');
        const row = element.dataset.row;
        const seat = element.dataset.seat;
        const input = document.querySelector(`input[name="seat_${movieId}_${row}_${seat}"]`);
        input.value = element.classList.contains('selected') ? 1 : 0;
    }
</script>
</body>
</html>