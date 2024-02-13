<?php

session_start();

foreach ($_SESSION as $prop => $val){
    unset($_SESSION[$prop]);
}

header('Location: ../index.php');