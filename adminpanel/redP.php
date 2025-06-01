<?php
session_start();
if ($_SESSION['ADMIN']) {
    $flag = 1;
}
$usname = $_SESSION['ADMIN']['login'];
include '../connect.php';

//$DELsession =  $_GET ['DELsession'];
if($DELsession > 0){
    mysqli_query($dp, "DELETE FROM бронирования WHERE бронирования.Идентификатор_сеанса = $DELsession");
    mysqli_query($dp , "DELETE FROM  сеансы WHERE  сеансы.Идентификатор = $DELsession");
    header("Location: redP.php");
    exit;
}

$IDsession  =  $_GET ['IDsession'];
$sessions = mysqli_query($dp, "SELECT * FROM сеансы");
$films = mysqli_query($dp, "SELECT * FROM фильмы");
$cinemas = mysqli_query($dp, "SELECT * FROM кинотеатры");
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
                <h1 class="features-t">Изменение сеансов</h1>
                <table align="center">
                    <td>
                        <div class="navs-item" align="center"><a href="../adminpanel/profileA.php"><button class="btn txt-uppercase blue">Вернуться назад</button></a></div>
                    </td><td>
                        <div class="navs-item" align="center"><a href="../pages/cinema.php" target="_blank"><button class="btn txt-uppercase">Перейти на страницу сеансов</button></a></div>
                    </td>
                </table>

                <div align="center">
                    <form action="redP.php" method="get">
                        <select name="filter_film">
                            <option value="" <?php if (!isset($_GET['filter_film'])) echo 'selected'; ?>>Выберите фильм</option>
                            <?php
                            foreach ($films as $film) {
                                $selected = ($film['Идентификатор'] == $_GET['filter_film']) ? ' selected' : '';
                                echo '<option value="' . $film['Идентификатор'] . '"' . $selected . '>' . $film['Название'] . '</option>';
                            }
                            ?>
                        </select>

                        <select name="filter_cinema">
                            <option value="" <?php if (!isset($_GET['filter_cinema'])) echo 'selected'; ?>>Выберите кинотеатр</option>
                            <?php
                            foreach ($cinemas as $cinema) {
                                $selected = ($cinema['Идентификатор'] == $_GET['filter_cinema']) ? ' selected' : '';
                                echo '<option value="' . $cinema['Идентификатор'] . '"' . $selected . '>' . $cinema['Название'] . '</option>';
                            }
                            ?>
                        </select>

                        <input type="submit" value="Применить фильтр">
                    </form>
                </div>




                <table align="center" class="cart-tables">
                    <tr>
                        <th>Название фильма</th>
                        <th>Дата и время</th>
                        <th>Кинотеатр</th>
                        <th colspan=3>Действие</th>
                    </tr>
                    <form method="POST">
                        <tr>
                            <td>
                                <select name="add_session_film">
                                    <option value="">Выберите фильм</option>
                                    <?php
                                    foreach ($films as $film) {
                                        echo '<option value="' . $film['Идентификатор'] . '">' . $film['Название'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input  type="datetime-local"  name= "add_session_datetime" placeholder="Введите дату и время">
                            </td>
                            <td>
                                <select name="add_session_cinema">
                                    <option value="">Выберите кинотеатр</option>
                                    <?php
                                    foreach ($cinemas as $cinema) {
                                        echo '<option value="' . $cinema['Идентификатор'] . '">' . $cinema['Название'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td colspan="2" align="center">
                                <input  type= "submit"  name = "add_session" value= "Добавить">
                            </td>
                        </tr>
                    </form>
                    <?php
                    $filter_film = isset($_GET['filter_film']) ? $_GET['filter_film'] : '';
                    $filter_cinema = isset($_GET['filter_cinema']) ? $_GET['filter_cinema'] : '';


                    $sessions = mysqli_query($dp, "SELECT * FROM сеансы WHERE 1=1" . ($filter_film ? " AND Идентификатор_фильма=$filter_film" : "") . ($filter_cinema ? " AND Идентификатор_кинотеатра=$filter_cinema" : ""));

                    while($session = mysqli_fetch_array($sessions)){
                        $film_name = '';
                        $cinema_name = '';

                        foreach ($films as $film) {
                            if ($session['Идентификатор_фильма'] == $film['Идентификатор']) {
                                $film_name = $film['Название'];
                                break;
                            }
                        }

                        foreach ($cinemas as $cinema) {
                            if ($session['Идентификатор_кинотеатра'] == $cinema['Идентификатор']) {
                                $cinema_name = $cinema['Название'];
                                break;
                            }
                        }

                        if (isset($_GET['edit']) && $_GET['edit'] == $session['Идентификатор']) {
                            ?>
                            <form method="POST">
                                <input type="hidden" name="session_id" value="<?= $session['Идентификатор'] ?>">
                                <tr>
                                    <td>
                                        <select name="edit_session_film">
                                            <option value="">Выберите фильм</option>
                                            <?php
                                            foreach ($films as $film) {
                                                $selected = ($film['Идентификатор'] == $session['Идентификатор_фильма']) ? ' selected' : '';
                                                echo '<option value="' . $film['Идентификатор'] . '"' . $selected . '>' . $film['Название'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input  type="datetime-local"  name= "edit_session_datetime" value="<?= htmlspecialchars($session['Время_начала']) ?>">
                                    </td>
                                    <td>
                                        <select name="edit_session_cinema">
                                            <option value="">Выберите кинотеатр</option>
                                            <?php
                                            foreach ($cinemas as $cinema) {
                                                $selected = ($cinema['Идентификатор'] == $session['Идентификатор_кинотеатра']) ? ' selected' : '';
                                                echo '<option value="' . $cinema['Идентификатор'] . '"' . $selected . '>' . $cinema['Название'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td colspan="1"><input type="submit" name="update_session" value="Обновить"></td>
                                    <td colspan="1"><input type="submit" formaction="redP.php" value="Отменить"></td>
                                </tr>
                            </form>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td><?= $film_name ?></td>
                                <td><?= date("d.m.Y H:i", strtotime($session['Время_начала'])) ?>
                            </td>
                            <td><?= $cinema_name ?></td>
                            <td>
                                <form action="redP.php" method="get">
                                    <input type="hidden" name="edit" value="<?php echo $session['Идентификатор']; ?>">
                                    <button type="submit">Редактировать</button>
                                </form>
                            </td>

                            <td>
                                <form action="redP.php" method="get">
                                    <input type="hidden" name="IDsession" value="<?php echo $session['Идентификатор']; ?>">
                                    <button type="submit">Удалить</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>

<?php
if (isset($_POST["update_session"])){
    $session_id = $_POST["session_id"];
    $edit_session_film = $_POST["edit_session_film"];
    $edit_session_datetime = $_POST["edit_session_datetime"];
    $edit_session_cinema = $_POST["edit_session_cinema"];

    if(!empty($edit_session_film)){
        mysqli_query($dp, "UPDATE сеансы SET `Идентификатор_фильма` = '$edit_session_film' WHERE сеансы.Идентификатор = '$session_id'" );
    }
    if(!empty($edit_session_datetime)){
        mysqli_query($dp, "UPDATE сеансы SET `Время_начала` = '$edit_session_datetime' WHERE сеансы.Идентификатор = '$session_id'" );
    }
    if(!empty($edit_session_cinema)){
        mysqli_query($dp, "UPDATE сеансы SET `Идентификатор_кинотеатра` = '$edit_session_cinema' WHERE сеансы.Идентификатор = '$session_id'" );
    }
    echo '<meta http-equiv="refresh" content="0; url=redP.php">';
    exit;
}

if (isset($_POST["add_session"])){
    $add_session_film = $_POST["add_session_film"];
    $add_session_datetime = $_POST["add_session_datetime"];
    $add_session_cinema = $_POST["add_session_cinema"];
    mysqli_query($dp , "INSERT INTO `сеансы` (`Идентификатор`, `Идентификатор_фильма`, `Идентификатор_кинотеатра`, `Время_начала`) VALUES (NULL, '$add_session_film', '$add_session_cinema', '$add_session_datetime');");

    echo '<meta http-equiv="refresh" content="0; url=redP.php">';
}
?>
