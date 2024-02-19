<?php

require '../../vendor/autoload.php';

function download_file($url){
    $file_name = explode("/", $url);
    $file_name = end($file_name);
    $arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
        ),
    ); 
    if (file_put_contents($file_name, file_get_contents($url, false, stream_context_create($arrContextOptions)))) { 
        return $file_name;
    } else { 
        return false;
    } 
}

function parse_docx_table($path){
    if ($phpWord = \PhpOffice\PhpWord\IOFactory::load($path)){

        var_dump($phpWord->);
    } else {
        return 0;
    }

}


$curl = curl_init("https://minjust.gov.ru/ru/documents/7756/");
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
ob_start();
$html = curl_exec($curl);
ob_end_clean();
curl_close($curl);

echo $html;

$dom = new domDocument;
$dom->loadHTML("https://minjust.gov.ru/ru/documents/7756/");

$table_class = "table-bordered";

// $xpath = new DomXPath($dom);
// $nodes = $finder->query("//*[contains(@class, '')]");
// $list = $finder->query("//*[contains(@class, '$classname')]");
$list = $dom->getElementsByTagName('html');

var_dump(parse_url("https://minjust.gov.ru/ru/documents/7756/"));

$path = download_file("https://minjust.gov.ru/uploaded/files/perechen-inostrannyih-i-mezhdunarodnyih-nepravitelstvennyih-1202.docx");
parse_docx_table($path);

//var_dump($dom);
