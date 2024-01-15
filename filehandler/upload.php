загрузка на сервер...
<?php
session_start();
require_once 'settings.php';

function deleteDir(string $dirPath): void
{
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

$path_to_root = "../";
$user_id = $_SESSION["user"]["id"];
$_SESSION["cash_directory_relative_path"] = $path_to_root . $handler_settings["cash_directory_prefix"] . $user_id . '/';

$rel_path = $_SESSION["cash_directory_relative_path"];

if(is_dir($rel_path)){
    deleteDir($rel_path);
}

if(!mkdir($rel_path)){
    die("Ошибка на сервере. Невозможно создать директрию (filehandler/upload.php)");
}

$_SESSION["currentfile"] = $_FILES['new_document']['name'];

if (move_uploaded_file($_FILES['new_document']['tmp_name'], $rel_path . $_FILES['new_document']['name'])) {
    header('Location: unpack.php');
} else {
    die("Ошибка на сервере. Не удалось загрузить файл (filehandler/upload.php)");
}


?>