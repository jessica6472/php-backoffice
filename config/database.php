<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "php_backoffice";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}