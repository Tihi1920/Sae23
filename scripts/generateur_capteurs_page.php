<?php
// generate_capteurs.php
// This script reads sensor data from the LOCAL database on the VM
// and creates a capteurs.html file that will be sent to EoHost by the bash script

// We connect to the local database on the VM
$host = "localhost";
$user = "root";        // my local MySQL username
$pass = "";            // my local MySQL password (empty by default on XAMPP)
$db   = "sae23"; // my local database name here

// We try to connect to the database
$conn = mysqli_connect($host, $user, $pass, $db);

// If the connection fails we stop everything and show an error
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// We make sure the connection uses UTF-8 so special characters work fine
mysqli_set_charset($conn, "utf8");

// We get all sensors with their min, current and max values
$sql = "SELECT c.nom as nom_capteur, c.unite, s.nom as nom_salle,
            MIN(m.valeur) as val_min,
            MAX(m.valeur) as val_max,
            (SELECT valeur FROM Mesure m2 WHERE m2.nom_capteur = c.nom ORDER BY id DESC LIMIT 1) as val_actuelle
        FROM Capteur c
        JOIN Salle s ON c.nom_salle = s.nom
        LEFT JOIN Mesure m ON c.nom = m.nom_capteur
        GROUP BY c.nom, c.unite, s.nom";

$result = mysqli_query($conn, $sql);

// We start building the HTML for the sensor cards
$html = "";

// We loop through each sensor and add a card to the HTML
while ($row = mysqli_fetch_assoc($result)) {
    $nom_capteur  = htmlspecialchars($row['nom_capteur']);
    $nom_salle    = htmlspecialchars($row['nom_salle']);
    $unite        = htmlspecialchars($row['unite']);
    $val_min      = $row['val_min'] ?? '-';
    $val_actuelle = $row['val_actuelle'] ?? '-';
    $val_max      = $row['val_max'] ?? '-';

    $html .= "<div>\n";
    $html .= "    <section>\n";
    $html .= "        <h2>$nom_capteur</h2>\n";
    $html .= "        <h3>Salle : $nom_salle</h3>\n";
    $html .= "        <ul class=\"stats\">\n";
    $html .= "            <li>Minimum : <span>$val_min $unite</span></li>\n";
    $html .= "            <li>Actuel  : <span>$val_actuelle $unite</span></li>\n";
    $html .= "            <li>Maximum : <span>$val_max $unite</span></li>\n";
    $html .= "        </ul>\n";
    $html .= "    </section>\n";
    $html .= "</div>\n";
}

// We save the HTML into a file called capteurs.html
file_put_contents('/opt/lampp/htdocs/sae23/capteurs.html', $html);

echo "capteurs.html created!\n";
?>
