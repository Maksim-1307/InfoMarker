<?php
require_once '../vendor/autoload.php';


$wordfile = \PhpOffice\PhpWord\IOFactory::load($_SESSION["cash_directory_relative_path"] . $_SESSION["currentfile"]);
$htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($wordfile);
$htmlWriter->save('worddocument.html');

echo file_get_contents('worddocument.html');

?>