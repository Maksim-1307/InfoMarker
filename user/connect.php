<?php

$connect = mysqli_connect('localhost', 'root', 'root', 'authorization');

if (!$connect){
    die('Databese connection failed');
}