<?php

require_once 'TagHandler.php';

define("CASHFOLDER", "docxToHtmlCashFolder");

class Handler{

    public $taghandler;// = new ZipArchive;
    public $path;
    public $xml, $stylesXml;

    function __construct($pathToDocx) {
        $this->path = $pathToDocx;
        $this->taghandler = new TagHandler();
    }

    function __destruct(){
        $this->deleteDir(CASHFOLDER);
    }

    function get_html(){
        $this->handle();
        $dom = new DOMDocument();
        $dom->loadXML($this->xml);
        return $this->get_css() . $this->taghandler->get_html($dom->documentElement);
    }

    function get_css(){
        $stylesDoc = new DOMDocument();
        if ($this->stylesXml){
            $stylesDoc->loadXML($this->stylesXml);
            return $this->taghandler->get_html($stylesDoc->documentElement);
        } else {
            return "";
        }
    }

    function save_html($path){
        return 1;
    }
    
    function handle(){
        $this->unpack();
        $this->xml = file_get_contents(CASHFOLDER . "/word/document.xml");
        $this->stylesXml = file_get_contents(CASHFOLDER . "/word/styles.xml");
        $this->deleteDir(CASHFOLDER);
    }

    function deleteDir(string $dir)
    {
        system('rm -rf -- ' . escapeshellarg($dir), $retval);
        return ($retval == 0);
    }

    function unpack(){

        $zip = new ZipArchive;
        $zip->open($this->path);

        if (is_dir(CASHFOLDER)) {
            $this->deleteDir(CASHFOLDER);
        }

        if (!mkdir(CASHFOLDER)) {
            echo "ERROR: DocxToHtml mkdir failed";
        }

        if (!($zip->extractTo(CASHFOLDER))) {
            echo "ERROR: DocxToHtml zip extract failed";
        }
    }
}



?>