<?php
session_start();
include("config.php"); 

if(
   !isset($_SESSION['role']) ||
   $_SESSION['role'] !== 'admin'
)
{
    session_destroy();
    header("Location: login.php");
    exit();
}

// --- TRAITEMENT DE L'UPLOAD PRODUIT ---
$message = "";

if(isset($_POST['ajouter_produit'])) {
    $nom_p = mysqli_real_escape_string($conn, $_POST['nom_p']);
    $dossier = "images/menu/"; 
    if (!is_dir($dossier)) mkdir($dossier, 0777, true);

    $nom_image = time() . "_" . basename($_FILES["image_p"]["name"]);
    $chemin_final = $dossier . $nom_image;

    if(!empty($_FILES["image_p"]["name"])) {
        if(move_uploaded_file($_FILES["image_p"]["tmp_name"], $chemin_final)) {
            $sql = "INSERT INTO produits (nom, image_path) VALUES ('$nom_p', '$chemin_final')";
            mysqli_query($conn, $sql);
            $message = "<div class='alert-success'>Produit ajouté avec succès !</div>";
        } else {
            $message = "<div class='alert-error'>Erreur lors du téléchargement de l'image.</div>";
        }
    }
}

// --- CALCUL DES STATISTIQUES ---
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='user'"));
$total_res = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM reservations"));
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #14171c;
            color: #ffffff;
            display: flex;
            min-height: 100vh;
        }

        /* --- BARRE LATÉRALE (SIDEBAR) --- */
        .sidebar {
            width: 280px;
            background: #1c1f26;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            height: 100vh;
        }

        .sidebar-brand h2 {
            font-size: 20px;
            color: #ff7b00;
            font-weight: 600;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            list-style: none;
            flex-grow: 1;
        }

        .sidebar-menu li {
            margin-bottom: 12px;
        }

        .sidebar-menu a {
            display: block;
            color: #a0a5b1;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 123, 0, 0.1);
            color: #ff7b00;
        }

        .btn-logout {
            display: block;
            text-decoration: none;
            background: rgba(255, 77, 77, 0.1);
            color: #ff4d4d;
            border: 1px solid rgba(255, 77, 77, 0.2);
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #ff4d4d;
            color: #ffffff;
        }

        /* --- CONTENU PRINCIPAL --- */
        .main-content {
            margin-left: 280px;
            flex-grow: 1;
            padding: 40px;
            background: #14171c;
        }

        header {
            margin-bottom: 40px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 20px;
        }

        header h1 {
            font-size: 26px;
            font-weight: 600;
            color: #ffffff;
        }

        header p {
            color: #a0a5b1;
            font-size: 14px;
            margin-top: 5px;
        }

        /* --- SECTION STATISTIQUES --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-box {
            background: #1c1f26;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .stat-box h3 {
            font-size: 14px;
            color: #a0a5b1;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #ff7b00;
        }

        /* --- PANNEAUX DE GESTION --- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .panel {
            background: #1c1f26;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .panel h2 {
            font-size: 18px;
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 25px;
            border-left: 3px solid #ff7b00;
            padding-left: 12px;
        }

        /* --- FORMULAIRES & ENTRÉES --- */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #a0a5b1;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: #252932;
            color: #ffffff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #ff7b00;
            background: #2b303b;
        }

        input[type="file"] {
            cursor: pointer;
        }

        .btn-primary {
            width: 100%;
            background: #ff7b00;
            color: white;
            padding: 14px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #e06c00;
        }

        /* --- ALERTES --- */
        .alert-success {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.2);
            color: #00ff88;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: rgba(255, 77, 77, 0.1);
            border: 1px solid rgba(255, 77, 77, 0.2);
            color: #ff4d4d;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <!-- BARRE LATÉRALE -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>Administration</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" style="color: #ff7b00; background: rgba(255, 123, 0, 0.1);">Tableau de bord</a></li>
            <li><a href="manage_products.php">Gestion Produits</a></li>
            <li><a href="manage_users.php">Gestion Utilisateurs</a></li>
            <li><a href="manage_reservations.php">Gestion Réservations</a></li>
        </ul>
        <a href="logout.php" class="btn-logout">Déconnexion</a>
    </div>

    <!-- CONTENU PRINCIPAL -->
    <div class="main-content">
        <header>
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?></h1>
            <p>Voici l'état actuel de votre plateforme aujourd'hui.</p>
        </header>

        <!-- ZONE DES STATISTIQUES -->
        <div class="stats-grid">
            <div class="stat-box">
                <h3>Clients Inscrits</h3>
                <div class="stat-number"><?php echo $total_users; ?></div>
            </div>
            <div class="stat-box">
                <h3>Réservations Totales</h3>
                <div class="stat-number"><?php echo $total_res; ?></div>
            </div>
        </div>

        <!-- ZONE DE GESTION -->
        <div class="dashboard-grid">
            <!-- Form d'ajout de produit -->
            <div class="panel">
                <h2>Ajouter un Produit au Menu</h2>
                <?php echo $message; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nom du plat</label>
                        <input type="text" name="nom_p" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>Image du produit</label>
                        <input type="file" name="image_p" accept="image/*" required>
                    </div>
                    <button type="submit" name="ajouter_produit" class="btn-primary">Enregistrer le produit</button>
                </form>
            </div>

            <!-- Liens d'accès rapide -->
            <div class="panel">
                <h2>Actions Rapides</h2>
                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 10px;">
                    <a href="manage_products.php" class="btn-primary" style="text-decoration: none; text-align: center; background: #252932; border: 1px solid rgba(255,255,255,0.05);">Accéder aux Produits</a>
                    <a href="manage_users.php" class="btn-primary" style="text-decoration: none; text-align: center; background: #252932; border: 1px solid rgba(255,255,255,0.05);">Accéder aux Utilisateurs</a>
                    <a href="manage_reservations.php" class="btn-primary" style="text-decoration: none; text-align: center; background: #252932; border: 1px solid rgba(255,255,255,0.05);">Accéder aux Réservations</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>