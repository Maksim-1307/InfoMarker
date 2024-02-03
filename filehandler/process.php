Обработка файла

<?php

session_start();


$colors = array();
$paragraph_coins = array();

function nice_color($color)
{
    $mindeviation = 20;
    $average = ($color[0] + $color[1] + $color[2]) / 3;
    for ($i = 0; $i < 3; $i++) {
        if (abs($color[$i] - $color[($i + 2) % 3]) <= $mindeviation) {
            return false;
        }
    }
    if ((255 * 3 - ($color[0] + $color[1] + $color[2])) < 200) { //32 * 3 - ($color[0] + $color[1] + $color[2]) < 32
        return true;
    } else {
        return false;
    }
    return true;
}

function genColor()
{
    global $colors;
    // from 0 to 32 for each channel
    $min = 0;
    $max = 255;
    $factor = 8;
    $color = array(mt_rand($min, $max), mt_rand($min, $max), mt_rand($min, $max));
    while (!nice_color($color)) {
        $color = array(mt_rand($min, $max), mt_rand($min, $max), mt_rand($min, $max));
    }
    $color = sprintf("#%02x%02x%02x", $color[0], $color[1], $color[2]);
    while(in_array($color, $colors)){
        $color = genColor();
    }
    return $color;
}

function uniqColor(){
    global $colors;
    $color = sprintf("#%02x%02x%02x", mt_rand(180, 240), mt_rand(180, 240), mt_rand(180, 240));
    // while (in_array($color, $colors)){
    //     $color = sprintf("#%02x%02x%02x", mt_rand(180, 220), mt_rand(0, 255), mt_rand(0, 255));
    // }
    array_push($colors, $color);
    return $color;
}

//test color funcution
function getColor($word){
    if (isset($_SESSION["coinsidences"][$word])){
        if (isset($_SESSION["coinsidences"][$word]["color"])){
            return $_SESSION["coinsidences"][$word]["color"];
        } else {
            $color = genColor();
            $_SESSION["coinsidences"][$word]["color"] = $color;
            return $color;
        }
    } else {
        die("Coinsidence not found in session array");
    }
}

function is_punctuation($t){
    $text = trim($t, " !?.,;:");
    return strlen($text) == 0;
}

function saveCoincidences($text){
    global $paragraph_coins;
    $coinsidences = [];

    $forbidden_words = array("Lorem", "adipisicing", "repellendus", "tempore", "repellat", "test", "corrupti", "amet");
    $paragraph_coins = [];
    foreach ($forbidden_words as $word){
        $indices = coincidencesByName($text, $word);
        if (count($indices)){
            $color = getColor($word);
            $paragraph_coins[$word] = array($indices, $color);
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
    $MINCOINS = 0.75;

    $result = [];
    $words = explode(' ', $str1);
    $words2 = explode(' ', $str2);
    $count = count($words2);
    $wordsCount = count($words);
    //$_SESSION["coinsidences"] = []

    if (!isset($_SESSION["coinsidences"][$str2])){
        $_SESSION["coinsidences"][$str2] = [];
    }

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
                    $_SESSION["coinsidences"][$str2]["count"] += 1;
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

    global $paragraph_coins;

    $path_to_document = "word/document.xml";
    $XMLfile_path = $_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["unzip_folder_name"] . '/' . $path_to_document;
    $xml_document = simplexml_load_file($XMLfile_path, null, 0, 'w', true);
    $body = $xml_document->body;

    foreach($body->p as $paragraph){
        saveCoincidences(extract_text($paragraph));
        $coinsidences = $paragraph_coins;
        split_paragraph($paragraph);
        
        if (count($coinsidences) > 0){
            
            foreach($coinsidences as $coins){

                $i = 0;
                foreach ($paragraph->r as $segment) {
                    if (in_array($i, $coins[0])) {
                        //print_r($coins[0]);
                        unset($segment->rPr->highlight);
                        $segment->rPr->addChild("w:highlight w:val=\"" . $coins[1] . "\"");
                    }
                    $i += 1;
                }

            }
        }
    } 

    return $xml_document->asXML($XMLfile_path);

}

unset($_SESSION["coinsidences"]);
if(process_xml()){
//
    //die();
    header('Location: compress.php');
    //print_r($paragraph_coins);
}

?>