<?php 

session_start();

require_once("FileLoader/settings.php");
require_once("fucntions.php");
require_once 'vendor/autoload.php';
require_once "texthandler/countwords.php";

$fileName = $_GET['name'];

$wordfile;


function findCoincidences($htmlFile){

    // in the future will be loaded from the database
    $forbiddenWords = array(
        'word1',
        'word word2',
        'some word'
    );

    $openSelectionTag = '<span lang=\'en-US\' style="background: yellow;">';
    $closeSelectionTag = '</span>';

    $fileContent = "Erhgk dhg kjdfhg ksdh fgjksdh gkjdshfkj sdword1ahgkadg msadjkgn hadsjn fkjasdg nsdangh word1 
gjkadsome wordjgdsjgsdjlgdsjgdsjfd some sfh word dsjklfh dsjkh kjh jkldsh fjkl. word word2 asdh fljksdhf jklsdhf ljkdshfjkl sdhfjkl sfklj h";//file_get_contents($htmlFile);

    //foreach ($forbiddenWords as $forbiddenName) {
        
    //     $pos = strpos($fileContent, $forbiddenName);

    //     if ($pos){
    //         $fileContent = substr_replace($fileContent, $openSelectionTag . $forbiddenName . $closeSelectionTag, $pos, strlen($forbiddenName));
    //     }

    //}

    print_r(getCoincidences($fileContent, "word"));

    //echo $fileContent;

}

if (file_exists($filesPath . $fileName)){

    $wordfile = \PhpOffice\PhpWord\IOFactory::load($filesPath . $fileName);
    $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($wordfile);
    $htmlWriter->save('worddocument.html');
    echo "<h3>File content:</h3>";
    echo file_get_contents('worddocument.html');
    echo "<h3>Processed content:</h3>";
    findCoincidences('worddocument.html');

    $_SESSION['currentfile'] = $fileName;

    header('Location: texthandler/process_docx.php');

    // $sections = $wordfile->getSections();
    // $text = "";

    // foreach ($sections as $s) {
    //     $els = $s->getElements();
    //     /** @var ElementTest $e */
    //     echo "ELEMENTS " . count($els) . "<br>";
    //     foreach ($els as $e) {
    //         $class = get_class($e);
    //         if (method_exists($class, 'getText')) {
    //             $text .= $e->getText();
    //         } else {
    //             $text .= "\n";
    //         }
    //     }
    // }
    // echo $text;

} else {
    echo "файл не найден";
}

?>


