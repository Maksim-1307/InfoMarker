<?php


require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user/connect.php';

function update_inoagents(){
    $url = "https://ru.wikipedia.org/wiki/Список_иностранных_агентов_(Россия)";

    // $html = curl_init($url);

    // curl_exec($html);
    // curl_close($html);

    $html = file_get_contents($url);

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $tables = $dom->getElementsByTagName("tbody");
    $id = 0;
    // массив с id таблиц с физ. лицами
    $individualsTablesIDs = array(2, 3);
    foreach ($tables as $tb){
        echo "<br><br>------------  TABLE ID: " . $id . "  ---------------<br><br>";
        $id++;
        $rows = $tb->getElementsByTagName("tr");
        foreach($rows as $row){
            if (isset($row->getElementsByTagName("td")[1]->textContent)){
                $name = $row->getElementsByTagName("td")[1]->textContent;
                echo $name;
                echo "<br><br><br><br><br><br>";
            }
        }
    }
}
// $xml = simplexml_load_string($html);
// $table = $xml->body->

// $pages->xpath('/pages/page[@id = "whatis"]');

//echo $table;

?>