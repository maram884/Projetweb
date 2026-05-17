<?php
session_start();

// Vérification stricte de la session
if(!isset($_SESSION['role']))
{
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] !== 'user')
{
    header("Location: admin_dashboard.php");
    exit();
}

include("config.php");

$message = "";
$status = ""; 

$aujourdhui = date('Y-m-d');
$max_date = date('Y-m-d', strtotime('+5 days'));

if(isset($_POST['reserver']))
{
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $date_reservation = $_POST['date_reservation'];
    $heure_reservation = $_POST['heure_reservation'];
    $personnes = intval($_POST['personnes']);
    $message_client = trim($_POST['message']);

    // --- VALIDATION CÔTÉ SERVEUR ---
    if ($date_reservation < $aujourdhui || $date_reservation > $max_date) {
        $message = "La date doit être comprise entre aujourd'hui et les 5 prochains jours.";
        $status = "error";
    } 
    elseif ($heure_reservation < "10:00" || $heure_reservation > "23:00") {
        $message = "L'heure doit être comprise entre 10h00 et 23h00.";
        $status = "error";
    }
    // Limite à 10 personnes max
    elseif ($personnes <= 0 || $personnes > 10) {
        $message = "Le nombre de personnes doit être compris entre 1 et 10.";
        $status = "error";
    }
    // Vérification téléphone : exactement 8 chiffres
    elseif (!preg_match('/^[0-9]{8}$/', $telephone)) {
        $message = "Le numéro de téléphone doit contenir exactement 8 chiffres.";
        $status = "error";
    }
    else {

        // Requête préparée pour l'insertion
        $sql = "INSERT INTO reservations (nom, prenom, email, telephone, date_reservation, heure_reservation, personnes, message) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssssssis",
                $nom,
                $prenom,
                $email,
                $telephone,
                $date_reservation,
                $heure_reservation,
                $personnes,
                $message_client
            );
            
            if(mysqli_stmt_execute($stmt))
            {
                $message = "Votre réservation a été effectuée avec succès !";
                $status = "success";
            }
            else
            {
                $message = "Une erreur est survenue lors de la réservation.";
                $status = "error";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver une Table | Restaurant</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background:
            linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
            url("images/background.jpg");

            background-size: cover;
            background-position: center;
            background-attachment: fixed;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 700px;
            margin: auto;
        }

        .reservation-box {

            width: 100%;
            padding: 40px;
            border-radius: 20px;

            background: rgba(255, 255, 255, 0.08);

            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);

            border: 1px solid rgba(255, 255, 255, 0.15);

            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .reservation-box h1 {

            text-align: center;
            margin-bottom: 30px;

            color: #fff;

            font-size: 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .alert-box {

            padding: 14px;
            margin-bottom: 25px;

            border-radius: 10px;

            text-align: center;
            font-size: 15px;
            font-weight: 500;
        }

        .alert-box.success {

            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;

            border: 1px solid rgba(46, 204, 113, 0.4);
        }

        .alert-box.error {

            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;

            border: 1px solid rgba(231, 76, 60, 0.4);
        }

        .row {
            display: flex;
            gap: 20px;
        }

        .input-group {

            flex: 1;

            margin-bottom: 20px;

            display: flex;
            flex-direction: column;
        }

        .input-group label {

            color: #f3f3f3;

            font-size: 14px;

            margin-bottom: 8px;

            font-weight: 500;
        }

        .input-group input,
        .input-group textarea {

            width: 100%;

            padding: 12px 16px;

            border-radius: 10px;

            border: 1px solid rgba(255, 255, 255, 0.25);

            background: rgba(0, 0, 0, 0.4);

            color: #fff;

            font-size: 15px;

            outline: none;

            transition: all 0.3s ease;
        }

        .input-group input:focus,
        .input-group textarea:focus {

            border-color: #ff7b00;

            background: rgba(0, 0, 0, 0.6);

            box-shadow: 0 0 8px rgba(255, 123, 0, 0.3);
        }

        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {

            filter: invert(1);

            cursor: pointer;
        }

        input::placeholder,
        textarea::placeholder {
            color: #bbb;
        }

        button.btn-submit {

            width: 100%;

            padding: 14px;

            border: none;

            background: #ff7b00;

            color: white;

            font-size: 16px;
            font-weight: 600;

            border-radius: 10px;

            cursor: pointer;

            transition: background 0.3s ease, transform 0.1s ease;

            margin-top: 10px;
        }

        button.btn-submit:hover {
            background: #e06c00;
        }

        button.btn-submit:active {
            transform: scale(0.98);
        }

        .form-footer {

            display: flex;
            justify-content: space-between;

            margin-top: 25px;

            padding-top: 15px;

            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-footer a {

            color: #ccc;

            text-decoration: none;

            font-size: 14px;

            transition: color 0.3s;
        }

        .form-footer a:hover {
            color: #ff7b00;
        }

        @media (max-width: 580px) {

            .row {
                flex-direction: column;
                gap: 0;
            }

            .reservation-box {
                padding: 25px 20px;
            }
        }

    </style>
</head>

<body>

<div class="container">

    <div class="reservation-box">

        <h1>Réserver une table</h1>

        <?php if($message != ""): ?>

            <div class="alert-box <?php echo $status; ?>">
                <?php echo $message; ?>
            </div>

        <?php endif; ?>

        <form method="POST" id="reservationForm">

            <div class="row">

                <div class="input-group">
                    <label>Nom</label>

                    <input
                    type="text"
                    name="nom"
                    required>
                </div>

                <div class="input-group">
                    <label>Prénom</label>

                    <input
                    type="text"
                    name="prenom"
                    required>
                </div>

            </div>

            <div class="row">

                <div class="input-group">

                    <label>Adresse Email</label>

                    <input
                    type="email"
                    name="email"
                    placeholder="exemple@gmail.com"
                    required>

                </div>

                <div class="input-group">

                    <label>Téléphone</label>

                    <input
                    type="tel"
                    name="telephone"
                    placeholder="12345678"
                    pattern="[0-9]{8}"
                    maxlength="8"
                    required>

                </div>

            </div>

            <div class="row">

                <div class="input-group">

                    <label>Date de réservation</label>

                    <input
                    type="date"
                    name="date_reservation"
                    min="<?php echo $aujourdhui; ?>"
                    max="<?php echo $max_date; ?>"
                    required>

                </div>

                <div class="input-group">

                    <label>Heure</label>

                    <input
                    type="time"
                    name="heure_reservation"
                    min="10:00"
                    max="23:00"
                    required>

                </div>

            </div>

            <div class="input-group">

                <label>Nombre de convives (Maximum 10)</label>

                <input
                type="number"
                name="personnes"
                min="1"
                max="10"
                required>

            </div>

            <div class="input-group">

                <label>Demande particulière (Optionnel)</label>

                <textarea
                name="message"
                rows="4"
                placeholder="Un événement particulier ? Une allergie ? Dites-le nous..."></textarea>

            </div>

            <button type="submit" name="reserver" class="btn-submit">
                Confirmer la réservation
            </button>

        </form>

        <div class="form-footer">

            <a href="index.php">
                ← Retour à l'accueil
            </a>

            <a href="mon_compte.php" style="color: #ff7b00; font-weight: 500;">
                Mon Compte
            </a>

            <a href="logout.php" style="opacity: 0.8;">
                Se déconnecter
            </a>

        </div>

    </div>

</div>

<script>

document.getElementById("reservationForm")
.addEventListener("submit", function(e){

    let personnes = parseInt(
        document.querySelector("input[name='personnes']").value
    );

    let dateRes = document.querySelector(
        "input[name='date_reservation']"
    ).value;

    let heureRes = document.querySelector(
        "input[name='heure_reservation']"
    ).value;

    let telephone = document.querySelector(
        "input[name='telephone']"
    ).value;

    let aujourdhui = "<?php echo $aujourdhui; ?>";
    let maxDate = "<?php echo $max_date; ?>";

    // Vérification téléphone
    if(!/^[0-9]{8}$/.test(telephone)) {

        alert("Le numéro de téléphone doit contenir exactement 8 chiffres.");

        e.preventDefault();

        return;
    }

    // Vérification JS pour max 10
    if(personnes <= 0 || personnes > 10) {

        alert("Le nombre de personnes doit être compris entre 1 et 10.");

        e.preventDefault();

        return;
    }

    if(dateRes < aujourdhui || dateRes > maxDate) {

        alert("Veuillez choisir une date entre aujourd'hui et les 5 prochains jours.");

        e.preventDefault();

        return;
    }

    if(heureRes < "10:00" || heureRes > "23:00") {

        alert("Les réservations sont disponibles uniquement entre 10h00 et 23h00.");

        e.preventDefault();

        return;
    }

});

</script>

</body>
</html>