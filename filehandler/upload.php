загрузка на сервер...
<?php

require_once 'FileHandler.php';

$fh = new FileHandler;
$fh->handle();


die();

session_start();
require_once 'settings.php';

$REQUESTSLIMIT = 10;

if (!isset($_SESSION["requests"])) $_SESSION["requests"] = 0;
$_SESSION["requests"] += 1;
if ($_SESSION["requests"] > $REQUESTSLIMIT && !isset($_SESSION["user"])){
    header('Location: ../pages/limit.php');
    exit();
}

unset($_SESSION["file"]);

// function deleteDir(string $dir): void
// {
//     $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
//     $files = new RecursiveIteratorIterator(
//         $it,
//         RecursiveIteratorIterator::CHILD_FIRST
//     );
//     foreach ($files as $file) {
//         if ($file->isDir()) {
//             rmdir($file->getPathname());
//         } else {
//             unlink($file->getPathname());
//         }
//     }
//     rmdir($dir);
// }

$path_to_root = "../";
$user_id = $_SESSION["user"]["id"];
$_SESSION["file"]["cash_directory_relative_path"] = $path_to_root . $handler_settings["cash_directory_prefix"] . $user_id . '/';

$rel_path = $_SESSION["file"]["cash_directory_relative_path"];

if (is_dir($rel_path)) {
    deleteDir($rel_path);
}

if (!mkdir($rel_path)) {
echo $rel_path;
    die("Ошибка на сервере. Невозможно создать директрию (filehandler/upload.php)");
}

$_SESSION["file"]["currentfile"] = $_FILES['new_document']['name'];

if (move_uploaded_file($_FILES['new_document']['tmp_name'], $rel_path . $_FILES['new_document']['name'])) {
    header('Location: unpack.php');
} else {
    die("Ошибка на сервере. Не удалось загрузить файл (filehandler/upload.php)");
}


?>
