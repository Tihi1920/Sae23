<?php
/* connexion.php - User authentication and role-based session initialization */
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'DB.php';

$error = "";

// Process user login request submitted via HTTP POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    // Sanitize user inputs to shield against potential SQL Injection attacks
    $login    = mysqli_real_escape_string($conn, $_POST['login']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Retrieve matching user credentials record from Administration table
    $sql    = "SELECT login, mdp FROM Administration WHERE login = '$login'";
    $result = mysqli_query($conn, $sql);
    $user   = mysqli_fetch_assoc($result);

    // Verify record existence and evaluate plain-text password compliance
    if ($user && $password === $user['mdp']) {

        // Dynamically assign authorization privileges using username strings
        if ($login === 'admin') {
            $_SESSION['role'] = 'admin';
        } else {
            $_SESSION['role'] = 'gestionnaire';
        }

        // Initialize user session variables and redirect to homepage dashboard
        $_SESSION['user'] = $user['login'];
        header("Location: index.php");
        exit;

    } else {
        // Enforce validation failure feedback messaging
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Supervision IoT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <section>
            <h2>Supervision IoT</h2>

            <?php if (!empty($error)): ?>
                <p class="message-erreur"><?= $error ?></p>
            <?php endif; ?>

            <form action="connexion.php" method="POST">
                <label>Identifiant d'accès :</label>
                <input type="text" name="login" placeholder="Identifiant" required>

                <label>Mot de passe associé :</label>
                <input type="password" name="password" placeholder="Mot de passe" required>

                <button type="submit">Se connecter</button>
            </form>
        </section>
    </main>
</body>
</html>
