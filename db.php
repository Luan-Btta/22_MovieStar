<?php

$type = "mysql";
$db = "moviestar";
$host = "localhost";
$user = "root";
$pass = "root";

try{
    $conn = new PDO("$type:host=$host;dbname=$db", $user, $pass);
}catch(PDOException $err){
    $error = $err->getMessage();
    echo "Erro: $error";
}

?>