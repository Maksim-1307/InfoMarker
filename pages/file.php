<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ваш файл</title>
    <link rel='stylesheet' href="../style.css">
</head>

<body>
    <div class="wrapper">
        <?php require '../blocks/header.php' ?>
        <section>
            <div class="container">
                <?php require '../blocks/processed_text.php' ?>
                <?php require '../blocks/download_file.php' ?>
                <?php require '../blocks/report.php'?>
            </div>
        </section>
    </div>
    <?php require '../blocks/footer.php' ?>
</body>

</html>