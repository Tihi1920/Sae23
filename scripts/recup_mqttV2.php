#!/opt/lampp/bin/php
<?php
// mqtt_listener.php
//
// This script gets the sensor data from MQTT and saves it in the MySQL database.
// It runs every minute thanks to crontab.
//
// We start with our 4 base sensors the ones we know from the beginning.
// Then we also check the database to see if new sensors were added by
// the admin, so the script can handle them too without changing the code.

// Connect to the database
$bdd = mysqli_connect('127.0.0.1', '', 'chap1234', 'sae23');

if (!$bdd) {
    echo "Database connection failed\n";
    exit(1);
}

// Our 4 base sensors, room name => sensor name in the database
$capteurs = array(
    'E208' => 'AM107-E208',
    'E104' => 'AM107-E104',
    'B102' => 'AM107-B102',
    'B202' => 'AM107-B202'
);

// Now we look in the database for any other sensor that is not in our base list
// This way, if the admin adds a new sensor, it will be picked up automatically
$requete = "SELECT nom, nom_salle FROM Capteur";
$resultat = mysqli_query($bdd, $requete);

while ($ligne = mysqli_fetch_assoc($resultat)) {
    $salle = $ligne['nom_salle'];
    $nom = $ligne['nom'];

    // If this sensor is not already in our list, we add it
    if (!array_key_exists($salle, $capteurs)) {
        $capteurs[$salle] = $nom;
    }
}

// Now we go through every sensor : base ones + new ones found in the database
foreach ($capteurs as $salle => $nom_capteur) {

    $topic = "sensors/AM107/by-room/$salle/data";

    // This command asks for the last message on the topic and waits 5 seconds max
    $commande = "mosquitto_sub -h mqtt.iut-blagnac.fr -p 8883 --capath /etc/ssl/certs -u student -P student -t \"$topic\" -C 1 -W 5";

    $output = shell_exec($commande);

    if (empty($output)) {
        echo "No data for $salle\n";
        continue;
    }

    // The data comes as JSON, we convert it to a PHP array
    $data = json_decode($output, true);
    $valeur = $data[0]['temperature'];

    // We save the new value in the database
    $date = date('Y-m-d');
    $heure = date('H:i:s');

    $requete_insert = "INSERT INTO Mesure (date, horaire, valeur, nom_capteur)
                        VALUES ('$date', '$heure', '$valeur', '$nom_capteur')";

    mysqli_query($bdd, $requete_insert);

    echo "$salle : $valeur saved\n";
}

mysqli_close($bdd);
