Открытие файла

<?php

    session_start();

    require_once 'settings.php';

    function deleteDir(string $dir): void
    {
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator(
            $it,
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($dir);
    }

    $zip = new ZipArchive;
    $zip->open($_SESSION["cash_directory_relative_path"] . $_SESSION["currentfile"]);

    $fileFullName = $_SESSION["currentfile"];
    $aFileName = explode('.', $fileFullName)[0];

    $_SESSION["unzip_folder_name"] = $aFileName; 

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
        //die();
        header('Location: process.php');
    }
    

    //header('Location: process.php');

    // 
    // $sDirectoryName = $_SESSION["cash_directory_relative_path"];

?>