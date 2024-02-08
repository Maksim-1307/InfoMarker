<?php

session_start();
require_once '../../user/connect.php';

if (!$_SESSION["user"]["is_admin"]) {
    header('location: /');
    exit();
}

$name = $_POST['name'];
$about = $_POST['about'];

echo "test";

if (!(isset($_POST['name']) && isset($_POST['about']))){
    $_SESSION['error_message'] = "Ошибка. Отправьте форму повторно";
    header('Location: ../../pages/admin.php');
} else {
    mysqli_query($connect, "INSERT INTO `register` (`name`, `about`) VALUES ('$name', '$about')");
    $_SESSION['success_message'] = "Элемент успешно добавлен";
    header('Location: ../../pages/admin.php');
}


