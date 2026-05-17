<?php
require_once("config.php");

$message = "";
$error = "";

if(isset($_POST['register']))
{
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // vérifier email existe déjà

    $check = mysqli_query($conn,
    "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0)
    {
        $error = "Cet email existe déjà";
    }
    else
    {
        $sql = "INSERT INTO users
        (nom, prenom, email, password, role)

        VALUES
        ('$nom','$prenom','$email','$password','user')";

        if(mysqli_query($conn, $sql))
        {
            $message = "Compte créé avec succès";
        }
        else
        {
            $error = "Erreur inscription";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Inscription</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Poppins, sans-serif;
}

/* ===== MÊME STYLE ARRIÈRE-PLAN ===== */
body{
    min-height:100vh;
    background: linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)),
    url("images/background.jpg");
    background-size:cover;
    background-position:center;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px;
}

/* ===== MÊME STYLE BOITE EN VERRE (GLASSMORPHISM) ===== */
.box{
    width:450px;
    padding:40px;
    border-radius:18px;

    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    border:1px solid rgba(255,255,255,0.2);
    box-shadow:0 8px 30px rgba(0,0,0,0.3);
}

/* TITRE */
h1{
    text-align:center;
    margin-bottom:30px;
    color:#fff;
    font-size:32px;
}

/* INPUTS STYLE RESERVATION */
input{
    width:100%;
    padding:14px;
    margin-bottom:20px;
    border-radius:10px;
    border:1px solid rgba(255,255,255,0.3);
    background:rgba(0,0,0,0.3);
    color:white;
    outline:none;
    font-size:16px;
}

/* PLACEHOLDER */
input::placeholder{
    color:#ddd;
}

/* BOUTON ORANGE */
button{
    width:100%;
    padding:14px;
    border:none;
    background:#ff7b00;
    color:white;
    font-size:18px;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s;
    font-weight:bold;
}

button:hover{
    background:black;
}

/* MESSAGE SUCCÈS */
.success{
    background: rgba(0, 0, 0, 0.4);
    color:#00ff88;
    padding:12px;
    margin-bottom:20px;
    border-radius:8px;
    text-align:center;
}

/* MESSAGE ERREUR */
.error{
    background: rgba(255, 0, 0, 0.2);
    color:#ff4d4d;
    padding:12px;
    margin-bottom:20px;
    border-radius:8px;
    text-align:center;
    border: 1px solid rgba(255, 77, 77, 0.3);
}

/* RETOUR / LIEN COULEUR ORANGE */
.link{
    margin-top:20px;
    text-align:center;
    color:#fff;
    font-weight:bold;
}

.link a{
    color:#ff7b00;
    text-decoration:none;
    margin-left:5px;
}

.link a:hover{
    text-decoration:underline;
}

</style>

</head>

<body>

<div class="box">

<h1>Créer un compte</h1>

<?php
if($message != "")
{
    echo "<div class='success'>$message</div>";
}

if($error != "")
{
    echo "<div class='error'>$error</div>";
}
?>

<form method="POST">

<input type="text" name="nom" placeholder="Nom" required>

<input type="text" name="prenom" placeholder="Prénom" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Mot de passe" required>

<button type="submit" name="register">
Créer un compte
</button>

</form>

<div class="link">

Déjà un compte ?

<a href="login.php">
Se connecter
</a>

</div>

</div>

</body>
</html>