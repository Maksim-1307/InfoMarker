<?php

// require_once('fucntions.php');
// require_once('fileLoader/filesList.php');

//print_files_list();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href="style.css">
    <title>InfoMarker</title>
</head>

<body>
    <div class="wrapper">
        <?php
        require 'blocks/header.php';
        require 'blocks/fileloader.php';
        ?>
    </div>
    <?php require 'blocks/footer.php'; ?>

</body>

</html>