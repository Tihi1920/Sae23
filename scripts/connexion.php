<?php
// connexion.php - Authentification des utilisateurs
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'DB.php'; 

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $login = htmlspecialchars($_POST['login']); 
    $password = $_POST['password'];

    // 1. Requête préparée pour récupérer l'utilisateur
    $sql = "SELECT * FROM utilisateur WHERE login = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // 2. Validation du mot de passe (Note: Idéalement, utilisez password_verify)
    if ($user && $password === $user['mot_de_passe']) {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user'] = $user['login'];
        $_SESSION['role'] = $user['role'];

        // 3. Liaison avec le bâtiment si gestionnaire
        if ($user['role'] === 'gestionnaire') {
            $sql_bat = "SELECT id_batiment, nom_batiment FROM batiment WHERE login_gestionnaire = ?";
            $stmt_bat = mysqli_prepare($conn, $sql_bat);
            mysqli_stmt_bind_param($stmt_bat, "s", $user['login']);
            mysqli_stmt_execute($stmt_bat);
            $res_bat = mysqli_stmt_get_result($stmt_bat);
            
            if ($batiment = mysqli_fetch_assoc($res_bat)) {
                $_SESSION['id_batiment_gere'] = $batiment['id_batiment'];
                $_SESSION['nom_batiment_gere'] = $batiment['nom_batiment'];
            } else {
                $error = "Votre compte n'est lié à aucun bâtiment.";
                session_destroy();
            }
            mysqli_stmt_close($stmt_bat);
        }

        if (empty($error)) {
            header("Location: index.php");
            exit;
        }

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