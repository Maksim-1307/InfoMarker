сжатие архива
<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

function make_docx()
{
    //$_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["currentfile"]
    $dir = $_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["unzip_folder_name"];
    $zip = new ZipArchive;
    $zip->open($dir . ".docx", ZipArchive::CREATE | ZipArchive::OVERWRITE);
                            
    echo $dir;                  

    $options = array('remove_path' => $dir);
    $zip->addGlob($dir . '/**/*.*', 0, $options);
    $zip->addGlob($dir . '/*.*', 0, $options);
    $zip->addGlob($dir . '/_rels/.rels', 0, $options);

    $zip->close();
}

make_docx();
header('Location: ../pages/file.php');

?>