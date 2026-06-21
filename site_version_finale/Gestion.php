<?php
// Gestion.php - Dashboard showing sensor data for each room
session_start();
require 'DB.php';

// If the user is not logged in we send them back to the login page
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$role        = $_SESSION['role'];
$login       = $_SESSION['user'];
$titre_page  = "Supervision Globale";
$id_batiment = null;
$grafana_url = null;

// We list all the buildings that have a Grafana dashboard
// if a new building is added by the admin and has no dashboard yet, it will use the BDD fallback
$grafana_dashboards = [
    'B' => "http://localhost:3000/d/adx2jsv/b?orgId=1&from=now-30m&to=now&timezone=browser&refresh=5s&kiosk",
    'E' => "http://localhost:3000/d/adhrv5b/e?orgId=1&from=now-30m&to=now&timezone=browser&refresh=5s&kiosk",
];

// Check if the user is a manager or an admin
if ($role === 'gestionnaire') {

    // We split the login by "_" to get the building name at the end
    // example: "gestionnaire_B" → ["gestionnaire", "B"]
    $parts        = explode('_', $login);
    $nom_batiment = end($parts); // we grab the last part which is the building name

    // We update the page title to show which building this manager handles
    $titre_page = "Supervision : Batiment " . htmlspecialchars($nom_batiment);

    // We look for the building in the database using the name we extracted from the login
    $nom_bat_escaped = mysqli_real_escape_string($conn, $nom_batiment);
    $res_bat = mysqli_query($conn, "SELECT id FROM Batiment WHERE nom = '$nom_bat_escaped'");
    $bat     = mysqli_fetch_assoc($res_bat);

    if ($bat) {
        $id_batiment = (int)$bat['id'];
    }

    // We check if this building has a Grafana dashboard or not
    // if yes we use the iframe, if no we fall back to the BDD data below
    if (isset($grafana_dashboards[$nom_batiment])) {
        $grafana_url = $grafana_dashboards[$nom_batiment];
    }
}

// We build the SQL query depending on the role
// Manager only sees their building, admin sees all buildings
if ($id_batiment !== null) {
    $sql = "SELECT c.nom as nom_capteur, c.unite, s.nom as nom_salle,
                MIN(m.valeur) as val_min,
                MAX(m.valeur) as val_max,
                (SELECT valeur FROM Mesure m2 WHERE m2.nom_capteur = c.nom ORDER BY id DESC LIMIT 1) as val_actuelle
            FROM Capteur c
            JOIN Salle s ON c.nom_salle = s.nom
            LEFT JOIN Mesure m ON c.nom = m.nom_capteur
            WHERE s.id_batiment = $id_batiment
            GROUP BY c.nom, c.unite, s.nom";
} else {
    // No filter for admin, we select everything
    $sql = "SELECT c.nom as nom_capteur, c.unite, s.nom as nom_salle,
                MIN(m.valeur) as val_min,
                MAX(m.valeur) as val_max,
                (SELECT valeur FROM Mesure m2 WHERE m2.nom_capteur = c.nom ORDER BY id DESC LIMIT 1) as val_actuelle
            FROM Capteur c
            JOIN Salle s ON c.nom_salle = s.nom
            LEFT JOIN Mesure m ON c.nom = m.nom_capteur
            GROUP BY c.nom, c.unite, s.nom";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion - IoT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'En-tete.php'; ?>

    <main>
        <h2><?= $titre_page ?></h2>

        <!-- We loop through all the sensors and display a card for each one -->
        <div id="tableau">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div>
                    <section>
                        <h2><?= htmlspecialchars($row['nom_capteur']) ?></h2>
                        <h3>Salle : <?= htmlspecialchars($row['nom_salle']) ?></h3>

                        <ul class="stats">
                            <li>Minimum : <span><?= $row['val_min'] ?? '-' ?> <?= htmlspecialchars($row['unite']) ?></span></li>
                            <li>Actuel  : <span><?= $row['val_actuelle'] ?? '-' ?> <?= htmlspecialchars($row['unite']) ?></span></li>
                            <li>Maximum : <span><?= $row['val_max'] ?? '-' ?> <?= htmlspecialchars($row['unite']) ?></span></li>
                        </ul>
                    </section>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Historique section -->
        <section>
            <h2>Historique des mesures</h2>

            <?php if ($role === 'gestionnaire'): ?>

                <?php if ($grafana_url !== null): ?>
                    <!-- This building has a Grafana dashboard so we display it in the iframe -->
                    <iframe
                        src="<?= htmlspecialchars($grafana_url) ?>"
                        style="width: 100%; height: 600px; border: none; border-radius: 8px; background: #181b1f;"
                        title="Dashboard Grafana">
                    </iframe>

                <?php else: ?>
                    <!-- This building has no Grafana dashboard yet so we show a message -->
                    <!-- The sensor data is already displayed in the cards above so nothing is lost -->
                    <p>Aucun historique graphique disponible pour ce bâtiment pour le moment.</p>
                    <p>Les données actuelles de vos capteurs sont affichées ci-dessus.</p>

                <?php endif; ?>

            <?php else: ?>

                <!-- Admin sees all dashboards one below the other -->
                <?php foreach ($grafana_dashboards as $nom_bat => $url): ?>
                    <h3>Bâtiment <?= htmlspecialchars($nom_bat) ?></h3>
                    <iframe
                        src="<?= htmlspecialchars($url) ?>"
                        style="width: 100%; height: 600px; border: none; border-radius: 8px; background: #181b1f; margin-bottom: 20px;"
                        title="Dashboard Grafana Batiment <?= htmlspecialchars($nom_bat) ?>">
                    </iframe>
                <?php endforeach; ?>

            <?php endif; ?>

        </section>

    </main>
</body>
</html>
