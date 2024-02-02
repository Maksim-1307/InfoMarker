<div class="report">
    <?php 
    $coinsidences = $_SESSION["coinsidences_count"];
    // foreach($coinsidences as $name => $count){
    //     echo "f" . $coins;
    // }
    foreach($_SESSION["coinsidences_count"] as $name => $data){
        //$color = $_SESSION["coinsidences"][$name]["color"];
        if ($data) {
            echo "<div class='report__row' style='background:" . $data["color"] . "'><div>" . $name . "</div><div>" . $data["count"] . "</div></div>";
        } 
    }
    //echo isset() 
    ?>
</div>