<?php
/* deconnexion.php - Secure user session termination script */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure a valid active tracking session is established before processing termination
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Wipe out runtime contextual information stored within global array data structures
$_SESSION = array();

// Expire client tracking browser cookies explicitly to purge session bindings
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Terminate the storage structure allocations bound on the host server system
session_destroy();

// Redirect back to landing view
header("Location: index.php");
exit;
?>
