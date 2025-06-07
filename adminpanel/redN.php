<?php
session_start();
if ($_SESSION['ADMIN']) {
    $flag = 1;
}
$usname = $_SESSION['ADMIN']['login'];
include '../connect.php';

$DELfilm = isset($_GET['DELfilm']) ? $_GET['DELfilm'] : 0;
if($DELfilm > 0){
    mysqli_query($dp, "DELETE b FROM бронирования b JOIN сеансы s ON b.Идентификатор_сеанса = s.Идентификатор WHERE s.Идентификатор_фильма = '$DELfilm'");
    mysqli_query($dp, "DELETE FROM сеансы WHERE сеансы.Идентификатор_фильма = '$DELfilm'");
    mysqli_query($dp, "DELETE FROM фильмы WHERE фильмы.Идентификатор = '$DELfilm'");
    header("Location: redN.php");
    exit;
}

if (isset($_POST["UpdateFilm"])) {
    $film = mysqli_real_escape_string($dp, $_POST['film_id']);
    $UpdTitle = mysqli_real_escape_string($dp, $_POST['UpdTitle']);
    $UpdYear = mysqli_real_escape_string($dp, $_POST['UpdYear']);
    $UpdDirector = mysqli_real_escape_string($dp, $_POST['UpdDirector']);
    $UpdDescription = mysqli_real_escape_string($dp, $_POST['UpdDescription']);
    $UpdPoster = mysqli_real_escape_string($dp, $_POST['UpdPoster']);

    mysqli_query($dp, "UPDATE `фильмы` SET `Название` = '$UpdTitle', `Год_выпуска` = '$UpdYear', `Режиссер` = '$UpdDirector', `Описание` = '$UpdDescription', `Постер` = '$UpdPoster' WHERE `Идентификатор` = '$film'");

    header("Location: redN.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AdminPANEL</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" type="text/css" href="../css/color-text.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/shadow.css">
    <style>
        .cart-tables {
            table-layout: fixed;
            word-wrap: break-word;
        }
        .clickable-poster {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
        .cart-tables th,
        .cart-tables td {
            max-width: 200px;
        }
        td {
            max-width: 200px;
        }
        .poster-img {
            max-width: 100px;
            max-height: 150px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="container">
        <div class="navbar-nav">
            <div class="navbar-brand">
                <a href="profileA.php"><img class="navbar-brand-png" src="../img/logo_main.png"><a>
            </div>
            <div class="navs" id="navs">
                <div class="navs-item"><a href="../RAA/logout.php"><button class="btn txt-uppercase shadow-sm">Выход</button></a></div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="jumbotron-item">
        <div class="features-box">
            <h1 class="features-t">Изменение фильмов</h1>

            <table align="center">
                <td>
                    <div class="navs-item" align="center"><a href="../adminpanel/profileA.php"><button class="btn txt-uppercase blue">Вернуться назад</button></a></div>
                </td><td>
                    <div class="navs-item" align="center"><a href="../index.php" target="_blank"><button class="btn txt-uppercase">Перейти на страницу фильмов</button></a></div>
                </td>
            </table>

            <input type="text" id="search-title" placeholder="Поиск по названию">
            <input type="text" id="search-director" placeholder="Поиск по режиссеру">

            <script>
                document.getElementById("search-title").addEventListener("input", filterTable);
                document.getElementById("search-director").addEventListener("input", filterTable);

                function filterTable() {
                    const searchTitle = document.getElementById("search-title").value.toLowerCase();
                    const searchDirector = document.getElementById("search-director").value.toLowerCase();

                    const rows = document.querySelectorAll("table tr");
                    rows.forEach((row) => {
                        if (row.querySelector("td")) {
                            const title = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                            const director = row.querySelector("td:nth-child(2)").textContent.toLowerCase();

                            if (title.includes(searchTitle) && director.includes(searchDirector)) {
                                row.style.display = "";
                            } else {
                                row.style.display = "none";
                            }
                        }
                    });
                }
            </script>

            <?php
            $IDfilm = isset($_GET['IDfilm']) ? $_GET['IDfilm'] : 0;
            $films = mysqli_query($dp, "SELECT * FROM фильмы");
            ?>
            <table align="center" class="cart-tables">
                <tr>
                    <th>Название</th>
                    <th>Режиссер</th>
                    <th>Год выпуска</th>
                    <th>Описание</th>
                    <th>Постер</th>
                    <th colspan=3>Действие</th>
                </tr>
                <form method="POST">
                    <tr>
                        <td><input type="text" name="add_film_name" placeholder="Введите название"></td>
                        <td><input type="text" name="add_film_director" placeholder="Введите режиссера"></td>
                        <td><input type="text" name="add_film_year" placeholder="Введите год выпуска"></td>
                        <td><textarea name="add_film_description" placeholder="Введите описание"></textarea></td>
                        <td><textarea name="add_film_poster" placeholder="Вставьте URL постера"></textarea></td>
                        <td colspan="2" align="center"><input type="submit" name="add_film" value="Добавить"></td>
                    </tr>
                </form>
                <?php
                while($film = mysqli_fetch_array($films)) {
                    if (isset($_GET['edit']) && $_GET['edit'] == $film['Идентификатор']) {
                        ?>
                        <form method="POST">
                            <tr>
                                <input type="hidden" name="film_id" value="<?= $film['Идентификатор'] ?>">
                                <td><input type="text" name="UpdTitle" value="<?= htmlspecialchars($film['Название']) ?>"></td>
                                <td><input type="text" name="UpdDirector" value="<?= htmlspecialchars($film['Режиссер']) ?>"></td>
                                <td><input type="text" name="UpdYear" value="<?= htmlspecialchars($film['Год_выпуска']) ?>"></td>
                                <td><input type="text" name="UpdDescription" value="<?= htmlspecialchars($film['Описание']) ?>"></td>
                                <td><input type="text" name="UpdPoster" value="<?= htmlspecialchars($film['Постер']) ?>"></td>
                                <td colspan="1"><input type="submit" name="UpdateFilm" value="Обновить"></td>
                                <td colspan="1"><input type="button" id="cancel-button" name="redN.php" value="Отменить"></td>
                            </tr>
                        </form>
                        <?php
                    } else{
                        echo '<tr>
                            <td>' . $film['Название'] . '</td>
                            <td>' . $film['Режиссер'] . '</td>
                            <td>' . $film['Год_выпуска'] . '</td>
                            <td>' . $film['Описание'] . '</td>
                            <td><img src="' . $film['Постер'] . '" class="poster-img" alt="Постер фильма"></td>
                            <td>
                                <form action="redN.php" method="get">
                                    <input type="hidden" name="edit" value="' . $film['Идентификатор'] . '">
                                    <button type="submit">Редактировать</button>
                                </form>
                            </td>
                            <td>
                                <form action="redN.php" method="get">
                                    <input type="hidden" name="DELfilm" value="' . $film['Идентификатор'] . '">
                                    <button type="submit">Удалить</button>
                                </form>
                            </td>
                        </tr>';
                    }
                }
                ?>
            </table>

            <?php
            if (isset($_POST["add_film"])){
                $add_film_name = mysqli_real_escape_string($dp, $_POST["add_film_name"]);
                $add_film_director = mysqli_real_escape_string($dp, $_POST["add_film_director"]);
                $add_film_year = mysqli_real_escape_string($dp, $_POST["add_film_year"]);
                $add_film_description = mysqli_real_escape_string($dp, $_POST["add_film_description"]);
                $add_film_poster = mysqli_real_escape_string($dp, $_POST["add_film_poster"]);
                mysqli_query($dp, "INSERT INTO `фильмы` (`Идентификатор`, `Название`, `Режиссер`, `Год_выпуска`, `Описание`, `Постер`) VALUES (NULL, '$add_film_name', '$add_film_director', '$add_film_year', '$add_film_description', '$add_film_poster');");
                echo '<meta http-equiv="refresh" content="0; url=redN.php">';
            }
            ?>
        </div>
    </div>
</div>
<script>
    document.getElementById("cancel-button").addEventListener("click", function() {
        location.href = "redN.php";
    });
</script>
</body>
</html>