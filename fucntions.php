<?php

function get_home_uri(){
    return $_SERVER['DOCUMENT_ROOT'] . '/';
}

function get_url()
{
    return $_SERVER['HTTP_HOST'] . '/';
}


function read_docx($filename) {

    // $zip = new ZipArchive;
    // $res = $zip->open($filename);
    // if ($res === TRUE) {
    //     // $zip->extractTo('/myzips/extract_path/');
    //     // $zip->close();
    //     // echo 'woot!';
    //     return $res;
    // } else {
    //     return false;
    // }

    $za = new ZipArchive();

    $za->open($filename);

    for ($i = 0; $i < $za->numFiles; $i++) {
        $stat = $za->statIndex($i);
        print_r(basename(
            $stat['name']
        ) . PHP_EOL);
    }
    return 1;

    // $striped_content = '';
    // $content = '';

    // if (!$filename || !file_exists($filename)) return false;

    // $zip = ZipArchive::open($filename);
    // if (!$zip || is_numeric($zip)) return false;

    // while ($zip_entry = ZipArchive::open($zip)) {

    //     if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

    //     if (zip_entry_name($zip_entry) != "word/document.xml") continue;

    //     $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

    //     zip_entry_close($zip_entry);
    // }
    // zip_close($zip);
    // $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    // $content = str_replace('</w:r></w:p>', "\r\n", $content);
    // $striped_content = strip_tags($content);

    //return $striped_content;

    //return $zip;
}
