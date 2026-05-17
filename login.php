<?php
session_start();
session_regenerate_id(true);
require_once("config.php");

$error = "";

if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Utilisation d'une requête préparée pour contrer les injections SQL
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($user = mysqli_fetch_assoc($result))
        {
            // Note de sécurité : Idéalement, utilisez password_verify($password, $user['password'])
            // Si vos mots de passe sont encore en clair (non recommandé), laissez la ligne ci-dessous :
            if($password === $user['password']) 
            {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = strtolower($user['role']); // Force les minuscules pour éviter les bugs
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['email'] = $user['email'];

                if($_SESSION['role'] == 'admin')
                {
                    header("Location: admin_dashboard.php");
                }
                else
                {
                    header("Location: reservation.php");
                }
                exit();
            }
            else
            {
                $error = "Email ou mot de passe incorrect";
            }
        }
        else
        {
            $error = "Email ou mot de passe incorrect";
        }
        mysqli_stmt_close($stmt);
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Connexion</title>

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
    padding:20px;
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

</style>

</head>

<body>

<div class="box">

<h1>Connexion</h1>

<?php
if($error != "")
{
    echo "<div class='error'>$error</div>";
}
?>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Mot de passe" required>

<button type="submit" name="login">
    Se connecter
</button>

</form>
<div style="text-align:center; margin-top:20px; color:#fff; font-weight:bold;">

Pas encore de compte ?

<a href="register.php" style="color:#ff7b00; text-decoration:none; margin-left:5px;">
Créer un compte
</a>

</div>
<a href="index.php" style="display: block; text-align: center; margin-top: 15px; color: #aaa; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#aaa'">
    ← Retour à l'accueil
</a>

</div>

</body>
</html>