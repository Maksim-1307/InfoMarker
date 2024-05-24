<?php

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user/connect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/database/update-inoagents.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/database/update-terrorists.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/database/extremists.php';


class RegisterUpdater {

    public function __construct() {
        
    }

    // юрлицо иди физлицо
    public static function is_person($str){
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

    public static function get_surname($FIO){
        return explode(' ', $FIO)[0];
    }

    public static function parse_inoagents(){

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
            $rows = $tb->getElementsByTagName("tr");
            foreach($rows as $row){
                if (isset($row->getElementsByTagName("td")[0]->textContent)){
                    $name = $row->getElementsByTagName("td")[0]->textContent;
                    array_push($register, $name);
                    // if (RegisterUpdater::is_person($name)) {
                    //     array_push($register, $name);
                    //     array_push($register, RegisterUpdater::get_surname($name));
                    // } else {
                    //     array_push($register, $name);
                    //     $register = array_merge($register, make_short_names($name));
                    //     $register = array_merge($register, get_english_substr($name));
                    // }
                }
            }
            $id++;
        }
        return $register;
    }



    public static function parse_extremists(){
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
                $name = $row->textContent;
                $i = 1;
                while(is_numeric(mb_substr($name, 0, $i))){
                    $i++;
                }
                $name = mb_substr($name, $i-1);
                array_push($register, $name);
                // $register = array_merge($register, make_short_names($name));
                // $register = array_merge($register, get_english_substr($name));
            }
        }
        return $register;
    }



    public static function parse_terrorists(){

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
                    // $register = array_merge($register, make_short_names($full_name));
                    // $register = array_merge($register, get_english_substr($full_name));
                }
            }
        }
        return $register;

    }




    public static function parse_organizations(){

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
            //$register = array_merge($register, make_short_names($full_name));
            //$register = array_merge($register, get_english_substr($full_name));
        }

        unset($register[0]);


        return $register;

    }
    public static function parse_all(){
        $register = array_merge(RegisterUpdater::parse_inoagents(), RegisterUpdater::parse_extremists(), RegisterUpdater::parse_terrorists(), RegisterUpdater::parse_organizations());
        return $register;
    }

    public static function get_name_by_id($id){
        global $connect;
        $data = mysqli_query($connect, "SELECT * FROM register WHERE id = '$id'");
        $array = [];
        while ($row = mysqli_fetch_assoc($data)) {
            $array[] = $row;
        }
        if (count($array)) return $array[0];
        return false;
    }

    public static function generate_variants($id){
        $name = RegisterUpdater::get_name_by_id($id);
        $variants = [];
        if (RegisterUpdater::is_person($name)) {
            array_push($variants, $name);
            array_push($variants, RegisterUpdater::get_surname($name));
        } else {
            array_push($variants, $name);
            $variants = array_merge($variants, make_short_names($name));
            $variants = array_merge($variants, get_english_substr($name));
        }
        return $variants;
    }

    // to (id) variants (array of strings)
    public static function add_variants($to, $variants){

    }

    public static function update(){
        global $connect;
        $data = RegisterUpdater::parse_all();
        foreach ($data as $name) {
            $request = "SELECT * FROM register WHERE name = '$name'";
            $result = $connect->query($request);
            if ($result->num_rows == 0) {
                if () mysqli_query($connect, "INSERT INTO `unhandled` (`name`) VALUES ('$name')");
            }
        }
    }


}


print_array(RegisterUpdater::update());


// function update_register(){
    
// }

// $names = array_merge(parse_inoagents(), update_register());
// $names = array_merge($names, get_terrorists());
// $names = array_merge($names, get_extremists());
// foreach($names as $name){
//     mysqli_query($connect, "INSERT INTO `register` (`name`) VALUES ('$name')");
//     echo $name . "<br><br>";
// }






//var_dump($dom);



// if is preson: get surname
// else: get short names, get english substr