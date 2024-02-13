<?

$connect = mysqli_connect('localhost', 'root', 'root', 'register');

if (!$connect){
    die('Databese connection failed');
}