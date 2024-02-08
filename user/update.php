<?php

session_start();

require_once 'connect.php';

$login = $_POST['login'];
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$image_path;

$isOk = true;

$check_username_request = "SELECT 1 FROM users WHERE login = '$login'";
$result = $connect->query($check_username_request);
echo mysqli_num_rows($result);
if (mysqli_num_rows($result)) {
    $_SESSION['error_message'] = "Этот логин уже занят, придумайте другой";
    $isOk = false;
    header('Location: ../pages/user.php');
} 

if ($_FILES['profile-image']['name']){
    $image_path = "uploads/" . time() . $_FILES['profile-image']['name'];
    if (!move_uploaded_file($_FILES['profile-image']['tmp_name'], '../' . $image_path)) {
        $_SESSION['error_message'] = "Ошибка при загрузке изображения";
        header('Location: ../pages/user.php');
        $isOk = false;
    }
}

/*
email
full_name
avatar

UPDATE users SET [filed]=[val] WHERE id=[user_id]

*/

if ($isOk){


    if (isset($login))      mysqli_query($connect, "UPDATE `users` SET `login` = '$login' WHERE id=" . $_SESSION['user']['id']);
    if (isset($email))      mysqli_query($connect, "UPDATE `users` SET `email` = '$email' WHERE id=" . $_SESSION['user']['id']);
    if (isset($full_name))  mysqli_query($connect, "UPDATE `users` SET `full_name` = '$full_name' WHERE id=" . $_SESSION['user']['id']);
    if (isset($image_path)) mysqli_query($connect, "UPDATE `users` SET `avatar` = '$image_path' WHERE id=" . $_SESSION['user']['id']);

    if (isset($login))      $_SESSION["user"]["login"] = $login;
    if (isset($email))      $_SESSION["user"]["email"] = $email;
    if (isset($full_name))  $_SESSION["user"]["full_name"] = $full_name;
    if (isset($image_path)) $_SESSION["user"]["avatar"] = $image_path;

    $_SESSION['success_message'] = 'Данные успешно обновлены';
    header('Location: ../pages/user.php');

}