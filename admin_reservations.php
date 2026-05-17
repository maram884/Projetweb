<?php
include("config.php"); //Connexion base de données

$result = mysqli_query($conn, "SELECT * FROM reservations ORDER BY id DESC"); //prendre toutes les réservationsles trier du plus récent au plus ancien
?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Admin Reservations</title>

<style>

body{
    font-family:Poppins;
    background:#f5f5f5;
    padding:30px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

table th, table td{
    border:1px solid #ccc;
    padding:15px;
    text-align:center;
}

th{
    background:#ff0157;
    color:white;
}

</style>

</head>

<body>

<h1>Liste des réservations</h1>

<table>

<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Email</th>
    <th>Téléphone</th>
    <th>Date</th>
    <th>Heure</th>
    <th>Personnes</th>
</tr>

<?php
/// prendre une réservationla mettre dans $row
while($row = mysqli_fetch_assoc($result))
{
?>

<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['nom'] ?></td>
    <td><?= $row['prenom'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['telephone'] ?></td>
    <td><?= $row['date_reservation'] ?></td>
    <td><?= $row['heure_reservation'] ?></td>
    <td><?= $row['personnes'] ?></td>
</tr>

<?php
}
?>

</table>

</body>
</html>
