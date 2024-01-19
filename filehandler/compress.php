сжатие архива
<?php

session_start();

function make_docx()
{
    //$_SESSION["cash_directory_relative_path"] . $_SESSION["currentfile"]
    $dir = $_SESSION["cash_directory_relative_path"] . $_SESSION["unzip_folder_name"];
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