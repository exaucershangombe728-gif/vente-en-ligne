<?php
require_once '../config.php';
require_once 'auth.php'; // Empêche un utilisateur externe d'appeler ce script

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // Suppression en BDD (Les contraintes ON DELETE CASCADE s'occupent d'historiser ou lier proprement les anciennes commandes si nécessaire)
    $stmt = $pdo->prepare("DELETE FROM voitures WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: dashboard.php');
exit;
?>
