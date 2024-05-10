<?php

enum AuthType {
    case default;
    case yandex;
    case vk;
    case telegram;
    case google;
}

class UserData {
    public AuthType $authType = AuthType::default;
    public $id;
    public $login;
    public $email;
    public $fullName;
    public $logo;
    public $password;
    public $isAdmin;
}

class User{

    public $userData;

    function __construct(){
        
    }

    public function is_authorized(){
        if (isset($this->userData)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_cash_path(){
        $path = $_SERVER['DOCUMENT_ROOT'] . "/user_cash_";
        if ($this->userData){
            $path .= $this->get_id();
        } else {
            $path = $path . time() . rand(0,9);
        }
        return $path . "/";
    }
}

$pUser = new User();
$_SESSION["pUser"] = &$pUser;

?>