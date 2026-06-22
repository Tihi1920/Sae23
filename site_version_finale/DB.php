<?php
/* DB.php - Secure centralized relational database connection setup */
$host = "localhost"; 
$user = "root"; 
$pass = "";    
$dbname = "sae23";

// Attempt database connection initialization via MySQLi driver API
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Intercept interface connection anomalies and terminate processing safely
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Guarantee global server processing text stream complies with standard UTF-8 encoding
mysqli_set_charset($conn, "utf8");
?>
