<?php

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';


function make_short_names($full_name){

    //текст заключенный в ковычки "" и «» является коротким именем

    $quotes = array('"', '«', '»');
    $short_names = array();
    $flag = false;
    $str = "";
    if (in_array($full_name[0], $quotes)){
        $flag = !$flag;
    }
    foreach (mb_str_split($full_name) as $char){
        if ($flag){
            $str = $str . $char;
        } 
        if (in_array($char, $quotes)){
            $flag = !$flag;
            if ($flag) $str = $str . $char;
        }
        if (!$flag && $str){
            array_push($short_names, $str);
            $str = "";
        }
    }

    return $short_names;
}


function update_register(){
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
            //$short_name = explode("\"", $name);
            //$name;
            //echo $short_name[0];
            //var_dump((string)$elem->tc[3]->p->r->t);
        }
        array_push($register, $full_name);
        $register = array_merge($register, make_short_names($full_name));
        // echo $full_name;
        // // $short_name = preg_replace('/\((.+?)\)/i', '()', $full_name);
        // // echo "<br><br>" . $short_name;
        // var_dump(make_short_names($full_name));
        // echo "<br><br><br><br><br><br><br><br>";
    }


    return $register;
}

$names = update_register();
foreach($names as $name){
    echo $name . "<br><br>";
}

//var_dump($dom);
