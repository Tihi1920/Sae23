<?php
// DB.php - Connexion sécurisée à la base de données
$host = "localhost"; 
$user = "root"; // Pensez à compléter si nécessaire
$pass = "";     // Pensez à compléter si nécessaire
$dbname = "sae23";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>