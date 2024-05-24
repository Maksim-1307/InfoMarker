<?php

session_start();

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$response = $_REQUEST;
foreach ($response as $name => $value) {
    if (isset($_SESSION[$name])) {
        $response[$name] = $_SESSION[$name];
    } else {
        $response[$name] = $name . " is unset in session. Session id: " . session_id();
    }
}
echo json_encode($response); 

?>