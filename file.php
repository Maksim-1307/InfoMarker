<?php 

require_once("FileLoader/settings.php");
require_once("fucntions.php");
require_once 'vendor/autoload.php';

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

    $fileContent = file_get_contents($htmlFile);

    foreach ($forbiddenWords as $forbiddenName) {
        
        $pos = strpos($fileContent, $forbiddenName);

        if ($pos){
            $fileContent = substr_replace($fileContent, $openSelectionTag . $forbiddenName . $closeSelectionTag, $pos, strlen($forbiddenName));
        }

    }

    echo $fileContent;

}

if (file_exists($filesPath . $fileName)){

    $wordfile = \PhpOffice\PhpWord\IOFactory::load($filesPath . $fileName);
    $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($wordfile);
    $htmlWriter->save('worddocument.html');
    echo "<h3>File content:</h3>";
    echo file_get_contents('worddocument.html');
    echo "<h3>Processed content:</h3>";
    findCoincidences('worddocument.html');

} else {
    echo "файл не найден";
}

?>