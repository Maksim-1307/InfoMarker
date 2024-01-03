<h1>обработчик файла</h1>

<p>

    <?php

    require_once('FileLoader/settings.php');
    require_once('FileLoader/filesList.php');

    if (move_uploaded_file($_FILES['newfile']['tmp_name'], $filesPath . $_FILES['newfile']['name'])) {
        echo "SUCCESS: file is loaded and copied on server";
    } else {
        echo "ERROR: can't upload a file";
    }

    print_files_list();

    ?>

</p>