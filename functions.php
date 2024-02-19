<?php 


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

?>