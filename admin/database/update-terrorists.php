<?php


require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user/connect.php';


function get_terrorists(){
    $url = "http://www.fsb.ru/fsb/npd/terror.htm";
    $register = array();

    $html = file_get_contents($url);

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $tables = $dom->getElementsByTagName("tbody");
    $id = 0;

    foreach ($tables as $tb){
        $rows = $tb->getElementsByTagName("tr");
        for($i = 1; $i < count($rows); $i++){
            $row = $rows[$i];
            if (isset($row->getElementsByTagName("td")[1]->textContent)){
                $full_name = $row->getElementsByTagName("td")[1]->textContent;
                array_push($register, $full_name);
                $register = array_merge($register, make_short_names($full_name));
                $register = array_merge($register, get_english_substr($full_name));
            }
        }
    }
    return $register;
}

?>