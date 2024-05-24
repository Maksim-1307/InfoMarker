<?php

session_start();

header('Access-Control-Allow-Origin: https://info-marker.ru');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

error_reporting(1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/user/User.php';

$_POST = $_REQUEST;
$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$user = $_SESSION["pUser"];
if (!$user) throw new Exception("Unable to get user data");

$form = $user->get_login_form($url);


if (!$form->parse_data()) {
    echo $user->get_login_form($url)->json_describe();
    exit();
} else {
    if (!$user->login($form)){
        $form->fields[0]->error = "Неверный логин или пароль";
        echo $form->json_describe();
    } else {
        echo json_encode(["success" => true, "session" => session_id(), "login" => $_SESSION["pUser"]]);
    }
}


?>