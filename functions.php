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

?>