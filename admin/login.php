<?php
session_start();
require_once '../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Identifiants par défaut pour vous connecter :
    // Identifiant : admin
    // Mot de passe : admin123
    $admin_user = 'admin';
    $admin_password = 'admin123'; 

    if ($username === $admin_user && $password === $admin_password) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Identifiants incorrects.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administration</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #0f1115; color: white; font-family: sans-serif;">

    <div style="background: #181b22; padding: 3rem; border-radius: 12px; border: 1px solid #262a34; width: 100%; max-width: 400px;">
        <h2 style="margin-bottom: 1.5rem; text-align: center;">Admin <span style="color: #e50914;">Panel</span></h2>
        
        <?php if($error): ?>
            <p style="color: #e50914; background: rgba(229,9,20,0.1); padding: 0.8rem; border-radius: 6px; font-size: 0.9rem; margin-bottom: 1rem;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: #a0a5b5;">Identifiant</label>
                <input type="text" name="username" style="width: 100%; padding: 0.8rem; background: #0f1115; border: 1px solid #262a34; color: white; border-radius: 6px;" required autocomplete="off">
            </div>
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: #a0a5b5;">Mot de passe</label>
                <input type="password" name="password" style="width: 100%; padding: 0.8rem; background: #0f1115; border: 1px solid #262a34; color: white; border-radius: 6px;" required>
            </div>
            <button type="submit" style="display: inline-block; width: 100%; background: #e50914; color: white; text-align: center; padding: 0.8rem; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">Se connecter</button>
        </form>
    </div>

</body>
</html>
