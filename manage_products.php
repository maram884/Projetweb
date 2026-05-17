<?php
session_start();
include("config.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// --- LOGIQUE DE SUPPRESSION ---
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // On récupère le chemin de l'image pour la supprimer du dossier images
    $res = mysqli_query($conn, "SELECT image_path FROM produits WHERE id=$id");
    $row = mysqli_fetch_assoc($res);
    if($row && file_exists($row['image_path'])) {
        unlink($row['image_path']);
    }
    mysqli_query($conn, "DELETE FROM produits WHERE id=$id");
    header("Location: manage_products.php");
    exit();
}

// --- RÉCUPÉRATION DES PRODUITS ---
$query = "SELECT * FROM produits ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Produits</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #14171c; color: white; padding: 40px; }
        .container { max-width: 1000px; margin: auto; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-back { color: #a0a5b1; text-decoration: none; font-size: 14px; }
        
        .panel { background: #1c1f26; border-radius: 12px; padding: 20px; border: 1px solid rgba(255,255,255,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; color: #ff7b00; font-size: 13px; text-transform: uppercase; padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
        
        .img-prod { width: 60px; height: 60px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1); }
        .btn-edit { color: #ff7b00; text-decoration: none; font-weight: 500; margin-right: 15px; }
        .btn-delete { color: #ff4d4d; text-decoration: none; font-weight: 500; }
        .btn-edit:hover, .btn-delete:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <h1>Liste des Produits</h1>
        <a href="admin_dashboard.php" class="btn-back">← Retour au Dashboard</a>
    </div>

    <div class="panel">
        <table>
            <thead>
                <tr>
                    <th>Aperçu</th>
                    <th>Nom du Produit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><img src="<?php echo $row['image_path']; ?>" class="img-prod"></td>
                        <td><strong><?php echo htmlspecialchars($row['nom']); ?></strong></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn-edit">Modifier</a>
                            
                            <a href="manage_products.php?delete=<?php echo $row['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                               Supprimer
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: #888; padding: 40px;">Aucun produit trouvé dans le menu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>