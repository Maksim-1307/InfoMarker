<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


if (mkdir("../uploads/testtest")){
    echo "success";
} else {
    echo "fail";
}

