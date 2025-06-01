<?php
session_start();
if ($_SESSION['ADMIN']) {
    $flag = 1;
}
$usname = $_SESSION['ADMIN']['login'];
include '../connect.php';

$DELclient  =  $_GET ['DELclient'];
if($DELclient > 0){

    mysqli_query($dp , "DELETE FROM бронирования WHERE Идентификатор_клиента = '$DELclient'");

    mysqli_query($dp , "DELETE FROM клиенты WHERE Идентификатор = '$DELclient'");
    header("Location: redC.php");
    exit;
}

$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$filter_field = isset($_GET['filter_field']) ? $_GET['filter_field'] : 'Имя';
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
        <h1 class="features-t">Информация о клиентах</h1>



<table align="center">
    <td>
<div class="navs-item" align="center"><a href="../adminpanel/profileA.php"><button class="btn txt-uppercase blue">Вернуться назад</button></a></div>
    </td><td>
<div class="navs-item" align="center"><a href="../#" target="_blank"><button class="btn txt-uppercase">Перейти на страницу клиента</button></a></div>
    </td>
</table>

<div align="center">
    <form action="redC.php" method="get">
        <select name="filter_field">
            <option value="Имя" <?php if($filter_field == 'Имя') echo 'selected'; ?>>Имя</option>
            <option value="Фамилия" <?php if($filter_field == 'Фамилия') echo 'selected'; ?>>Фамилия</option>
        </select>
        <select name="order">
            <option value="asc" <?php if($order == 'asc') echo 'selected'; ?>>Возрастанию</option>
            <option value="desc" <?php if($order == 'desc') echo 'selected'; ?>>Убыванию</option>
        </select>
        <button type="submit">Фильтр</button>
    </form>
</div>

<?php
$IDclient  =  $_GET ['IDclient'];
$ZAKclient  =  $_GET ['ZAKclient'];
$CLIENTI = mysqli_query($dp, "SELECT * FROM клиенты ORDER BY $filter_field $order");

if($IDclient > 0)
{
    $CLIENTIS = mysqli_fetch_assoc((mysqli_query($dp, "SELECT * FROM клиенты WHERE клиенты.Идентификатор = $IDclient")));
?>
</br><table align="center" class="cart-tables">
<tr><th align="center"><b>Информация о клиенте</b></th>

<tr><td><p>ФИО: <b><?php echo $CLIENTIS['Имя'] . ' ' . $CLIENTIS['Фамилия']; ?></b></p></td>

<tr><td> <p>Логин:<b> <?php echo $CLIENTIS['Логин']; ?></b></p></td>

<tr><td> <p>Почта:<b> <?php echo $CLIENTIS['Электронная_почта']; ?></b></p></td>

<tr><td><a href="redC.php">Вернуться к списку клиентов</a></td>
<?php
} else if($ZAKclient > 0){
?>
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
                    $bookings = mysqli_query($dp, "SELECT * FROM бронирования WHERE Идентификатор_клиента = '$ZAKclient'");
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
                        echo '<td>' . date("d.m.Y H:i", strtotime($session_info['Время_начала'])) . '</td>';
                        echo '<td>' . $booking['Ряд'] . '</td>';
                        echo '<td>' . $booking['Место'] . '</td>';
                        echo '<td>';

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
    </br></br><div class="navs-item" align="center"><a href="redC.php"><button class="btn txt-uppercase shadow-sm blue">Назад</button></a></div>

<?php
} else {
?>
    <table align="center" class="cart-tables" >
    <tr>
        <th>Клиент</th>
        <th>Имя</th>
        <th>Фамилия</th>
        <th>Почта</th>
        <th colspan=3>Действие</th>
    </tr>
<?php
    while($OutCL = mysqli_fetch_array($CLIENTI)){
        echo '<tr><td>' . $OutCL['Идентификатор'] . '</td>
            <td>' . $OutCL['Имя'] . '</td>
            <td>' . $OutCL['Фамилия'] . '</td>
            <td>' . $OutCL['Электронная_почта'] . '</td>
    
            <td>
                <form action="redC.php" method="get">
                    <input type="hidden" name="DELclient" value="' . $OutCL['Идентификатор'] . '">
                    <button type="submit">Удалить</button>
                </form>
            </td>
    
            <td>
                <form action="redC.php" method="get">
                    <input type="hidden" name="ZAKclient" value="' . $OutCL['Идентификатор'] . '">
                    <button type="submit">Заказы</button>
                </form>
                </td></tr>';
    }

    echo '</table>';
}

?>

    </table>
    <?php
    if(isset($_POST["delete_booking"])){
        $booking_id = $_POST["booking_id"];
        $result = mysqli_query($dp, "DELETE FROM бронирования WHERE Идентификатор = '$booking_id'");
        if ($result) {
            header("Location: redC.php?ZAKclient=$ZAKclient");
            exit;
        } else {

        }
        exit;
    }
    ?>
</body>
</html>
