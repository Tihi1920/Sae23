<?php
/* Administrateur.php - Admin and Manager portal for building and sensor management */
session_start();
require 'DB.php';

// Redirect unauthenticated users back to the login page
if (!isset($_SESSION['role'])) {
    header("Location: connexion.php");
    exit;
}

$role = $_SESSION['role'];
$message = "";

// For managers, retrieve the specific building assigned to their account
$id_batiment_gestionnaire = null;
if ($role === 'gestionnaire') {
    $login_gest = mysqli_real_escape_string($conn, $_SESSION['user']);
    $res_bat = mysqli_query($conn, "SELECT id FROM Batiment WHERE login_gestionnaire = '$login_gest'");
    $bat_gest = mysqli_fetch_assoc($res_bat);
    if ($bat_gest) {
        $id_batiment_gestionnaire = (int)$bat_gest['id'];
    }
}

// Handle form submissions based on POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // Restrict building creation and deletion strictly to the admin role
    if ($role === 'admin') {

        // Action: Add a new building and automatically generate its manager account
        if ($_POST['action'] === 'add_batiment') {
            $nom_bat = mysqli_real_escape_string($conn, $_POST['nom_batiment']);
            // Generate manager login and password credentials automatically based on building name
            $suffixe = strtoupper(str_replace(' ', '', $nom_bat));
            $login_gest = "gestionnaire_" . $suffixe;
            $mdp_gest   = "gestion123_" . $suffixe;

            // Insert new building data into the Batiment table
            $sql_bat = "INSERT INTO Batiment (nom, login_gestionnaire, mdp_gestionnaire) VALUES ('$nom_bat', '$login_gest', '$mdp_gest')";
            if (mysqli_query($conn, $sql_bat)) {
                // Simultaneously create the corresponding manager record in the Administration table
                $sql_admin = "INSERT INTO Administration (login, mdp) VALUES ('$login_gest', '$mdp_gest')";
                mysqli_query($conn, $sql_admin);
                $message = "<p class='message-ok'>Batiment '$nom_bat' cree. Compte : $login_gest / MDP : $mdp_gest</p>";
            } else {
                $message = "<p class='message-erreur'>Erreur : ce batiment existe peut-etre deja.</p>";
            }
        }

        // Action: Delete a building and its linked manager account
        elseif ($_POST['action'] === 'del_batiment') {
            $id_bat = (int)$_POST['id_batiment'];

            // Query the database to retrieve the manager's login before removing the building row
            $res = mysqli_query($conn, "SELECT login_gestionnaire FROM Batiment WHERE id = $id_bat");
            $bat = mysqli_fetch_assoc($res);

            if ($bat) {
                $login_gest = mysqli_real_escape_string($conn, $bat['login_gestionnaire']);
                // Perform cascade deletion across both Batiment and Administration tables
                mysqli_query($conn, "DELETE FROM Batiment WHERE id = $id_bat");
                mysqli_query($conn, "DELETE FROM Administration WHERE login = '$login_gest'");
                $message = "<p class='message-ok'>Batiment et compte $login_gest supprimes.</p>";
            }
        }
    }

    // Shared Actions: Accessible by both Admin and Manager roles
    // Action: Add a new sensor (and automatically create the room if it doesn't exist)
    if ($_POST['action'] === 'add_capteur') {
        $nom_cap   = mysqli_real_escape_string($conn, $_POST['nom_capteur']);
        $nom_salle = mysqli_real_escape_string($conn, $_POST['nom_salle']);
        $type_cap  = mysqli_real_escape_string($conn, $_POST['type_capteur']);
        $unite_cap = mysqli_real_escape_string($conn, $_POST['unite_capteur']);

        // Check if the target room exists; if missing, create it first with dynamic building assignment
        $check_salle = mysqli_query($conn, "SELECT nom FROM Salle WHERE nom = '$nom_salle'");
        if (mysqli_num_rows($check_salle) === 0) {
            $id_bat_insert = ($role === 'gestionnaire') ? $id_batiment_gestionnaire : 0;
            mysqli_query($conn, "INSERT INTO Salle (nom, type, capacite, id_batiment) VALUES ('$nom_salle', 'cours', 0, $id_bat_insert)");
        }

        // Insert the new sensor specification record into the Capteur table
        $sql = "INSERT INTO Capteur (nom, type, unite, nom_salle) VALUES ('$nom_cap', '$type_cap', '$unite_cap', '$nom_salle')";
        if (mysqli_query($conn, $sql)) {
            $message = "<p class='message-ok'>Capteur '$nom_cap' ajoute avec succes.</p>";
        } else {
            $message = "<p class='message-erreur'>Erreur : " . mysqli_error($conn) . "</p>";
        }
    }

    // Action: Delete an existing sensor
    elseif ($_POST['action'] === 'del_capteur') {
        $nom_cap = mysqli_real_escape_string($conn, $_POST['nom_capteur']);
        // Remove the sensor registry row from the Capteur table
        mysqli_query($conn, "DELETE FROM Capteur WHERE nom = '$nom_cap'");
        $message = "<p class='message-ok'>Capteur '$nom_cap' supprime.</p>";
    }
}

// Fetch display data based on user roles
if ($role === 'admin') {
    // Admin fetches all records from the database across all buildings
    $salles    = mysqli_query($conn, "SELECT s.nom, b.nom as nom_batiment FROM Salle s JOIN Batiment b ON s.id_batiment = b.id");
    $capteurs  = mysqli_query($conn, "SELECT c.nom, c.type, c.unite, c.nom_salle FROM Capteur c");
    $batiments = mysqli_query($conn, "SELECT id, nom FROM Batiment");
} else {
    // Manager fetches only data belonging strictly to their assigned building
    $salles   = mysqli_query($conn, "SELECT nom FROM Salle WHERE id_batiment = $id_batiment_gestionnaire");
    $capteurs = mysqli_query($conn, "SELECT c.nom, c.type, c.unite, c.nom_salle FROM Capteur c JOIN Salle s ON c.nom_salle = s.nom WHERE s.id_batiment = $id_batiment_gestionnaire");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panneau d'Action</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'En-tete.php'; ?>

    <main>
        <?= $message ?>

        <section id="tableau">

            <?php if ($role === 'admin'): ?>
            <section>
                <h2>Gestion des Batiments</h2>

                <form method="POST">
                    <input type="hidden" name="action" value="add_batiment">
                    <label>Nom du nouveau batiment :</label>
                    <input type="text" name="nom_batiment" placeholder="Ex: D" required>
                    <button type="submit">Creer le batiment</button>
                </form>

                <hr>

                <form method="POST">
                    <input type="hidden" name="action" value="del_batiment">
                    <label>Batiment a supprimer :</label>
                    <select name="id_batiment" required>
                        <?php while ($b = mysqli_fetch_assoc($batiments)): ?>
                            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['nom']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit">Supprimer le batiment</button>
                </form>
            </section>
            <?php endif; ?>

            <section>
                <h2>Ajouter un capteur</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="add_capteur">
                    <label>Nom du capteur :</label>
                    <input type="text" name="nom_capteur" placeholder="Ex: AM107-B103" required>

                    <label>Type :</label>
                    <input type="text" name="type_capteur" placeholder="Ex: temperature" required>

                    <label>Unite :</label>
                    <input type="text" name="unite_capteur" placeholder="Ex: °C" required>

                    <label>Salle :</label>
                    <input type="text" name="nom_salle" placeholder="Ex: C101" required>
                    <button type="submit">Ajouter le capteur</button>
                </form>
            </section>

        </section>

        <section>
            <h2>Supprimer un capteur</h2>
            <form method="POST">
                <input type="hidden" name="action" value="del_capteur">
                <label>Capteur a supprimer :</label>
                <select name="nom_capteur" required>
                    <option value="">-- Selectionner un capteur --</option>
                    <?php while ($c = mysqli_fetch_assoc($capteurs)): ?>
                        <option value="<?= htmlspecialchars($c['nom']) ?>">
                            <?= htmlspecialchars($c['nom']) ?> — <?= htmlspecialchars($c['nom_salle']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Supprimer le capteur</button>
            </form>
        </section>

    </main>
</body>
</html>
