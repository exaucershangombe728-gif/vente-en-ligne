<?php 
require_once 'config.php'; 

$stmt = $pdo->query("SELECT * FROM voitures ORDER BY id DESC");
$voitures = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EXAU Cars | Vente de véhicules d'exception</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">Exau<span>CARS</span></div>
</header>

<main>
    <div class="grid">
        <?php foreach ($voitures as $v): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($v['image']) ?>" alt="<?= htmlspecialchars($v['marque'].' '.$v['modele']) ?>" class="card-img">
                <div class="card-content">
                    <h3><?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?></h3>
                    <p style="color: var(--text-muted);"><?= $v['annee'] ?></p>
                    <div class="price"><?= number_format($v['prix'], 2, ',', ' ') ?> €</div>
                    <a href="voiture.php?id=<?= $v['id'] ?>" class="btn">Consulter les détails</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

</body>
</html>
