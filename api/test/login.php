<?php

session_start();

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// тут мы как будто проверяем логин и пароль

// если все хорошо, сохраняем в сессию
$_SESSION["login"] = $_REQUEST["login"];
echo json_encode(["success"=>true, "session_id" => session_id()]);

?>