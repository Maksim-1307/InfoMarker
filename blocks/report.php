<div class="report">
    <?php
    $coinsidences = $_SESSION["coinsidences"];
    // foreach($coinsidences as $name => $count){
    //     echo "f" . $coins;
    // }
    foreach ($_SESSION["coinsidences"] as $name => $data) {
        //$color = $paragraph_coins[$name]["color"];
        if ($data) {
            echo "<div class='report__row' style='background:#" . $data["color"] . "'><div>" . $name . "</div><div>" . $data["count"] . "</div></div>";
        }
    }
    //echo isset() 
    ?>
</div>