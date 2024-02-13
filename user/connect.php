<?php

$connect = mysqli_connect(ini_get("mysql.default_host"), 'root', 'Hm8d67_rE40?k', 'infomarker');

if (!$connect){
    die('Databese connection failed');
} 
