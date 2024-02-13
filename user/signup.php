<?php

session_start();
require_once 'connect.php';

$login = $_POST['login'];
$password = $_POST['password'];
$email = $_POST['e-mail'];
$full_name = $_POST['full_name'];
$password_confirm = $_POST['password-again'];

if ($password === $password_confirm){

    $password = md5($password);

    $is_OK = true;

    $check_username_request = "SELECT 1 FROM users WHERE login = '$login';";
    $result = $connect->query($check_username_request);
    echo mysqli_num_rows($result);
    if (mysqli_num_rows($result)){
        $_SESSION['error_message'] = "Этот логин уже занят, придумайте другой";
        header('Location: ../pages/register.php');
        $is_OK = false;
    } 

    $image_path = "uploads/" . time() . $_FILES['profile-image']['name'];
    if (!move_uploaded_file($_FILES['profile-image']['tmp_name'], '../' . $image_path)){
        // $_SESSION['error_message'] = "Ошибка при загрузке изображения";
        // header('Location: ../pages/register.php');
        // $is_OK = false;
        $image_path = '';
    }

    if ($is_OK){
        mysqli_query($connect, "INSERT INTO `users` (`id`, `login`, `email`, `full_name`, `password`, `avatar`) VALUES (NULL, '$login', '$email', '$full_name', '$password', '$image_path')");

        $_SESSION['success_message'] = 'Регистрация прошла успешно';
        header('Location: ../pages/login.php');
    }

} else {
    $_SESSION['error_message'] = "Пароли не совпадают";
    header('Location: ../pages/register.php');
}