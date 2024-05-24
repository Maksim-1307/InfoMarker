<?php

session_start();

require_once 'Form.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/filehandler/Uploader.php';

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
    public static $avatarUploader;

    function __construct(){

        if (!self::$avatarUploader){
            self::$avatarUploader = new Uploader();
            self::$avatarUploader->set_rule("allowed_extensios", ["png", "jpg", "jpeg"]);
            self::$avatarUploader->set_rule("allow_rewrite", false);
            self::$avatarUploader->set_rule("allow_create_dir", true);
            self::$avatarUploader->set_rule("unque_name_prefix", "user-avatar__");
        }

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

    public static function check_login($field){

        if (!$field->value) return "Введите логин";

        require_once 'connect.php';

        $result = $connect->query("SELECT 1 FROM users WHERE login = '$field->value';");
        if (mysqli_num_rows($result)){
            return "Этот логин уже занят, придумайте другой";
        } 
        return false;

    }

    public static function check_email($field){

        if (!filter_var($field->value, FILTER_VALIDATE_EMAIL)) {
            return "Введите корректный адрес электронной почты";
        }
        return false;

    }

    public static function check_password($field){

        function check_len($pass){
            if (strlen($pass) < 6) return false;
            return true;
        }
        function check_numbers($pass){
            $len = strlen($pass);
            $noNumLen = strlen(preg_replace('0123456789', '', $pass));
            if ($len == $noNumLen) return false;
            return true;
        }

        $pass = $field->value;
        if (!$pass) {
            return "Введите пароль";
        }
        if (!check_len($pass) || !check_numbers($pass)){
            return "Пароль должен состоять не менее чем из 6 символов и содержать хотя бы одну цифру";
        }
        return false;

    }

    public static function check_avatar($field){

        if (!count($_FILES) && $field->required) return "Не удалось загрузить файл";

        return false;


    }


    // returns empty form with requiered fields

    public function get_register_form($action){

        $registerForm = new Form("POST", $action, 'multipart/form-data');

        $registerForm->add_field("login", "text", true, "Логин" ,["User", "check_login"]);
        $registerForm->add_field("email", "email", true, "Ваш e-mail", ["User", "check_email"]);
        $registerForm->add_field("fullname", "text", true, "Как к Вам обращаться?", null);
        $registerForm->add_field("password", "password", true, "Придумайте пароль", ["User", "check_password"]);
        $registerForm->add_field("avatar", "file", false, "Выберите изображение профиля", ["User", "check_avatar"]);

        return $registerForm;

    }

    public function get_login_form($action){

        $loginForm = new Form("GET", $action);

        $loginForm->add_field("login", "text", true, "Логин", null);
        $loginForm->add_field("password", "password", true, "Пароль", null);

        return $loginForm;
    }


    // requieres filled form, derived from get_register_form

    public function register(Form $registerForm){

        require 'connect.php';

        $fields = $registerForm->get_values_array();

        $login = $fields["login"];
        $email = $fields["email"];
        $full_name = $fields["fullname"];
        $password = md5($fields["password"]);
        $image_path = NULL;
        
        try {
            $image_path = self::$avatarUploader->upload($_SERVER['DOCUMENT_ROOT'] . '/uploads/profile-images/');
        } 
        catch (Exception $e) {
            $image_path = NULL;
        }

          

        $check = mysqli_query($connect, "INSERT INTO `users` (`id`, `login`, `email`, `full_name`, `password`, `avatar`) VALUES (NULL, '$login', '$email', '$full_name', '$password', '$image_path')");

        return json_encode(["success" => true]);

    }

    public function login(Form $loginForm){
        require 'connect.php';

        $fields = $loginForm->get_values_array();

        $login = $fields["login"];
        $password = md5($fields["password"]);

        $check = mysqli_query($connect, "SELECT * FROM  `users` WHERE `login` = '$login' AND `password` = '$password'");
        if (mysqli_num_rows($check)){
            $user = mysqli_fetch_assoc($check);
            $this->userData["login"] = $user["login"];
            return true;
        } else {
            return false;
        }
    }

    public function get_login(){
        return $this->userData["login"];
    }
    public function get_fullname(){
        require "connect.php";
        $login = $this->userData["login"];
        $sql = mysqli_query($connect, "SELECT `full_name` FROM  `users` WHERE `login` = 'Maksim'");
        $result = mysqli_fetch_assoc($sql);
        return $result["full_name"];
    }
}

if (!isset($_SESSION["pUser"])){
    $pUser = new User();
    $_SESSION["pUser"] = &$pUser;
}


?>