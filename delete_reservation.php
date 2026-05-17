
<?php
//supprimer une réservation dans une base de données MySQL.
require_once("config.php"); //Connexion à la base

$id = $_GET['id']; //Récupération de l’ID

//Suppression dans la base de données
mysqli_query($conn,
"DELETE FROM reservations WHERE id=$id");

header("Location: manage_reservations.php"); //Après suppression, on redirige vers la page de gestion des réservations.
?>
