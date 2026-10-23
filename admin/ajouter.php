<?php
require_once '../config.php';
require_once 'auth.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marque = trim($_POST['marque']);
    $modele = trim($_POST['modele']);
    $annee = filter_input(INPUT_POST, 'annee', FILTER_VALIDATE_INT);
    $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
    $description = trim($_POST['description']);

    // Gestion du téléversement d'image ou fallback URL
    $image_url = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['image_file']['name']);
        
        // Dossier de destination local sur XAMPP (Créer le dossier 'uploads' s'il n'existe pas)
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            $image_url = 'uploads/' . $file_name; // URL relative pour la base de données
        }
    } elseif (!empty($_POST['image_url'])) {
        $image_url = trim($_POST['image_url']);
    }

    if ($marque && $modele && $annee && $prix && $image_url) {
        $stmt = $pdo->prepare("INSERT INTO voitures (marque, modele, annee, prix, image, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$marque, $modele, $annee, $prix, $image_url, $description]);
        $message = "Véhicule inséré avec succès dans le catalogue !";
    } else {
        $message = "Erreur : Veuillez remplir correctement tous les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Véhicule | Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-grid { display: grid; grid-template-columns: 1fr 3fr; gap: 2rem; padding: 2rem 5%; }
        .sidebar { background: var(--card-bg); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border); height: fit-content; }
        .sidebar a { display: block; color: var(--text-muted); text-decoration: none; padding: 0.8rem; border-radius: 6px; margin-bottom: 0.5rem; }
        .sidebar a:hover, .sidebar a.active { background: var(--accent); color: white; }
    </style>
</head>
<body>

<header style="padding: 1rem 5%;">
    <div class="logo">PANNEAU<span>ADMIN</span></div>
</header>

<div class="admin-grid">
    <div class="sidebar">
        <h3>Menu</h3>
        <hr style="border-color: var(--border); margin: 1rem 0;">
        <a href="dashboard.php">Vue d'ensemble</a>
        <a href="ajouter.php" class="active">Ajouter une voiture</a>
    </div>

    <div>
        <h2>Ajouter un nouveau véhicule d'exception</h2>
        
        <?php if($message): ?>
            <p style="color: #2ecc71; background: rgba(46,204,113,0.1); padding: 1rem; border-radius: 6px; margin: 1rem 0;"><?= $message ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" style="background: var(--card-bg); padding: 2.5rem; border-radius: var(--radius); border: 1px solid var(--border); margin-top: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>Marque *</label>
                    <input type="text" name="marque" class="form-control" required placeholder="Ex: Ferrari">
                </div>
                <div class="form-group">
                    <label>Modèle *</label>
                    <input type="text" name="modele" class="form-control" required placeholder="Ex: F8 Tributo">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
                <div class="form-group">
                    <label>Année de fabrication *</label>
                    <input type="number" name="annee" class="form-control" required value="2026">
                </div>
                <div class="form-group">
                    <label>Prix de vente (€) *</label>
                    <input type="number" step="0.01" name="prix" class="form-control" required placeholder="Ex: 245000">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
                <div class="form-group">
                    <label>Uploader une image locale</label>
                    <input type="file" name="image_file" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Ou lien d'image externe (URL)</label>
                    <input type="url" name="image_url" class="form-control" placeholder="https://exemple.com">
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label>Description & Caractéristiques techniques</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Options, puissance fiscale, état du véhicule..."></textarea>
            </div>

            <button type="submit" class="btn" style="margin-top: 1.5rem; width: auto; padding: 0.8rem 3rem;">Mettre en vente</button>
        </form>
    </div>
</div>

</body>
</html>
