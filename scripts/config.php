<?php
// ============================================================================
-- GLOBAL WEB APPLICATION CORE BOOTSTRAPPER
// ============================================================================
$conn = mysqli_connect("localhost", "user", "chap1234", "Sae23");
if (!$conn) {
    die("[CRITICAL ERROR] Core SQL Service is offline: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>