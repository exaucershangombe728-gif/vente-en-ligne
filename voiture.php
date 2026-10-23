<?php
require_once 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM voitures WHERE id = ?");
$stmt->execute([$id]);
$voiture = $stmt->fetch();

if (!$voiture) {
    die("Véhicule introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($voiture['marque'].' '.$voiture['modele']) ?> | Détails</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <a href="index.php" class="logo" style="text-decoration: none; color: inherit;">EXAU<span>CARS</span></a>
</header>

<main class="detail-container">
    <div>
        <img src="<?= htmlspecialchars($v['image']) ?>" alt="Vehicule" class="detail-img">
    </div>
    
    <div>
        <h1><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h1>
        <div class="price"><?= number_format($voiture['prix'], 2, ',', ' ') ?> €</div>
        <p style="color: var(--text-muted); margin-bottom: 2rem;"><?= htmlspecialchars($voiture['description']) ?></p>

        <!-- Formulaire de commande immédiate -->
        <div style="background: var(--card-bg); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border);">
            <h3 style="margin-bottom: 1.5rem;">Passer la commande en ligne</h3>
            <form action="commander.php" method="POST">
                <input type="hidden" name="voiture_id" value="<?= $voiture['id'] ?>">
                
                <div class="form-group">
                    <label>Votre nom complet</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Adresse e-mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <button type="submit" class="btn" style="margin-top: 1rem;">Procéder au paiement sécurisé</button>
            </form>
        </div>
    </div>
</main>

</body>
</html>
