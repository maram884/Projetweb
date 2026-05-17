<?php
session_start();

// Vérification session
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'user')
{
    header("Location: login.php");
    exit();
}

include("config.php");

// Messages de notification
$success_msg = "";
$error_msg = "";

// Récupération email utilisateur connecté
$user_email = $_SESSION['email'] ?? '';
$user_email_clean = mysqli_real_escape_string($conn, $user_email);

// ===== TRAITEMENT DE LA MODIFICATION (POST) =====
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier_profil'])) {
    $nouveau_nom = mysqli_real_escape_string($conn, trim($_POST['nom']));
    $nouveau_prenom = mysqli_real_escape_string($conn, trim($_POST['prenom']));
    $nouvel_email = mysqli_real_escape_string($conn, trim($_POST['email']));

    if (!empty($nouveau_nom) && !empty($nouveau_prenom) && !empty($nouvel_email)) {
        
        // Si l'utilisateur veut changer d'email, on vérifie si le nouvel email n'est pas déjà pris
        $email_existe = false;
        if ($nouvel_email !== $user_email_clean) {
            $check_email_query = "SELECT email FROM users WHERE email = '$nouvel_email'";
            $check_res = mysqli_query($conn, $check_email_query);
            if (mysqli_num_rows($check_res) > 0) {
                $email_existe = true;
                $error_msg = "Cet email est déjà utilisé par un autre compte.";
            }
        }

        if (!$email_existe) {
            // Début d'une transaction pour s'assurer que TOUT se met à jour correctement
            mysqli_begin_transaction($conn);

            try {
                // 1. Mettre à jour l'utilisateur
                $update_user = "UPDATE users SET nom = '$nouveau_nom', prenom = '$nouveau_prenom', email = '$nouvel_email' WHERE email = '$user_email_clean'";
                mysqli_query($conn, $update_user);

                // 2. Mettre à jour les réservations liées à l'ancien email pour NE PAS LES PERDRE
                $update_reservations = "UPDATE reservations SET email = '$nouvel_email' WHERE email = '$user_email_clean'";
                mysqli_query($conn, $update_reservations);

                // Validation des changements dans la base de données
                mysqli_commit($conn);

                // 3. Mettre à jour la session actuelle avec les nouvelles infos
                $_SESSION['email'] = $nouvel_email;
                $user_email_clean = $nouvel_email; // On actualise la variable locale pour le reste du script

                $success_msg = "Profil mis à jour avec succès !";
            } catch (Exception $e) {
                // En cas d'erreur, on annule tout
                mysqli_rollback($conn);
                $error_msg = "Une erreur est survenue lors de la mise à jour.";
            }
        }
    } else {
        $error_msg = "Veuillez remplir tous les champs.";
    }
}

// ===== RÉCUPÉRATION INFORMATIONS USER =====
$nom_user = "";
$prenom_user = "";
$email_user = "";

if(!empty($user_email_clean))
{
    $query_user = "SELECT nom, prenom, email 
                   FROM users 
                   WHERE email = '$user_email_clean'";

    $result_user = mysqli_query($conn, $query_user);

    if($result_user && mysqli_num_rows($result_user) > 0)
    {
        $user_data = mysqli_fetch_assoc($result_user);

        $nom_user = $user_data['nom'];
        $prenom_user = $user_data['prenom'];
        $email_user = $user_data['email'];
    }
}

// ===== HISTORIQUE RÉSERVATIONS =====
$query_reservations = "SELECT date_reservation,
                              heure_reservation,
                              personnes,
                              message
                       FROM reservations
                       WHERE email = '$user_email_clean'
                       ORDER BY date_reservation DESC,
                                heure_reservation DESC";

$result_reservations = mysqli_query($conn, $query_reservations);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Mon Compte | Restaurant</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url("images/background.jpg");
    background-size:cover;
    background-position:center;
    background-attachment:fixed;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.container{
    width:100%;
    max-width:900px;
}

.account-box{
    width:100%;
    padding:40px;
    border-radius:20px;
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(16px);
    -webkit-backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.15);
    box-shadow:0 15px 35px rgba(0,0,0,0.4);
    color:white;
}

h1,h2{
    font-weight:600;
    margin-bottom:20px;
}

h1{
    text-align:center;
    font-size:32px;
    padding-bottom:15px;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

h2{
    color:#ff7b00;
    margin-top:20px;
}

/* FORMULAIRE / PROFIL INPUTS */
.profile-form-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:15px;
    background:rgba(0,0,0,0.2);
    padding:20px;
    border-radius:12px;
}

.info-group p{
    font-size:14px;
    color:#bbb;
    margin-bottom:5px;
}

.info-group input{
    width: 100%;
    padding: 10px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: white;
    font-size: 16px;
    outline: none;
    transition: 0.3s;
}

.info-group input:focus{
    border-color: #ff7b00;
    background: rgba(255, 255, 255, 0.15);
}

.btn-submit {
    background: #ff7b00;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s;
    display: block;
    margin-left: auto;
}

.btn-submit:hover {
    background: #e06c00;
    transform: translateY(-2px);
}

/* MESSAGES */
.alert {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    text-align: center;
}
.alert-success { background: rgba(40, 167, 69, 0.2); border: 1px solid #28a745; color: #2da949; }
.alert-danger { background: rgba(220, 53, 69, 0.2); border: 1px solid #dc3545; color: #ea4353; }

/* TABLE */
.table-container{
    width:100%;
    overflow-x:auto;
    margin-top: 15px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:14px;
    text-align:left;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

th{
    background:rgba(255,123,0,0.15);
    color:#ff7b00;
    font-size:13px;
}

td{
    color:#eee;
}

tr:hover td{
    background:rgba(255,255,255,0.03);
}

.no-data{
    text-align:center;
    color:#bbb;
    padding:20px;
}

/* FOOTER */
.form-footer{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:10px;
    margin-top:30px;
    padding-top:20px;
    border-top:1px solid rgba(255,255,255,0.1);
}

.form-footer a{
    color:#ddd;
    text-decoration:none;
    transition:0.3s;
}

.form-footer a:hover{
    color:#ff7b00;
}
</style>
</head>

<body>

<div class="container">

    <div class="account-box">

        <h1>Mon Espace Personnel</h1>

        <!-- ALERTES -->
        <?php if(!empty($success_msg)): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <?php if(!empty($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <!-- FORMULAIRE MODIFICATION -->
        <h2>Mes Informations</h2>
        <form action="" method="POST">
            <div class="profile-form-grid">
                <div class="info-group">
                    <p><label for="nom">Nom</label></p>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom_user); ?>" required>
                </div>

                <div class="info-group">
                    <p><label for="prenom">Prénom</label></p>
                    <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom_user); ?>" required>
                </div>

                <div class="info-group">
                    <p><label for="email">Email</label></p>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email_user); ?>" required>
                </div>
            </div>
            <button type="submit" name="modifier_profil" class="btn-submit">Enregistrer les modifications</button>
        </form>

        <!-- TABLE RÉSERVATIONS -->
        <h2>Mes Réservations</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Convives</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if($result_reservations && mysqli_num_rows($result_reservations) > 0)
                {
                    while($row = mysqli_fetch_assoc($result_reservations))
                    {
                        $date_formattee = date("d/m/Y", strtotime($row['date_reservation']));
                        $heure_formattee = date("H:i", strtotime($row['heure_reservation']));
                ?>
                    <tr>
                        <td><?php echo $date_formattee; ?></td>
                        <td><?php echo $heure_formattee; ?></td>
                        <td><?php echo htmlspecialchars($row['personnes']); ?> personnes</td>
                        <td>
                            <?php echo !empty($row['message']) ? htmlspecialchars($row['message']) : "Aucun message"; ?>
                        </td>
                    </tr>
                <?php
                    }
                }
                else
                {
                ?>
                    <tr>
                        <td colspan="4" class="no-data">
                            Vous n'avez aucune réservation.
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="form-footer">
            <a href="reservation.php">← Nouvelle réservation</a>
            <a href="index.html">Accueil</a>
            <a href="logout.php">Déconnexion</a>
        </div>

    </div>
</div>

</body>
</html>