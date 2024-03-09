<?php


require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user/connect.php';

function get_surname($FIO){
    return explode(' ', $FIO)[0];
}

function parse_inoagents(){
    $url = "https://ru.wikipedia.org/wiki/Список_иностранных_агентов_(Россия)";
    $register = array();

    $html = file_get_contents($url);

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $tables = $dom->getElementsByTagName("tbody");
    $id = 0;
    // массив с id таблиц с физ. лицами
    $individualsTablesIDs = array(3);
    foreach ($tables as $tb){
        $id++;
        $rows = $tb->getElementsByTagName("tr");
        foreach($rows as $row){
            if (isset($row->getElementsByTagName("td")[1]->textContent)){
                $name = $row->getElementsByTagName("td")[1]->textContent;
                if (in_array($id, $individualsTablesIDs)) {
                    array_push($register, $name);
                    array_push($register, get_surname($name));
                } else {
                    $register = array_merge($register, make_short_names($name));
                }
            }
        }
    }
    return $register;
}

// $inoagents = parse_inoagents();
// foreach ($inoagents as $name){
//     echo $name . "<br>";
// }

// $xml = simplexml_load_string($html);
// $table = $xml->body->

// $pages->xpath('/pages/page[@id = "whatis"]');

//echo $table;

?>