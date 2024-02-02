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
                <?php

                function print_array($arr, $tab = 0)
                {
                    foreach ($arr as $key => $element) {
                        if (gettype($element) == 'array') {
                            print(str_repeat(' --- ', $tab) . "<b>" . strtoupper($key) . "</b>" . ":<br>");
                            print_array($element, $tab + 1);
                        } else {
                            $res = "" . str_repeat(' --- ', $tab) . "[" . $key . "] " . $element . "<br>";
                            echo $res;
                        }
                    }
                }

                print_array($_SESSION);

                // $output = str_replace(array("\r\n", "\n", "\r"), '<br>', json_encode($_SESSION));
                // echo $output;   
                //print_r(JSON.pretty_generate($_SESSION)) 
                
                ?>
            </div>
        </section>
    </div>
    <?php require '../blocks/footer.php' ?>
</body>

</html>