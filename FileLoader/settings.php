<?php

require_once('fucntions.php');

$forbiddenFileNames = array(
    '.',
    '..',
    '.DS_Store'
);

$filesPath = get_home_uri() . 'files/';

$lengthOfFilePreviewText = 100;

?>