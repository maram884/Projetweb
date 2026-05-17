<?php
session_start();
require_once("config.php");

$result = mysqli_query($conn,
"SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Gestion Users</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Poppins, sans-serif;
}

/* ===== FOND SOMBRE ASSORTI AU DASHBOARD ===== */
body{
    background:#11141a;
    padding:40px;
    color: #ffffff;
    min-height: 100vh;
}

h1{
    color: #ffffff;
    margin-bottom: 30px;
    font-size: 28px;
    font-weight: 600;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 15px;
}

/* ===== TABLEAU STYLE DASHBOARD MODERNE ===== */
table{
    width:100%;
    border-collapse:collapse;
    background:#1b1f27;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    margin-bottom: 20px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

/* EN-TÊTE DU TABLEAU */
th{
    background:#ff7b00; /* Orange dans le thème */
    color:white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 0.5px;
}

th,td{
    padding:15px;
    border-bottom:1px solid rgba(255, 255, 255, 0.05);
    text-align:center;
}

/* EFFET AU SURVOL DES LIGNES */
tr:hover td {
    background: rgba(255, 255, 255, 0.02);
}

td {
    color: #e0e0e0;
}

/* ===== STYLE DES BOUTONS D'ACTION ===== */
a{
    padding:8px 15px;
    text-decoration:none;
    color:white;
    border-radius:6px;
    font-weight: bold;
    font-size: 14px;
    transition: 0.3s;
    display: inline-block;
}

/* Bouton Modifier */
.edit{
    background: rgba(255, 165, 0, 0.15);
    color: #ffa500;
    border: 1px solid rgba(255, 165, 0, 0.3);
}

.edit:hover{
    background: orange;
    color: white;
}

/* Bouton Supprimer */
.delete{
    background: rgba(255, 77, 77, 0.15);
    color: #ff4d4d;
    border: 1px solid rgba(255, 77, 77, 0.3);
    margin-left: 5px;
}

.delete:hover{
    background: #ff4d4d;
    color: white;
}

/* Bouton Retour */
.back{
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: inline-block;
    margin-top: 20px;
}

.back:hover{
    background: white;
    color: #11141a;
}

</style>

</head>

<body>

<h1>Gestion des utilisateurs</h1>

<table>

<tr>
<th>ID</th>
<th>Nom</th>
<th>Prénom</th>
<th>Email</th>
<th>Role</th>
<th>Actions</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?= $row['id']; ?></td>
<td><?= $row['nom']; ?></td>
<td><?= $row['prenom']; ?></td>
<td><?= $row['email']; ?></td>
<td><?= $row['role']; ?></td>

<td>

<a class="edit"
href="edit_user.php?id=<?= $row['id']; ?>">
Modifier
</a>

<a class="delete"
href="delete_user.php?id=<?= $row['id']; ?>">
Supprimer
</a>

</td>

</tr>

<?php
}
?>

</table>

<a class="back" href="admin_dashboard.php">
Retour Dashboard
</a>

</body>
</html>