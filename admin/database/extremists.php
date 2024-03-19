<?php 

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user/connect.php';


function get_extremists(){
    $url = "https://minjust.gov.ru/ru/documents/7822/";
    $register = array();

    $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );  
    $html = file_get_contents($url, false, stream_context_create($arrContextOptions));

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $tables = $dom->getElementsByTagName("tbody");
    $finder = new DomXPath($dom);
    $tableClass="doc";
    $tables = $finder->query("//*[contains(@class, '$tableClass')]");

    foreach ($tables as $tb){
        $rows = $tb->getElementsByTagName("p");
        foreach($rows as $row){
            $name = $row->textContent . "<br>";
            $register = array_merge($register, make_short_names($name));
        }
    }
    return $register;
}

?>