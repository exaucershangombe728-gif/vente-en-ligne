<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voiture_id = filter_input(INPUT_POST, 'voiture_id', FILTER_VALIDATE_INT);
    $nom = filter_input(INPUT_POST, 'nom', FILTER_DEFAULT);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$voiture_id || !$nom || !$email) {
        die("Données du formulaire invalides.");
    }

    // 1. Vérifier la disponibilité de la voiture
    $stmt = $pdo->prepare("SELECT prix FROM voitures WHERE id = ?");
    $stmt->execute([$voiture_id]);
    $voiture = $stmt->fetch();

    if (!$voiture) {
        die("Produit introuvable.");
    }

    // 2. Insérer la commande en BDD (Statut : En attente de paiement)
    $stmt = $pdo->prepare("INSERT INTO commandes (voiture_id, nom_client, email_client, statut_paiement) VALUES (?, ?, ?, 'En attente')");
    $stmt->execute([$voiture_id, $nom, $email]);
    $commande_id = $pdo->lastInsertId();

    /* 
    * 3. LOGIQUE DE PAIEMENT (EXEMPLE STRIPE)
    * Dans un environnement professionnel, vous utiliseriez le SDK Stripe :
    *
    * \Stripe\Stripe::setApiKey(STRIPE_PRIVATE_KEY);
    * $session = \Stripe\Checkout\Session::create([...]);
    * 
    * Pour XAMPP et cette démo, nous simulons une redirection de paiement réussi :
    */

    // Simulation de succès de paiement immédiat
    $stmtUpdate = $pdo->prepare("UPDATE commandes SET statut_paiement = 'Payé' WHERE id = ?");
    $stmtUpdate->execute([$commande_id]);

    echo "<script>
            alert('Félicitations ! Votre commande n°$commande_id a été enregistrée et payée avec succès.');
            window.location.href = 'index.php';
          </script>";
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>
