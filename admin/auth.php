<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protection : si la variable de session n'existe pas, retour à la page de connexion
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit;
}
?>
