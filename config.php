<?php

$host = "localhost"; //serveur local
$user = "root"; //utilisateur MySQL par défaut 
$password = ""; //mot de passe vide
$database = "auberge_db"; //nom de ta base de données

$conn = mysqli_connect($host, $user, $password, $database); //crée la connexion entre PHP et MySQL

//si connexion échoue on arrête le script et on affiche "Connexion échouée"

if (!$conn) {
    die("Connexion échouée");
}

?>
