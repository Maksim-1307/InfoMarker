<?php

function coincidence($str1, $str2){
    $len = (float)max(strlen($str1), strlen($str2));
    $lev = (float)levenshtein($str1, $str2);
    return (float)((float)($len - $lev) / (float)$len);
}

function getCoincidences($str1, $str2){
    $MINCOINS = 0.75;

    $result = [];
    $words = explode(' ', $str1);
    $words2 = explode(' ', $str2);
    $count = count($words2);
    $wordsCount = count($words);

    for ($i = 0; $i < $wordsCount; $i++){
        $word = "";
        $coins = $MINCOINS;
        $res = [];
        for ($j = 0; $j < $count; $j++){
            $word = $words2[$j];
            $coins2 = coincidence($word, $words[$i + $j]);
            //print("  word is: " . $words[$i + $j] . ",  cons is " . $coins2 .  "  <br> ");
            if ($coins2 >= $coins){
                $coins = $coins2;
                // $i += $j;
                // array_push($result, $i);
                if ($coins >= $MINCOINS) {
                    //print("word n is " . $words[$i + $j] . "<br>");
                    array_push($result, $i + $j);
                }
            }
        }
    }
    return $result;
}



