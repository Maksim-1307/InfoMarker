<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

error_reporting(1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/user/User.php';

// echo "INPUT CONTENTS: " . file_get_contents("php://input") . "<br>";
// var_dump($_REQUEST);
$_POST = $_REQUEST;


$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$user = $_SESSION["pUser"];
if (!$user) throw new Exception("Unable to get user data");

$form = $user->get_register_form($url);


if (!$form->parse_data()) {
    
    echo $user->get_register_form($url)->json_describe();
    exit();
} else {
    if (!$form->validate_fields()){
        echo $form->json_describe();
    } else {
        echo $user->register($form);
    }
}


?>