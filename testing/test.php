<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


if (!count($_POST)) echo "no post data";
var_dump($_POST);
var_dump($_GET);