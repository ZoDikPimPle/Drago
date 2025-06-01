<?php
include '../connect.php';

if (isset($_POST['client_id']) && isset($_POST['field']) && isset($_POST['value'])) {
    $client_id = $_POST['client_id'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $update_query = "UPDATE клиенты SET `$field` = '$value' WHERE Идентификатор = '$client_id'";
    $update_result = mysqli_query($dp, $update_query);

    if ($update_result) {
        echo "Данные успешно обновлены";
    } else {
        echo "Ошибка обновления данных: " . mysqli_error($dp);
    }
}
?>
