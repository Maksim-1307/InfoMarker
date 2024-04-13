<?php


require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user/connect.php';

function get_surname($FIO){
    return explode(' ', $FIO)[0];
}

// юрлицо иди физлицо
function is_person($str){
    $exploded = explode(' ', $str);
    if (count($exploded) != 3){
        return false;
    }
    if (strpos($str, '"') || strpos($str, '«') || strpos($str, '»')){
        return false;
    }
    foreach($exploded as $word){
        if (!is_upper(mb_substr($word, 0, 1))){
            return false;
        }
    }
    return true;
}

function parse_inoagents(){
    $url = "https://gogov.ru/articles/inagenty-21apr22";
    $register = array();

    $html = file_get_contents($url);

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $tables = $dom->getElementsByTagName("tbody");
    $id = 0;
    // массив с id таблиц с физ. лицами
    //$individualsTablesIDs = array(3, 4);
    foreach ($tables as $tb){
        $id++;
        $rows = $tb->getElementsByTagName("tr");
        foreach($rows as $row){
            if (isset($row->getElementsByTagName("td")[0]->textContent)){
                $name = $row->getElementsByTagName("td")[0]->textContent;
                if (is_person($name)) {
                    array_push($register, $name);
                    array_push($register, get_surname($name));
                } else {
                    array_push($register, $name);
                    $register = array_merge($register, make_short_names($name));
                    $register = array_merge($register, get_english_substr($name));
                }
            }
        }
    }
    return $register;
}

?>