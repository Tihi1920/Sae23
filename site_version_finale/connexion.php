<?php
// connexion.php - Authentification des utilisateurs
// Style procédural mysqli

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'DB.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $login    = mysqli_real_escape_string($conn, $_POST['login']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // On cherche le compte dans la table Administration
    $sql    = "SELECT login, mdp FROM Administration WHERE login = '$login'";
    $result = mysqli_query($conn, $sql);
    $user   = mysqli_fetch_assoc($result);

    if ($user && $password === $user['mdp']) {

        // Le rôle est déterminé par le login
        if ($login === 'admin') {
            $_SESSION['role'] = 'admin';
        } else {
            $_SESSION['role'] = 'gestionnaire';
        }

        $_SESSION['user'] = $user['login'];
        header("Location: index.php");
        exit;

    } else {
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
                <div class="message-erreur"><?= $error ?></div>
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
