<?php

require_once 'Form.php';

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

        if (!field->value) return "Введите логин";

        require_once $_SERVER['DOCUMENT_ROOT'] . '/secret.php';

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

        function errCallBack ($e) {
            // 15 is unsupported extention code
            if ($e->getCode() == 15) {
                return "Выберите файл с расширением <b>png</b>, <b>jpg</b> или <b>jpeg</b>";
            }
        }

        if ($field->required && !$file->value) return "Выберите изображение"; 
        if (!$field->required && !$file->value) return false;

        $uploadPath = self::$avatarUploader->upload($_SERVER['DOCUMENT_ROOT'] . '/uploads/profile-images/', errCallBack);
        if (!$uploadPath || !is_file($uploadPath)) return "Не удалось загрузить изображение";

        return false;


    }


    // returns empty form with requiered fields

    public function get_register_form($action){

        $registerForm = new Form(Method::POST, $action, 'multipart/form-data');

        $registerForm->add_field("login", "text", true, User::check_login);
        $registerForm->add_field("email", "email", true, User::check_email);
        $registerForm->add_field("fullname", "text", true);
        $registerForm->add_field("password", "password", true, User::check_password);
        $registerForm->add_field("avatar", "file", true, User::check_avatar);

        return $registerForm;

    }


    // requieres filled form, derived from get_register_form

    public function register(Form $registerForm){

    }
}

$pUser = new User();
$_SESSION["pUser"] = &$pUser;

?>