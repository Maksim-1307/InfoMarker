<?php

class User{
    public function get_cash_path(){
        // Remake
        return $_SERVER['DOCUMENT_ROOT'] . "/user_cash_" . $_SESSION["user"]["id"];
    }
}

$pUser = new User();
$_SESSION["pUser"] = &$pUser;

?>