<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "auberge_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connexion échouée");
}

?>