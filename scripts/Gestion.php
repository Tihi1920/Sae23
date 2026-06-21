<?php
// Gestion.php - Dashboard de suivi des capteurs
session_start();
require 'DB.php'; 

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$id_batiment = null;
$titre_page = "Supervision Globale";

if ($_SESSION['role'] === 'gestionnaire') {
    $id_batiment = (int)$_SESSION['id_batiment_gere']; 
    $titre_page = "Supervision : " . htmlspecialchars($_SESSION['nom_batiment_gere']); 
}

$sql = "SELECT c.id_capteur, c.nom_capteur, s.nom_salle, 
        MIN(m.valeur) as val_min, 
        MAX(m.valeur) as val_max,
        (SELECT valeur FROM mesure m2 WHERE m2.id_capteur = c.id_capteur ORDER BY id_mesure DESC LIMIT 1) as val_actuelle
        FROM capteur c
        JOIN salle s ON c.id_salle = s.id_salle
        LEFT JOIN mesure m ON c.id_capteur = m.id_capteur";

if ($id_batiment !== null) {
    $sql .= " WHERE s.id_batiment = ? ";
    $sql .= " GROUP BY c.id_capteur";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_batiment);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql .= " GROUP BY c.id_capteur";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion - IoT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1><?= $titre_page ?></h1>
    </header>

    <main>
        <div id="tableau">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div>
                    <section>
                        <h2><?= htmlspecialchars($row['nom_capteur']) ?></h2>
                        <h3>Salle locale : <?= htmlspecialchars($row['nom_salle']) ?></h3>
                        
                        <ul class="stats">
                            <li>Minimum : <span><?= $row['val_min'] ?? '-' ?></span></li>
                            <li>Actuel : <span><?= $row['val_actuelle'] ?? '-' ?></span></li>
                            <li>Maximum : <span><?= $row['val_max'] ?? '-' ?></span></li>
                        </ul>
                    </section>
                </div>
            <?php endwhile; ?>
        </div>

        <section>
            <h2>📈 Historique des mesures</h2>
            <iframe 
                src="http://localhost:3000/d/adr8fgt/mesures-plus-visuelles?orgId=1&from=now-30d&to=now&timezone=browser&kiosk" 
                style="width: 100%; height: 600px; border: none; border-radius: 8px; background: #181b1f;" 
                title="Dashboard Grafana">
            </iframe>
        </section>
    </main>
</body>
</html>