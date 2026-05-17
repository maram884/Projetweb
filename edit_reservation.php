<?php
require_once("config.php");

$id = $_GET['id'];

$result = mysqli_query($conn,
"SELECT * FROM reservations WHERE id=$id");

$reservation = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $personnes = $_POST['personnes'];

    mysqli_query($conn,

    "UPDATE reservations SET

    nom='$nom',
    email='$email',
    date_reservation='$date',
    heure_reservation='$heure',
    personnes='$personnes'

    WHERE id=$id");

    header("Location: manage_reservations.php");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Modifier réservation</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Poppins, sans-serif;
}

/* ===== FOND SOMBRE ASSORTI AU DASHBOARD ===== */
body{
    min-height:100vh;
    background:#11141a;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px;
    color: #ffffff;
}

/* ===== BLOC CONTAINER DU FORMULAIRE ===== */
.box{
    width:450px;
    background:#1b1f27;
    padding:40px;
    border-radius:12px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

/* TITRE */
h1{
    text-align:center;
    margin-bottom:30px;
    color:#fff;
    font-size:28px;
    font-weight: 600;
}

/* INPUTS EN THÈME SOMBRE ASSORTI */
input{
    width:100%;
    padding:14px;
    margin-bottom:20px;
    border-radius:10px;
    border:1px solid rgba(255,255,255,0.1);
    background:rgba(0,0,0,0.2);
    color:white;
    outline:none;
    font-size:16px;
}

/* Ajustement de l'icône calendrier/heure pour les navigateurs récents */
input::-webkit-calendar-picker-indicator {
    filter: invert(1);
}

/* BOUTON MODIFIER ORANGE */
button{
    width:100%;
    padding:14px;
    border:none;
    background:#ff7b00;
    color:white;
    font-size:18px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    transition: 0.3s;
}

button:hover{
    background:black;
}

/* LIEN DE RETOUR RAPIDE */
.cancel-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #a0a0a0;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}

.cancel-link:hover {
    color: #ffffff;
}

</style>

</head>

<body>

<div class="box">

<h1>Modifier réservation</h1>

<form method="POST">

<input type="text"
name="nom"
value="<?= $reservation['nom']; ?>" required>

<input type="email"
name="email"
value="<?= $reservation['email']; ?>" required>

<input type="date"
name="date"
value="<?= $reservation['date_reservation']; ?>" required>

<input type="time"
name="heure"
value="<?= $reservation['heure_reservation']; ?>" required>

<input type="number"
name="personnes"
value="<?= $reservation['personnes']; ?>" required>

<button name="update">
Modifier
</button>

</form>

<a href="manage_reservations.php" class="cancel-link">Annuler et retourner</a>

</div>

</body>
</html>