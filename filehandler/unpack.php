Открытие файла

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

    $zip = new ZipArchive;
    $zip->open($_SESSION["cash_directory_relative_path"] . $_SESSION["currentfile"]);

    $fileFullName = $_SESSION["currentfile"];
    $aFileName = explode('.', $fileFullName)[0];

    $extractDir = $_SESSION["cash_directory_relative_path"] . $aFileName;

    if (is_dir($extractDir)) {
        deleteDir($extractDir);
    }

    if (!mkdir($extractDir)){
        die("Не удалось открыть файл");
    }

    if (!($zip->extractTo($extractDir))){
        die("Не удалось открыть файл");
    } else {
        header('Location: process.php');
    }
    

    //header('Location: process.php');

    // 
    // $sDirectoryName = $_SESSION["cash_directory_relative_path"];

?>