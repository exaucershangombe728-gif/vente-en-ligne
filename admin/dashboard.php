<?php
require_once '../config.php';
require_once 'auth.php';

// Récupération des voitures
$voitures = $pdo->query("SELECT * FROM voitures ORDER BY id DESC")->fetchAll();

// Récupération des commandes avec les détails du véhicule associé
$commandes = $pdo->query("
    SELECT c.*, v.marque, v.modele, v.prix 
    FROM commandes c 
    JOIN voitures v ON c.voiture_id = v.id 
    ORDER BY c.cree_le DESC
")->fetchAll();

// Calcul du Chiffre d'Affaires total validé
$ca_total = $pdo->query("SELECT SUM(v.prix) FROM commandes c JOIN voitures v ON c.voiture_id = v.id WHERE c.statut_paiement = 'Payé'")->fetchColumn() ?: 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | EXAU Cars</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-grid { display: grid; grid-template-columns: 1fr 3fr; gap: 2rem; padding: 2rem 5%; }
        .sidebar { background: var(--card-bg); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border); height: fit-content; }
        .sidebar a { display: block; color: var(--text-muted); text-decoration: none; padding: 0.8rem; border-radius: 6px; margin-bottom: 0.5rem; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: var(--accent); color: white; }
        .stats-box { background: var(--card-bg); border: 1px solid var(--border); padding: 1.5rem; border-radius: var(--radius); text-align: center; }
        table { width: 100%; border-collapse: collapse; background: var(--card-bg); border-radius: var(--radius); overflow: hidden; border: 1px solid var(--border); margin-top: 1.5rem; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: rgba(255,255,255,0.02); color: var(--text-muted); }
        .status-badge { padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .status-paid { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
        .status-pending { background: rgba(241, 196, 15, 0.2); color: #f1c40f; }
    </style>
</head>
<body>

<header style="padding: 1rem 5%;">
    <div class="logo">PANNEAU<span>ADMIN</span></div>
    <div style="color: var(--text-muted);">Session : <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong> | <a href="logout.php" style="color: var(--accent); text-decoration: none; margin-left: 15px;">Déconnexion</a></div>
</header>

<div class="admin-grid">
    <!-- Menu latéral -->
    <div class="sidebar">
        <h3>Menu</h3>
        <hr style="border-color: var(--border); margin: 1rem 0;">
        <a href="dashboard.php" class="active">Vue d'ensemble</a>
        <a href="ajouter.php">Ajouter une voiture</a>
        <a href="../index.php" target="_blank">Voir le site public ↗</a>
    </div>

    <!-- Contenu Principal -->
    <div>
        <!-- Cartes Statistiques -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="stats-box">
                <p style="color: var(--text-muted);">Chiffre d'Affaires</p>
                <h2 style="color: #2ecc71; font-size: 2rem;"><?= number_format($ca_total, 2, ',', ' ') ?> €</h2>
            </div>
            <div class="stats-box">
                <p style="color: var(--text-muted);">Commandes Passées</p>
                <h2 style="font-size: 2rem;"><?= count($commandes) ?></h2>
            </div>
            <div class="stats-box">
                <p style="color: var(--text-muted);">Flotte en Ligne</p>
                <h2 style="font-size: 2rem;"><?= count($voitures) ?></h2>
            </div>
        </div>

        <!-- Section Commandes -->
        <h2>Suivi des Commandes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Véhicule</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $c): ?>
                <tr>
                    <td>#<?= $c['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($c['nom_client']) ?></strong><br>
                        <span style="font-size:0.85rem; color:var(--text-muted);"><?= htmlspecialchars($c['email_client']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($c['marque'] . ' ' . $c['modele']) ?></td>
                    <td><?= number_format($c['prix'], 2, ',', ' ') ?> €</td>
                    <td>
                        <span class="status-badge <?= $c['statut_paiement'] === 'Payé' ? 'status-paid' : 'status-pending' ?>">
                            <?= $c['statut_paiement'] ?>
                        </span>
                    </td>
                    <td style="font-size:0.9rem; color:var(--text-muted);"><?= date('d/m/Y H:i', strtotime($c['cree_le'])) ?></td>
                </tr>
                <?php endforeach; if(empty($commandes)) echo "<tr><td colspan='6' style='text-align:center; color:var(--text-muted);'>Aucune commande enregistrée.</td></tr>"; ?>
            </tbody>
        </table>

        <!-- Section Gestion Véhicules -->
        <h2 style="margin-top: 3rem;">Gestion du Parc Automobile</h2>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Prix affiché</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voitures as $v): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($v['image']) ?>" style="width: 70px; height: 45px; object-fit: cover; border-radius: 4px;"></td>
                    <td><strong><?= htmlspecialchars($v['marque'].' '.$v['modele']) ?></strong></td>
                    <td><?= $v['annee'] ?></td>
                    <td style="color: var(--accent); font-weight: bold;"><?= number_format($v['prix'], 2, ',', ' ') ?> €</td>
                    <td>
                        <a href="supprimer.php?id=<?= $v['id'] ?>" class="status-badge" style="background: rgba(229,9,20,0.1); color: var(--accent); text-decoration: none;" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce véhicule ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
