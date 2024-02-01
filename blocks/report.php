<div class="report">
    <?php 
    $coinsidences = $_SESSION["coinsidences_count"];
    // foreach($coinsidences as $name => $count){
    //     echo "f" . $coins;
    // }
    foreach($_SESSION["coinsidences_count"] as $name => $count){
        //$color = $_SESSION["coinsidences"][$name]["color"];
        echo "<div class='report__row'><div>". $name ."</div><div>$count</div></div>";
    } 
    //print_r($coinsidences);
    //echo isset() 
    ?>
</div>