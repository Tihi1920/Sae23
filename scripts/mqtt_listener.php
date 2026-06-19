#!/opt/lampp/bin/php
<?php
// ============================================================================
-- BACKGROUND RUNNING MQTT DATA CAPTURE WORKER (PROCEDURAL PHP)
-- Execution Environment: Linux Terminal / PHP CLI Binary background job
-- Purpose: Seamless mosquitto subscription ingestion into sae23_db
// ============================================================================

$db_host = "";
$db_user = "";
$db_pass = "chap1234";
$db_name = "Sae23";

// Open connection to database using procedural functions
$db_link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$db_link) {
    die("[FATAL] Relational storage link offline: " . mysqli_connect_error() . "\n");
}

echo "[*] Daemon listening... Waiting for incoming MQTT signals on 'iut/+/+'...\n";

// Open a system reading stream from the Mosquitto broker
$mqtt_stream = popen("mosquitto_sub -h localhost -t 'iut/+/+' -v", "r");
if (!$mqtt_stream) {
    die("[CRITICAL] Operating system execution error: popen failed to spawn mosquitto.\n");
}

// Continuous stream inspection loop
while (!feof($mqtt_stream)) {
    $incoming_line = fgets($mqtt_stream);
    if ($incoming_line) {
        $raw_tokens = explode(' ', trim($incoming_line));
        if (count($raw_tokens) == 2) {
            $topic_path   = $raw_tokens[0];
            $sensor_value = floatval($raw_tokens[1]);
            
            $topic_nodes = explode('/', $topic_path);
            $sensor_uid  = end($topic_nodes);
            
            $current_date = date('Y-m-d');
            $current_time = date('H:i:s');
            
            // Sanitize variables with procedural mysql function
            $safe_sensor_uid = mysqli_real_escape_string($db_link, $sensor_uid);
            
            // Verify hardware via procedural queries
            $check_hardware = mysqli_query($db_link, "SELECT nom_capteur FROM Capteur WHERE nom_capteur = '$safe_sensor_uid'");
            
            if (mysqli_num_rows($check_hardware) > 0) {
                $insert_statement = "INSERT INTO Mesure (date_mesure, horaire_mesure, valeur, nom_capteur) 
                                     VALUES ('$current_date', '$current_time', $sensor_value, '$safe_sensor_uid')";
                if (mysqli_query($db_link, $insert_statement)) {
                    echo "[LOG] Saved successfully -> Hardware: $safe_sensor_uid | Value: $sensor_value | Logged at: $current_time\n";
                } else {
                    echo "[ERROR] Database transaction failed: " . mysqli_error($db_link) . "\n";
                }
            } else {
                echo "[WARNING FORBIDDEN] Signal dropped. Sensor UID '$safe_sensor_uid' does not exist in BD.\n";
            }
        }
    }
}

pclose($mqtt_stream);
mysqli_close($db_link);
?>