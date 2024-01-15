<?php

session_start();
require_once 'connect.php';

$login = $_POST['login'];
$password = $_POST['password'];

$password = md5($password);

$check_user = mysqli_query($connect, "SELECT * FROM  `users` WHERE `login` = '$login' AND `password` = '$password'");

if (mysqli_num_rows($check_user)){
    $user = mysqli_fetch_assoc($check_user);
    $_SESSION['user'] = [
        "id" => $user["id"],
        "login" => $user["login"],
        "email" => $user["email"],
        "full_name" => $user["full_name"],
        "avatar" => $user["avatar"],
        "is_admin" => $user["is_admin"]
    ];
    header('Location: ../');
} else {
    $_SESSION['error_message'] = "Неверный логин или пароль <a class='link' href='../restore.php'>восстановить пароль</a>";
    header('Location: ../pages/login.php');
}