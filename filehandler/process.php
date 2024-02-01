Обработка файла

<?php

session_start();


function getColor(){
    return "#FFFFFF";
}

function is_punctuation($t){
    $text = trim($t, " !?.,;:");
    return strlen($text) == 0;
}

function saveCoincidences($text){
    $coinsidences = [];

    $forbidden_words = array("Lorem", "adipisicing", "repellendus", "tempore", "repellat", "test", "corrupti", "amet");
    $_SESSION["coinsidences"] = [];
    foreach ($forbidden_words as $word){
        $indices = coincidencesByName($text, $word);
        if (count($indices)){
            $color = getColor();
            $_SESSION["coinsidences"][$word] = array($indices, $color);
        }
    }
    return $coinsidences;
}

function coincidence($str1, $str2){
    $len = (float)max(strlen($str1), strlen($str2));
    $lev = (float)levenshtein($str1, $str2);
    return (float)((float)($len - $lev) / (float)$len);
}

function coincidencesByName($str1, $str2){
    $MINCOINS = 0.9;

    $result = [];
    $words = explode(' ', $str1);
    $words2 = explode(' ', $str2);
    $count = count($words2);
    $wordsCount = count($words);
    //$_SESSION["coinsidences_count"] = [];

    for ($i = 0; $i < $wordsCount; $i++){
        $word = "";
        $coins = $MINCOINS;
        $res = [];
        for ($j = 0; $j < $count; $j++){
            $word = $words2[$j];
            $coins2 = coincidence($word, $words[$i + $j]);
            if ($coins2 >= $coins){
                $coins = $coins2;
                if ($coins >= $MINCOINS) {
                    array_push($result, $i + $j);
                    $_SESSION["coinsidences_count"][$str2] += 1;
                }
            }
        }
    };
    return $result;
}

function split_paragraph($paragraph){
    $segments_array = $paragraph->r;
    $segments_count = count($segments_array);

    $no_space_characters = array('.', ',', '!', '!', ':', ';');

    for ($i = 0; $i < $segments_count; $i++) {
        $segment_text = (string)($segments_array[0]->t);
        // вот тут не работает как надо
        $segment_words = explode(" ", $segment_text);
        //print_r($segment_words);
        $styles_tag = $segments_array[0]->rPr;
        for ($w = 0; $w < count($segment_words); $w++) {
            $word = $segment_words[$w];
            //print_r(explode(" ", $word));
            if ($word) {
                if ($w < count($segment_words)-1 && is_punctuation($segment_words[$w + 1])){
                    $next_word = $segment_words[$w+1];
                    $word = $word . $next_word;
                    $segment_words[$w+1] = "";
                }
                $word_object = $paragraph->addChild("r");
                sxml_append($word_object, $styles_tag);
                $text = $word_object->addChild("t", $word . " ");
                $text->addAttribute("xml:space", "preserve", "xml");
            }
        }
        unset($segments_array[0]);
    }
}

function extract_text(SimpleXMLElement $p){
    $text = "";
    foreach ($p->r as $r){
        $text = $text . (string)($r->t);
    }
    return $text;
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
        saveCoincidences(extract_text($paragraph));
        $coinsidences = $_SESSION["coinsidences"];
        print_r($coinsidences);
        split_paragraph($paragraph);
        
        if (count($coinsidences) > 0){
            
            foreach($coinsidences as $coins){

                $i = 0;
                foreach ($paragraph->r as $segment) {
                    if (in_array($i, $coins[0])) {
                        //print_r($coins[0]);
                        unset($segment->rPr->highlight);
                        $segment->rPr->addChild("w:highlight w:val=\"yellow\"");
                    }
                    $i += 1;
                }

            }
        }
    } 

    return $xml_document->asXML($XMLfile_path);

}

if(process_xml()){
//
    //die();
    header('Location: compress.php');
    //print_r($_SESSION["coinsidences"]);
}

?>