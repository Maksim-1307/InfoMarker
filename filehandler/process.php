Обработка файла

<?php

session_start();

function get_concidences($string, $word){
    return array(1, 2, 10);
}

function process_xml(){

    $path_to_document = "word/document.xml";
    $XMLfile_path = $_SESSION["cash_directory_relative_path"] . $_SESSION["unzip_folder_name"] . '/' . $path_to_document;
    $xml_document = simplexml_load_file($XMLfile_path, null, 0, 'w', true);
    $body = $xml_document->body;

    foreach($body->p as $paragraph){
        $i = 0;
        $marked_words = get_concidences("", "");
        foreach($paragraph->r as $word){
            if (in_array($i, $marked_words)){
                $word->rPr->addChild("w:highlight w:val=\"yellow\"");
            }
            $i += 1;
        }
    } 

    return $xml_document->asXML($XMLfile_path);

}

if(process_xml()){
    header('Location: compress.php');
}


?>