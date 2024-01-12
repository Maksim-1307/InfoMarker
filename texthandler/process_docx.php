<?php

session_start();

if(isset($_SESSION['currentfile'])){
    $zip = new ZipArchive; // creating object of ZipArchive class.
    $sUploadedFile = $_SESSION['currentfile'];
    $zip->open("../files/$sUploadedFile");
    $aFileName = explode('.', $sUploadedFile);
    $sDirectoryName = current($aFileName);

    if (!is_dir("../files/$sDirectoryName")) {
        mkdir("../files/$sDirectoryName");
        $zip->extractTo("../files/$sDirectoryName");
        copy("../files/$sDirectoryName/word/document.xml", "../files/$sDirectoryName.xml");

        $xml = simplexml_load_file("../files/$sDirectoryName.xml");
        // $xml->registerXPathNamespace('w', "http://schemas.openxmlformats.org/wordprocessingml/2006/main");
        // $text = $xml->xpath('//w:t');

        // echo '<pre>';
        // print_r($text);
        // echo '</pre>';

        // rrmdir("../files/$sDirectoryName");
    } else {
        make_docx("../files/$sDirectoryName");
    }

    function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
    //header('Location: ../../index.php');
} else {
    echo "Ошибка на сервере. \$_SESSION['currentfile'] не определена. перенаправить куда-нибудь";
}

function get_files_reqursive($path){
    $files_array = array();
    $files = array_diff(
        scandir($path),
        array('.', '..')
    );
    foreach($files as $file){
        if (is_dir($path . "/" . $file)){
            array_push($files_array, get_files_reqursive($path . "/" .$file));
        } else {
            array_push($files_array, $path . $file);
        }
    }
    return $files_array;
}

function make_docx($path)
{
    $zip = new ZipArchive;
    $zip->open('../files/testttv.docx', ZipArchive::CREATE);

    $options = array('remove_path' => '../files/test');
    $zip->addGlob('../files/test/**/*.*', 0, $options);
    $zip->addGlob('../files/test/*.*', 0, $options);
    $zip->addGlob('../files/test/_rels/.rels', 0, $options);

    $zip->close();
}