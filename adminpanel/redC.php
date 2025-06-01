<?php
session_start();
if (isset($_SESSION['ADMIN'])) {
    $flag = 1;
}
$usname = isset($_SESSION['ADMIN']['login']) ? $_SESSION['ADMIN']['login'] : null;

// Подключение к базе данных
include '../connect.php';

// Инициализация переменных
$DELclient = isset($_GET['DELclient']) ? $_GET['DELclient'] : null;
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$filter_field = isset($_GET['filter_field']) ? $_GET['filter_field'] : 'Имя';

// Удаление клиента
if ($DELclient > 0) {
    mysqli_query($dp, "DELETE FROM бронирования WHERE Идентификатор_клиента = '$DELclient'");
    mysqli_query($dp, "DELETE FROM клиенты WHERE Идентификатор = '$DELclient'");
    header("Location: redC.php");
    exit;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="navbar">
    <div class="container">
        <div class="navbar-nav">
            <div class="navbar-brand">
                <a href="profileA.php"><img class="navbar-brand-png" src="../img/logo_main.png"></a>
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
                <tr>
                    <td>
                        <div class="navs-item" align="center"><a href="../adminpanel/profileA.php"><button class="btn txt-uppercase blue">Вернуться назад</button></a></div>
                    </td>
                    <td>
                        <div class="navs-item" align="center"><a href="../#" target="_blank"><button class="btn txt-uppercase">Перейти на страницу клиента</button></a></div>
                    </td>
                </tr>
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

            <table align="center" class="cart-tables">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Почта</th>
                    <th colspan="2">Действие</th>
                </tr>
                <?php
                $CLIENTI = mysqli_query($dp, "SELECT * FROM клиенты ORDER BY $filter_field $order");
                while($OutCL = mysqli_fetch_array($CLIENTI)) {
                    echo '<tr id="client-row-' . $OutCL['Идентификатор'] . '">';
                    echo '<td>' . htmlspecialchars($OutCL['Идентификатор']) . '</td>';
                    echo '<td class="editable" data-id="' . $OutCL['Идентификатор'] . '" data-field="Имя">' . htmlspecialchars($OutCL['Имя']) . '</td>';
                    echo '<td class="editable" data-id="' . $OutCL['Идентификатор'] . '" data-field="Фамилия">' . htmlspecialchars($OutCL['Фамилия']) . '</td>';
                    echo '<td class="editable" data-id="' . $OutCL['Идентификатор'] . '" data-field="Электронная_почта">' . htmlspecialchars($OutCL['Электронная_почта']) . '</td>';
                    echo '<td>
                                <form action="redC.php" method="get" style="display:inline;">
                                    <input type="hidden" name="DELclient" value="' . $OutCL['Идентификатор'] . '">
                                    <button type="submit">Удалить</button>
                                </form>
                              </td>';
                    echo '<td>
                                <button onclick="editClient(' . $OutCL['Идентификатор'] . ', this)">Редактировать</button>
                              </td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>

<script>
    function editClient(id, button) {
        var row = $('#client-row-' + id);
        row.find('td.editable').each(function() {
            var currentValue = $(this).text();
            var field = $(this).data('field');
            $(this).html('<input type="text" name="' + field + '" value="' + currentValue + '" />');
        });
        button.innerHTML = 'Сохранить';
        button.onclick = function() { saveClient(id, button); };
    }

    function saveClient(id, button) {
        var row = $('#client-row-' + id);
        var data = { client_id: id };

        row.find('td.editable').each(function() {
            var field = $(this).data('field');
            var newValue = $(this).find('input').val();
            data[field] = newValue;
        });

        $.ajax({
            url: 'update_client.php',
            type: 'POST',
            data: data,
            success: function(response) {
                row.find('td.editable').each(function() {
                    var field = $(this).data('field');
                    $(this).text(data[field]);
                });
                button.innerHTML = 'Редактировать';
                button.onclick = function() { editClient(id, button); };
            },
            error: function(xhr, status, error) {
                alert('Ошибка при сохранении данных: ' + error);
            }
        });
    }
</script>
</body>
</html>
