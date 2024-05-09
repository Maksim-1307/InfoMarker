<?php

class User{
    public function get_cash_path(){
        // Remake
        $path = $_SERVER['DOCUMENT_ROOT'] . "/user_cash_";
        if ($_SESSION["user"]["id"] == ""){
            $path = $path . time() . rand(0,9);
        } else {
            die("use is set");
            $path .= $_SESSION["user"]["id"];
        }
        return $path . "/";
    }
}

$pUser = new User();
$_SESSION["pUser"] = &$pUser;

?>