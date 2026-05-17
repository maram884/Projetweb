<?php
require_once("config.php");

$id = $_GET['id'];

$result = mysqli_query($conn,
"SELECT * FROM users WHERE id=$id");

$user = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    mysqli_query($conn,

    "UPDATE users SET

    nom='$nom',
    prenom='$prenom',
    email='$email',
    role='$role'

    WHERE id=$id");

    header("Location: manage_users.php");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Modifier User</title>

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

/* INPUTS ET SELECT EN THÈME SOMBRE */
input, select{
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

/* Style spécifique pour la flèche de sélection et le fond des options */
select option {
    background: #1b1f27;
    color: white;
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

<h1>Modifier utilisateur</h1>

<form method="POST">

<input type="text"
name="nom"
value="<?= $user['nom']; ?>" required>

<input type="text"
name="prenom"
value="<?= $user['prenom']; ?>" required>

<input type="email"
name="email"
value="<?= $user['email']; ?>" required>

<select name="role">
    <!-- Le PHP vérifie maintenant quel rôle cocher par défaut -->
    <option value="user" <?= $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
</select>

<button name="update">
Modifier
</button>

</form>

<a href="manage_users.php" class="cancel-link">Annuler et retourner</a>

</div>

</body>
</html>