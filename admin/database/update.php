<?php

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user/connect.php';


function update_register(){
    global $connect;

    if ($data = mysqli_query($connect, "SELECT * FROM register")){
        $numRows = mysqli_num_rows($data);
        if ($numRows){
            $connect->query("TRUNCATE TABLE register;");
        }
    } else {
        return [];
    }

    $path = download_file("https://minjust.gov.ru/uploaded/files/perechen-inostrannyih-i-mezhdunarodnyih-nepravitelstvennyih-1202.docx");
    
    if (!unzip($path, "unzipped")){
        return "Ошибка! Не удается загрузить реестр";
    }

    $register = array();

    $xml = simplexml_load_file("unzipped/word/document.xml", null, 0, 'w', true);
    foreach($xml->body->tbl->tr as $elem){
        $full_name = "";
        foreach($elem->tc[3]->p->r as $paragraph){
            $full_name = $full_name . (string)$paragraph->t;
        }
        array_push($register, $full_name);
        $register = array_merge($register, make_short_names($full_name));
    }


    return $register;
}

$names = update_register();
foreach($names as $name){
    mysqli_query($connect, "INSERT INTO `register` (`name`) VALUES ('$name')");
    echo $name . "<br><br>";
}

//var_dump($dom);
