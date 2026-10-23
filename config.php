<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'concessionnaire');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuration Paiement (Stripe exemple)
define('STRIPE_PRIVATE_KEY', 'sk_test_votre_cle_privee');
define('STRIPE_PUBLIC_KEY', 'pk_test_votre_cle_publique');
define('SITE_URL', 'http://localhost/votre-projet');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion critique : " . $e->getMessage());
}
?>
