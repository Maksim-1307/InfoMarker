<?php

require_once('settings.php');
require_once("fucntions.php");

function get_files_array()
{

    global $filesPath;
    global $forbiddenFileNames;

    $files_array = array_diff(
        scandir($filesPath),
        $forbiddenFileNames
    );
    return $files_array;
}

function print_files_list()
{

    $files = get_files_array();

    echo "<h2>Ваши файлы:</h2><p>";
    foreach ($files as $filename) {
        echo print_file($filename);
    }
    if (count($files) == 0) {
        echo "<div>no files loaded yet</div>";
    }
    echo "</p>";
}

function print_file($filename)
{

    global $filesPath;
    global $lengthOfFilePreviewText;

    ?>
    <a href="<?= get_url() . 'file.php?name=' . $filename ?>" class="file-card">
        <h3 class="file-card__name"><?= $filename ?></h3>
        <p class="file-card__file-content"><?= file_get_contents( $filesPath . $filename, FALSE, NULL, 0, $lengthOfFilePreviewText) ?></p>
    </a>
    <?php
}

?>