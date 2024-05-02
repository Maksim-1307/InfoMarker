<?php 

session_start();

require_once 'settings.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';


// REMAKE !!!
$colors = array();
$paragraph_coins = array();
$document_coins = array();
$register_list = array();

class FileHandler{

    public function handle(){

        global $register_list;

        // REMAKE!!!
        $handler_settings = [
            "cash_directory_prefix" => "user_cash_"
        ];

        // обявление функций

        // function deleteDir(string $dir): void
        // {
        //     $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        //     $files = new RecursiveIteratorIterator(
        //         $it,
        //         RecursiveIteratorIterator::CHILD_FIRST
        //     );
        //     foreach ($files as $file) {
        //         if ($file->isDir()) {
        //             rmdir($file->getPathname());
        //         } else {
        //             unlink($file->getPathname());
        //         }
        //     }
        //     rmdir($dir);
        // }

        // лимит запросов

        $REQUESTSLIMIT = 10;

        if (!isset($_SESSION["requests"])) $_SESSION["requests"] = 0;
        $_SESSION["requests"] += 1;
        if ($_SESSION["requests"] > $REQUESTSLIMIT && !isset($_SESSION["user"])){
            header('Location: ../pages/limit.php');
            exit();
        }




        // создание папки кеша, запись в сессию

        unset($_SESSION["file"]);

        $path_to_root = "../";
        $user_id = $_SESSION["user"]["id"];
        $_SESSION["file"]["cash_directory_relative_path"] = $path_to_root . $handler_settings["cash_directory_prefix"] . $user_id . '/';

        $rel_path = $_SESSION["file"]["cash_directory_relative_path"];

        if (is_dir($rel_path)) {
            //echo "dir should be deleted line 56" . $rel_path;
            deleteDir($rel_path);
        }

        if (!mkdir($rel_path)) {
        echo $rel_path;
            die("Ошибка на сервере. Невозможно создать директрию (filehandler/upload.php)");
        }

        $_SESSION["file"]["currentfile"] = $_FILES['new_document']['name'];




        // перемещение файла 

        if (move_uploaded_file($_FILES['new_document']['tmp_name'], $rel_path . $_FILES['new_document']['name'])) {
            //header('Location: unpack.php');
        } else {
            die("Ошибка на сервере. Не удалось загрузить файл (filehandler/upload.php)");
        }


        // обявление функций

        // function deleteDir(string $dir): void
        // {
        //     $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        //     $files = new RecursiveIteratorIterator(
        //         $it,
        //         RecursiveIteratorIterator::CHILD_FIRST
        //     );
        //     foreach ($files as $file) {
        //         if ($file->isDir()) {
        //             rmdir($file->getPathname());
        //         } else {
        //             unlink($file->getPathname());
        //         }
        //     }
        //     rmdir($dir);
        // }

        // function unzip($from, $to){
        //     $zip = new ZipArchive;
        //     if (!($zip->open($path))){
        //         return false;
        //     }
        //     $aFileName = explode('/', $to);
        //     $aFileName = $aFileName[end($aFileName)];
        //     if (is_dir($to)) {
        //         echo "dir should be deleted";
        //         //deleteDir($to);
        //     }
        //     if (!mkdir($to)) {
        //         die("Не удалось открыть файл");
        //     }
        //     if (!($zip->extractTo($to))) {
        //         die("Не удалось открыть файл");
        //     } else {
        //         //header('Location: process.php');
        //     }
        // }


        // создание архива

        $zip = new ZipArchive;
        $zip->open($_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["currentfile"]);


        // работа с сессей

        $fileFullName = $_SESSION["file"]["currentfile"];
        $aFileName = explode('.', $fileFullName)[0];
        $_SESSION["file"]["unzip_folder_name"] = $aFileName;
        $extractDir = $_SESSION["file"]["cash_directory_relative_path"] . $aFileName;


        // работа с папкой

        if (is_dir($extractDir)) {
            echo "dir should be deleted";
            //deleteDir($extractDir);
        }
        if (!mkdir($extractDir)) {
            die("Не удалось открыть файл");
        }


        // непосредственно само извлечение

        if (!($zip->extractTo($extractDir))) {
            die("Не удалось открыть файл");
        } else {
            //header('Location: process.php');
        }





        set_time_limit(86400);

        // $colors = array();
        // $paragraph_coins = array();
        // $document_coins = array();

        function get_names_from_db(){
            require_once '../user/connect.php';
            $request = "SELECT `name` FROM register";
            $res = $connect->query($request);
            $register_list = array();
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    array_push($register_list, $row['name']);
                }
            } else {
            //echo "rows: " . $res->num_rows > 0;
            }
            return $register_list;
        }

        $register_list = get_names_from_db();

        function nice_color($color)
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

        function genColor()
        {
            global $colors;
            // from 0 to 32 for each channel
            $min = 0;
            $max = 255;
            $factor = 8;
            $color = array(mt_rand($min, $max), mt_rand($min, $max), mt_rand($min, $max));
            while (!nice_color($color)) {
                $color = array(mt_rand($min, $max), mt_rand($min, $max), mt_rand($min, $max));
            }
            $color = sprintf("%02x%02x%02x", $color[0], $color[1], $color[2]);
            while(in_array($color, $colors)){
                $color = genColor();
            }
            return $color;
        }

        function setColor($word){
            if (isset($_SESSION["coinsidences"][$word])){
                if (isset($_SESSION["coinsidences"][$word]["color"])){
                    return $_SESSION["coinsidences"][$word]["color"];
                } else {
                    $color = genColor();
                    $_SESSION["coinsidences"][$word]["color"] = $color;
                    return $color;
                }
            } else {
                die("Coinsidence not found in session array");
            }
        }

        function is_punctuation($t){
            $text = trim($t, " !?.,;:");
            if (strlen($text) == 0){
                return 1;
            } else {
                return 0;
            }
        }

        function saveCoincidences($text){
            global $paragraph_coins;
            global $document_coins;
            global $register_list;
            $coinsidences = [];

            $forbidden_words = $register_list;
            array_push($document_coins, $paragraph_coins);
            $paragraph_coins = [];
            foreach ($forbidden_words as $word){
                $indices = coincidencesByName($text, $word);
                if (count($indices)){
                    $color = setColor($word);
                    $paragraph_coins[$word] = array($indices, $color);
                }
            }
            return $coinsidences;
        }

        function coincidence($str1, $str2){
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

        function coincidencesByName($text, $word)
        {
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
                $coins = coincidence($substr, $word);
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

        function split_paragraph($paragraph){
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
                        // if (($w + 1) < count($segment_words) && is_punctuation($segment_words[$w + 1])){
                        //     $next_word = $segment_words[$w+1];
                        //     $word = $word . $next_word;
                        //     unset($segment_words[$w+1]);
                        // }
                        if ($w+1 < count($segment_words)){
                            if (is_punctuation($segment_words[$w + 1])){
                                $next_word = $segment_words[$w+1];
                                $word = $word . $next_word;
                                unset($segment_words[$w+1]);
                            }
                        } else {
                            if (isset($next_segment_text)){
                                $next_word = explode(" ", $next_segment_text)[0];
                                if (is_punctuation($next_word)) {
                                    
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
                            sxml_append($word_object, $styles_tag);
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

        function extract_text(SimpleXMLElement $p){
            $text = "";
            foreach ($p->r as $r){
                $text = $text . (string)($r->t);
            }
            return $text;
        }

        function sxml_append(SimpleXMLElement $to, SimpleXMLElement $from)
        {
            if (count($from)){
                $toDom = dom_import_simplexml($to);
                $fromDom = dom_import_simplexml($from);
                $toDom->appendChild($toDom->ownerDocument->importNode($fromDom->cloneNode(true), true));
            }
        }

        function make_docx()
        {
            //$_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["currentfile"]
            $dir = $_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["unzip_folder_name"];
            $zip = new ZipArchive;
            $zip->open($dir . ".docx", ZipArchive::CREATE | ZipArchive::OVERWRITE);

            echo $dir;

            $options = array('remove_path' => $dir);
            $zip->addGlob($dir . '/**/*.*', 0, $options);
            $zip->addGlob($dir . '/*.*', 0, $options);
            $zip->addGlob($dir . '/_rels/.rels', 0, $options);

            $zip->close();
        }

        function process_xml(){

            global $paragraph_coins;

            $path_to_document = "word/document.xml";
            $XMLfile_path = $_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["unzip_folder_name"] . '/' . $path_to_document;
            $xml_document = simplexml_load_file($XMLfile_path, null, 0, 'w', true);
            $body = $xml_document->body;

            foreach($body->p as $paragraph){
                saveCoincidences(extract_text($paragraph));
                $coinsidences = $paragraph_coins;
                split_paragraph($paragraph);

                
                
                if (count($coinsidences) > 0){

                    echo "<br><br>register_list<br><br>";

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

        function process_html(){

            global $document_coins;

            $dom = new DOMDocument();
            $path = $_SESSION["file"]["cash_directory_relative_path"] . "content.html";
            $html = file_get_contents($path);
            if (!$html) return 0;
            $dom->loadHTML('<?xml encoding="UTF-8">' . $html);

            $elements = $dom->getElementsByTagName("p");
            for ($i = 0; $i < count($elements); $i++){
                $paragraph = $elements[$i];
                $words = $paragraph->getElementsByTagName("span");

                if ($i+1 < count($document_coins)){
                    $coinsidences = $document_coins[$i+1];
                } else {
                    $coinsidences = null;
                }

                if ($coinsidences){
                    foreach ($coinsidences as $coin) {
                        $j = 0;
                        foreach($words as $word){
                            if (in_array($j, $coin[0])){
                                $styles = $word->getAttribute("style");
                                $word->setAttribute("style", $styles . "background-color: #". $coin[1] .";");
                            }
                            $j++;
                        }
                    }
                }
            }
            $html = $dom->saveHTML();
            //file_put_contents($path, $html);
            $file = fopen($path, "w");
            fwrite($file, $html);
            fclose($file);
            //echo $dom->saveHTML();
        }

        //unset($_SESSION["coinsidences"]);
        $_SESSION["coinsidences"] = [];

        process_xml();
        make_docx();
        save_html($_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["unzip_folder_name"] . ".docx", $_SESSION["file"]["cash_directory_relative_path"] . "content.html");
        header('Location: ../pages/file.php');
        print_array($_SESSION);


    }

}

?>