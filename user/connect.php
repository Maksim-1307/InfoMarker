<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/secret.php';

$connect = mysqli_connect(ini_get("mysql.default_host"), 'root', $mysqlPass, $mysqlTableName);

if (!$connect){
    throw new Exception ("Database connection failed. Check mysql password and database name defined in secret.php");
} 

?>
