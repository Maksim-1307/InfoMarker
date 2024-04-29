
<section >
    <div class="container warning">
        <h2>Просмотр верстки</h2>
        <p>Так будет выглядеть сайт в скором будущем, когда верстка будет утверждена и на ее основе будет разрабртана front-end часть проекта</p>
        <h3>Готовые страницы:</h3>
        <div>
            <?php
                $files = scandir($_SERVER['DOCUMENT_ROOT'] . "/layout/");
                foreach($files as $file){
                    if (str_contains($file, '.html')){
                        echo "<a href='/layout/". $file ."'>" . $file . "</a><br>";
                    }
                }
            ?>
        </div>
    </div>
</section>
<?php

?>