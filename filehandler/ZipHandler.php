<?php

class ZipHandler{

    public $allowedExtentions = [];

    public function removeExt($name){
        $arr = explode('.', $name);
        array_pop($arr);
        if (!count($arr)) return $name;
        return implode($arr);
    }

    public function checkFolder(){

    }

    public function unzip($src, $to, $callback = null){
        try {

            $zip = new ZipArchive;
            $zip->open($src);
            $name = end((explode("/", $src)));
            $name = ZipHandler::removeExt($name);

            $to .= ('/' . $name);

            if (!($zip->extractTo($to))) {
                throw new Exception("Failed to unpack in ZipHandler->unzip");
            }

            return $to;

        } catch (Exception $err){
            if (isset($callback)){
                $callback($err);
            }
        }

    }

    public function zip($src, $to, $callback = null){
        try {

            $zip = new ZipArchive;
            $zip->open($src . ".docx", ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $options = array('remove_path' => $src);
            $zip->addGlob($src . '/**/*.*', 0, $options);
            $zip->addGlob($src . '/*.*', 0, $options);
            $zip->addGlob($src . '/_rels/.rels', 0, $options);

            $zip->close();

            return $src . ".docx";

        } catch (Exception $err){
            if (isset($callback)){
                $callback($err);
            }
        }
    }

}

?>