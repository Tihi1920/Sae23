<?php 
/* En-tete.php - Reusable application header component with navigation logic */
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
// Isolate active file path names to dynamically handle active context styling
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GTC - Supervision SAÉ 23</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1><span>GTC</span> </h1>
        <p>Gestion Technique Centralisée • SAÉ 23</p>
        <nav>
            <ul>
                <li><a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Accueil</a></li>
                <li><a href="Sae23 presentation.php" class="<?= $current_page === 'Sae23 presentation.php' ? 'active' : '' ?>">Présentation Projet</a></li>
                
                <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['gestionnaire', 'admin'])): ?>
                    <li><a href="Gestion.php" class="<?= $current_page === 'Gestion.php' ? 'active' : '' ?>">Gestion</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="Administrateur.php" class="<?= $current_page === 'Administrateur.php' ? 'active' : '' ?>">Administration Globale</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'gestionnaire'): ?>
                    <li><a href="Administrateur.php" class="<?= $current_page === 'Administrateur.php' ? 'active' : '' ?>">Gérer mes capteurs</a></li>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="deconnexion.php">Déconnexion (<?= htmlspecialchars($_SESSION['user']) ?>)</a></li>
                <?php else: ?>
                    <li><a href="connexion.php" class="<?= $current_page === 'connexion.php' ? 'active' : '' ?>">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
