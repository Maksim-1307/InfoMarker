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

$data = $_GET;
foreach ($data as $key => $val) {
    if (method_exists($user, "get_".$key)) {
        $data[$key] = $user->{"get_".$key}();
    }
}
var_dump($_SESSION);
echo (session_id());
echo json_encode(["login" => $user]);


?>