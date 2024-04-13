<?php
require_once '../vendor/autoload.php';
require_once '../docx2html/DocxToHtml.php';

// $wordfile = \PhpOffice\PhpWord\IOFactory::load($_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["currentfile"]);
// $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($wordfile);
// $htmlWriter->save('worddocument.html');

$wordPath = $_SESSION["file"]["cash_directory_relative_path"] . $_SESSION["file"]["currentfile"];
$handler = new Handler($wordPath);
echo $handler->get_html();

//echo file_get_contents($_SESSION["file"]["cash_directory_relative_path"] . "content.html");
