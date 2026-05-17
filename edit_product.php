<?php
//Ce script permet à un administrateur de modifier un produit, avec possibilité de :changer le nom ,remplacer l’image ,supprimer l’ancienne image du serveur
session_start();
include("config.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); } //vérifie que l’utilisateur est connecté et qu’il est admin.

//On récupère l’ID du produit depuis l’URL
$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM produits WHERE id = $id");
$product = mysqli_fetch_assoc($res);

if(isset($_POST['update'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    
    // 1. Vérifier si une nouvelle image a été sélectionnée
    if(!empty($_FILES["image_p"]["name"])) {
        $dossier = "images/menu/";
        $nom_image = time() . "_" . basename($_FILES["image_p"]["name"]);
        $chemin_final = $dossier . $nom_image;

        if(move_uploaded_file($_FILES["image_p"]["tmp_name"], $chemin_final)) {
            // Supprimer l'ancienne image du dossier pour ne pas encombrer le serveur
            if(file_exists($product['image_path'])) {
                unlink($product['image_path']);
            }
            // Mettre à jour le NOM et le CHEMIN de l'image
            mysqli_query($conn, "UPDATE produits SET nom='$nom', image_path='$chemin_final' WHERE id=$id");
        }
    } else {
        // 2. Si pas de nouvelle image, on met à jour uniquement le nom
        mysqli_query($conn, "UPDATE produits SET nom='$nom' WHERE id=$id");
    }
    
    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
    <style>
        body { background: #14171c; color: white; padding: 50px; font-family: sans-serif; }
        .box { background: #1c1f26; padding: 30px; border-radius: 15px; max-width: 450px; margin: auto; border: 1px solid rgba(255,255,255,0.1); }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #252932; border: 1px solid #444; color: white; border-radius: 8px; }
        button { background: #ff7b00; color: white; border: none; padding: 12px; width: 100%; cursor: pointer; border-radius: 8px; font-weight: bold; margin-top: 10px; }
        .current-img { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; margin: 10px 0; border: 2px solid #ff7b00; }
        label { font-size: 14px; color: #aaa; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Modifier le produit</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Nom du produit</label>
            <input type="text" name="nom" value="<?php echo htmlspecialchars($product['nom']); ?>" required>

            <label>Image actuelle :</label><br>
            <img src="<?php echo $product['image_path']; ?>" class="current-img"><br>

            <label>Remplacer l'image (laisser vide pour garder l'actuelle) :</label>
            <input type="file" name="image_p" accept="image/*">

            <button type="submit" name="update">Enregistrer les modifications</button>
            <a href="manage_products.php" style="display:block; text-align:center; color:#888; margin-top:15px; text-decoration:none;">Annuler</a>
        </form>
    </div>
</body>
</html>
