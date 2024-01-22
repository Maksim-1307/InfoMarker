Обработка файла

<?php

session_start();

function get_concidences($string, $word){
    return array(3, 4, 5, 15, 20);
}

function split_paragraph($paragraph){
    $segments_array = $paragraph->r;
    $segments_count = count($segments_array);

    for ($i = 0; $i < $segments_count; $i++) {
        $segment_text = (string)($segments_array[0]->t);
        $segment_words = explode(" ", $segment_text);
        $styles_tag = $segments_array[0]->rPr;
        var_dump($styles_tag);
        foreach ($segment_words as $word) {
            if ($word) {
                echo "<br>";
                $word_object = $paragraph->addChild("r");
                sxml_append($word_object, $styles_tag);
                $text = $word_object->addChild("t", $word . " ");
                $text->addAttribute("xml:space", "preserve", "xml");
            }
        }
        unset($segments_array[0]);
    }
}

function sxml_append(SimpleXMLElement $to, SimpleXMLElement $from)
{
    if (count($from)){
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom->cloneNode(true), true));
    }
}

function process_xml(){

    $path_to_document = "word/document.xml";
    $XMLfile_path = $_SESSION["cash_directory_relative_path"] . $_SESSION["unzip_folder_name"] . '/' . $path_to_document;
    $xml_document = simplexml_load_file($XMLfile_path, null, 0, 'w', true);
    $body = $xml_document->body;

    foreach($body->p as $paragraph){
        $marked_words = get_concidences("", "");
        //$remove_queue = array();
        if (count($marked_words) > 0){
            
            split_paragraph($paragraph);

            $i = 0;
            foreach ($paragraph->r as $segment) {
                if (in_array($i, $marked_words)) {
                    $segment->rPr->addChild("w:highlight w:val=\"yellow\"");
                }
                $i += 1;
            }
        }
    } 

    return $xml_document->asXML($XMLfile_path);

}

if(process_xml()){
    header('Location: compress.php');
}
// process_xml();


?>