<?php 

session_start();

require_once 'settings.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/docx2html/DocxToHtml.php';

set_time_limit(86400);

class FileHandler{

    public $html = "";
    public $report = [];

    public $colors = array();
    public $paragraph_coins = array();
    public $document_coins = array();
    public $register_list = array();

    public function get_html(){
        if (!$this->html) {
            $this->handle();
        }
        return $this->html;
    }

    public function handle(){

        // REMAKE!!!
        $handler_settings = [
            "cash_directory_prefix" => "user_cash_"
        ];


        // создание папки кеша, запись в сессию

        unset($_SESSION["file"]);

        $path_to_root = $_SERVER['DOCUMENT_ROOT'] . '/';
        $user_id = $_SESSION["user"]["id"];
        $_SESSION["file"]["cash_directory_relative_path"] = $path_to_root . $handler_settings["cash_directory_prefix"] . $user_id . '/';

        $rel_path = $_SESSION["file"]["cash_directory_relative_path"];

        if (is_dir($rel_path)) {
            //echo "dir should be deleted line 56" . $rel_path;
            deleteDir($rel_path);
        }

        if (!mkdir($rel_path)) {
            die("Ошибка на сервере. Невозможно создать директрию (filehandler/upload.php)");
        }

        $_SESSION["file"]["currentfile"] = reset($_FILES)['name'];




        // перемещение файла 

        if (move_uploaded_file(reset($_FILES)['tmp_name'], $rel_path . reset($_FILES)['name'])) {
            //header('Location: unpack.php');
        } else {
            die("Ошибка на сервере. Не удалось загрузить файл (filehandler/upload.php)");
        }


        // работа с сессей

        $fileFullName = $_SESSION["file"]["currentfile"];
        $aFileName = explode('.', $fileFullName)[0];
        $_SESSION["file"]["unzip_folder_name"] = $aFileName;
        $extractDir = $_SESSION["file"]["cash_directory_relative_path"] . $aFileName;


        // работа с папкой

        if (is_dir($extractDir)) {
            deleteDir($extractDir);
        }
        if (!mkdir($extractDir)) {
            die("Не удалось открыть файл");
        }


        // создание архива

        $zip = new ZipArchive;
        $zip->open($_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["currentfile"]);

        // непосредственно само извлечение

        if (!($zip->extractTo($extractDir))) {
            die("Не удалось извлечь архив");
        } else {
            //header('Location: process.php');
        }




        $this->register_list = $this->get_names_from_db();

        //unset($_SESSION["coinsidences"]);
        $_SESSION["coinsidences"] = [];

        $this->process_xml();
        $this->make_docx();
        save_html($_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["unzip_folder_name"] . ".docx", $_SESSION["file"]["cash_directory_relative_path"] . "content.html");
        // header('Location: ../pages/file.php');
        // print_array($_SESSION);

        foreach ($_SESSION["coinsidences"] as $name => $data) {
            if (!$data){
                unset($_SESSION["coinsidences"][$name]);
            }
        }

        $wordPath = $_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["currentfile"];

        $handler = new Handler($wordPath);
        $this->html = $handler->get_html();
        $this->report = $_SESSION["coinsidences"];


    }

















    public function get_names_from_db(){
        require_once $_SERVER['DOCUMENT_ROOT'] . '/user/connect.php';
        $request = "SELECT `name` FROM register";
        $res = $connect->query($request);
        $this->register_list = array();
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                array_push($this->register_list, $row['name']);
            }
        } else {
        //echo "rows: " . $res->num_rows > 0;
        }
        return $this->register_list;
    }





    public function nice_color($color)
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

    public function genColor()
        {
            // from 0 to 32 for each channel
            $min = 0;
            $max = 255;
            $factor = 8;
            $color = array(mt_rand($min, $max), mt_rand($min, $max), mt_rand($min, $max));
            while (!$this->nice_color($color)) {
                $color = array(mt_rand($min, $max), mt_rand($min, $max), mt_rand($min, $max));
            }
            $color = sprintf("%02x%02x%02x", $color[0], $color[1], $color[2]);
            while(in_array($color, $this->colors)){
                $color = $this->genColor();
            }
            return $color;
        }

    public function setColor($word){
            if (isset($_SESSION["coinsidences"][$word])){
                if (isset($_SESSION["coinsidences"][$word]["color"])){
                    return $_SESSION["coinsidences"][$word]["color"];
                } else {
                    $color = $this->genColor();
                    $_SESSION["coinsidences"][$word]["color"] = $color;
                    return $color;
                }
            } else {
                die("Coinsidence not found in session array");
            }
        }

    public function is_punctuation($t){
            $text = trim($t, " !?.,;:");
            if (strlen($text) == 0){
                return 1;
            } else {
                return 0;
            }
        }

    public function saveCoincidences($text){
            $coinsidences = [];

            $forbidden_words = $this->register_list;
            array_push($this->document_coins, $this->paragraph_coins);
            $this->paragraph_coins = [];
            foreach ($forbidden_words as $word){
                $indices = $this->coincidencesByName($text, $word);
                if (count($indices)){
                    $color = $this->setColor($word);
                    $this->paragraph_coins[$word] = array($indices, $color);
                }
            }
            return $coinsidences;
        }

    public function coincidence($str1, $str2){
            $str1 = make_lowercase($str1);
            $str2 = make_lowercase($str2);
            $len = (float)max(strlen($str1), strlen($str2));
            $lev = (float)levenshtein($str1, $str2);
            if ($len){ 
                return (float)((float)($len - $lev) / (float)$len);
            } else {
                return 0;
            }
        }

    public function coincidencesByName($text, $word) {
        $MINCOINS = 0.75;

        $result = [];
        $textArray = explode(' ', $text);
        $wordArray = explode(' ', $word);
        $textLen = count($textArray);
        $wordLen = count($wordArray);

        if($wordLen > $textLen){
            return [];
        }

        if (!isset($_SESSION["coinsidences"][$word])) {
            $_SESSION["coinsidences"][$word] = [];
            //echo "considences set to empty array";
        }

        $lastCoins = 0;
        for ($i = 0; ($i + $wordLen) <= $textLen; $i++){
            $substr = "";
            for ($j = 0; $j < $wordLen; $j++){
                if ($substr){
                    $substr = $substr . " " . $textArray[$i + $j];
                } else {
                    $substr = $substr . $textArray[$i + $j];
                }
            }
            $coins = $this->coincidence($substr, $word);
            if ($coins >= $lastCoins){
                if ($coins >= $MINCOINS){
                    $lastCoins = $coins;
                }
            } else {
                for($j = $i-1; $j+1 < $i + $wordLen; $j++){
                    array_push($result, $j);
                }
                $lastCoins = 0;
                $i = $i + $wordLen - 1;
                if (!isset($_SESSION["coinsidences"][$word]["count"])) {
                    $_SESSION["coinsidences"][$word]["count"] = 1;
                } else  {
                    $_SESSION["coinsidences"][$word]["count"] += 1;
                }
            }
            //for the last iteration
            if (($i + $wordLen) == $textLen && $lastCoins >= $MINCOINS) {
                for ($j = $i - 1; $j + 1 < $i + $wordLen; $j++) {
                    array_push($result, $j+1);
                }
                if (!isset($_SESSION["coinsidences"][$word]["count"])) {
                    $_SESSION["coinsidences"][$word]["count"] = 1;
                } else  {
                    $_SESSION["coinsidences"][$word]["count"] += 1;
                }
            }
        }

        return $result;
    }

    public function split_paragraph($paragraph){
        $segments_array = $paragraph->r;
        $segments_count = count($segments_array);

        $no_space_characters = array('.', ',', '!', '!', ':', ';');

        if (!isset($segments_array[0]->t)){
            return null;
        }
        $next_segment_text = (string)($segments_array[0]->t);
        $segment_text = "";

        for ($i = 0; $i < $segments_count; $i++) {
            $segment_text = $next_segment_text;
            if (isset($segments_array[1])){
                $next_segment_text = (string)($segments_array[1]->t);
            } else {
                unset($next_segment_text);
            }
            $segment_words = explode(" ", $segment_text);
            $styles_tag = $segments_array[0]->rPr;
            $word_object;
            for ($w = 0; $w < count($segment_words); $w++) {
                $word = $segment_words[$w];
                if ($word) {
                    if ($w+1 < count($segment_words)){
                        if ($this->is_punctuation($segment_words[$w + 1])){
                            $next_word = $segment_words[$w+1];
                            $word = $word . $next_word;
                            unset($segment_words[$w+1]);
                        }
                    } else {
                        if (isset($next_segment_text)){
                            $next_word = explode(" ", $next_segment_text)[0];
                            if ($this->is_punctuation($next_word)) {
                                
                                $word = $word . $next_word;
                                $next_words = explode(" ", $next_segment_text);
                                unset($next_words[0]);
                                $next_segment_text = implode(" ", $next_words);
                            }
                        }
                    }
                    $word_object = $paragraph->addChild("r");
                    //$word_object->addAttribute("w:rsidR", "00711293");
                    if ($styles_tag){
                        $this->sxml_append($word_object, $styles_tag);
                    } else {
                        $word_object->addChild("w:rPr", "");
                    }
                
                    $text = $word_object->addChild("t", $word . " ");
                    $text->addAttribute("xml:space", "preserve", "xml");
                }
            }
            unset($segments_array[0]);
        }
        //die("testtest");
    }

    public function extract_text(SimpleXMLElement $p){
        $text = "";
        foreach ($p->r as $r){
            $text = $text . (string)($r->t);
        }
        return $text;
    }

    public function sxml_append(SimpleXMLElement $to, SimpleXMLElement $from)
    {
        if (count($from)){
            $toDom = dom_import_simplexml($to);
            $fromDom = dom_import_simplexml($from);
            $toDom->appendChild($toDom->ownerDocument->importNode($fromDom->cloneNode(true), true));
        }
    }

    public function make_docx()
    {
        //$_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["currentfile"]
        $dir = $_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["unzip_folder_name"];
        $zip = new ZipArchive;
        $zip->open($dir . ".docx", ZipArchive::CREATE | ZipArchive::OVERWRITE);

        //echo $dir;

        $options = array('remove_path' => $dir);
        $zip->addGlob($dir . '/**/*.*', 0, $options);
        $zip->addGlob($dir . '/*.*', 0, $options);
        $zip->addGlob($dir . '/_rels/.rels', 0, $options);

        $zip->close();
    }

    public function process_xml(){

        $path_to_document = "word/document.xml";
        $XMLfile_path = $_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["unzip_folder_name"] . '/' . $path_to_document;
        $xml_document = simplexml_load_file($XMLfile_path, null, 0, 'w', true);
        $body = $xml_document->body;

        foreach($body->p as $paragraph){
            $this->saveCoincidences($this->extract_text($paragraph));
            $coinsidences = $this->paragraph_coins;
            $this->split_paragraph($paragraph);

            
            
            if (count($coinsidences) > 0){

                foreach($coinsidences as $coins){

                    $i = 0;
                    foreach ($paragraph->r as $segment) {
                        if (in_array($i, $coins[0])) {
                            unset($segment->rPr->highlight);
                            //$segment->rPr->addChild("w:highlight w:val=\"" . $coins[1] . "\"");
                            $glow = $segment->rPr->addChild("w14:glow", null, "http://schemas.microsoft.com/office/word/2010/wordml");
                            $glow->addAttribute("w14:rad", "250000", "http://schemas.microsoft.com/office/word/2010/wordml");
                            $glow->addChild("w14:srgbClr w14:val=\"". $coins[1] ."\"");
                            /*
                                <w14:glow w14:rad="254000">
                                    <w14:srgbClr w14:val="54FFC6" />
                                </w14:glow>
                            */
                        }
                        $i += 1;
                    }

                }
            }
        } 

        return $xml_document->asXML($XMLfile_path);

    }

    // public function process_html(){

    //     $dom = new DOMDocument();
    //     $path = $_SESSION["file"]["cash_directory_relative_path"] . "content.html";
    //     $html = file_get_contents($path);
    //     if (!$html) return 0;
    //     $dom->loadHTML('<?xml encoding="UTF-8">' . $html);

    //     $elements = $dom->getElementsByTagName("p");
    //     for ($i = 0; $i < count($elements); $i++){
    //         $paragraph = $elements[$i];
    //         $words = $paragraph->getElementsByTagName("span");

    //         if ($i+1 < count($this->document_coins)){
    //             $coinsidences = $this->document_coins[$i+1];
    //         } else {
    //             $coinsidences = null;
    //         }

    //         if ($coinsidences){
    //             foreach ($coinsidences as $coin) {
    //                 $j = 0;
    //                 foreach($words as $word){
    //                     if (in_array($j, $coin[0])){
    //                         $styles = $word->getAttribute("style");
    //                         $word->setAttribute("style", $styles . "background-color: #". $coin[1] .";");
    //                     }
    //                     $j++;
    //                 }
    //             }
    //         }
    //     }
    //     $html = $dom->saveHTML();
    //     //file_put_contents($path, $html);
    //     $file = fopen($path, "w");
    //     fwrite($file, $html);
    //     fclose($file);
    //     //echo $dom->saveHTML();
    // }
        
}

?>