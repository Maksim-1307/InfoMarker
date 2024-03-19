<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

function unzip($from, $to){
    $zip = new ZipArchive;

    if (!($zip->open($from))){
        return false;
    }

    $aFileName = explode('/', $to);
    $aFileName = $aFileName[end($aFileName)];

    if (is_dir($to)) {
        deleteDir($to);
    }

    if (!mkdir($to)) {
        die("Не удалось открыть файл");
    }

    if (!($zip->extractTo($to))) {
        die("Не удалось открыть файл");
    } else {
        return true;
    }
}

//requeres 777 premissions
function deleteDir(string $dir): void
{
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator(
        $it,
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getPathname());
        } else {
            unlink($file->getPathname());
        }
    }
    rmdir($dir);
}


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

function save_html($docx, $to){
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($docx);
    $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
    $htmlWriter->save($to);
}

function print_array($arr, $tab = 0)
{
    foreach ($arr as $key => $element) {
        if (gettype($element) == 'array') {
            print(str_repeat(' --- ', $tab) . "<b>" . strtoupper($key) . "</b>" . ":<br>");
            print_array($element, $tab + 1);
        } else {
            $res = "" . str_repeat(' --- ', $tab) . "[" . $key . "] " . $element . "<br>";
            echo $res;
        }
    }
}

function make_short_names($full_name){

    //текст заключенный в ковычки "" и «» является коротким именем

    $quotes = array('"', '«', '»'); // „ “
    $short_names = array();
    $flag = false;
    $str = "";
    if (in_array($full_name[0], $quotes)){
        $flag = !$flag;
    }
    foreach (mb_str_split($full_name) as $char){
        if (in_array($char, $quotes)){
            $flag = !$flag;
            //if ($flag) $str = $str . $char;
        } else {
            if ($flag){
                $str = $str . $char;
            } 
        }
        if (!$flag && $str){
            array_push($short_names, $str);
            array_push($short_names, '«' . $str . '»');
            $str = "";
        }
    }

    return $short_names;
}

function is_upper($char){
    if (!$char) return false;
    if (mb_ord($char) >= mb_ord('A') && mb_ord($char) <= mb_ord('Z') || mb_ord($char) >= mb_ord('А') && mb_ord($char) <= mb_ord('Я')){
        return true;
    } else {
        return false;
    }
}

function is_lower($char){
    if (mb_ord($char) >= mb_ord('a') && mb_ord($char) <= mb_ord('z') || mb_ord($char) >= mb_ord('а') && mb_ord($char) <= mb_ord('я')){
        return true;
    } else {
        return false;
    }
}

function is_letter($char){
    return is_upper($char) || is_lower($char);
}  

function is_russian($char){
    if (mb_ord($char) >= mb_ord('а') && mb_ord($char) <= mb_ord('я') || mb_ord($char) >= mb_ord('А') && mb_ord($char) <= mb_ord('Я')){
        return true;
    } else {
        return false;
    }
}

function is_english($char){
    if (mb_ord($char) >= mb_ord('a') && mb_ord($char) <= mb_ord('z') || mb_ord($char) >= mb_ord('A') && mb_ord($char) <= mb_ord('Z')){
        return true;
    } else {
        return false;
    }
}

function make_lowercase($str){
    $dRus = mb_ord('а') - mb_ord('А');
    $dEn = mb_ord('a') - mb_ord('A');
    $result = "";
    for ($i = 0; $i < mb_strlen($str); $i++){
        $char = mb_substr($str, $i, 1, "UTF-8");
        if (is_upper($char)){
            if (is_english($char)){
                $char = mb_chr(mb_ord($char, "UTF-8") + $dEn);
            }
            if (is_russian($char)){
                $char = mb_chr(mb_ord($char, "UTF-8") + $dRus);
            }
        }
        $result .= $char;
    }
    return $result;
}

?>