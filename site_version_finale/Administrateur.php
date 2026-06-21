<?php
// Administrateur.php - Admin panel for managing buildings and sensors
session_start();
require 'DB.php';

// If the user is not logged in we send them back to the login page
if (!isset($_SESSION['role'])) {
    header("Location: connexion.php");
    exit;
}

$role = $_SESSION['role'];
$message = "";

// If the user is a manager we get the building linked to their account
$id_batiment_gestionnaire = null;
if ($role === 'gestionnaire') {
    $login_gest = mysqli_real_escape_string($conn, $_SESSION['user']);
    $res_bat = mysqli_query($conn, "SELECT id FROM Batiment WHERE login_gestionnaire = '$login_gest'");
    $bat_gest = mysqli_fetch_assoc($res_bat);
    if ($bat_gest) {
        $id_batiment_gestionnaire = (int)$bat_gest['id'];
    }
}

// We check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // These actions are only for the admin
    if ($role === 'admin') {

        if ($_POST['action'] === 'add_batiment') {
            $nom_bat = mysqli_real_escape_string($conn, $_POST['nom_batiment']);
            // We build the manager login and password from the building name
            $suffixe = strtoupper(str_replace(' ', '', $nom_bat));
            $login_gest = "gestionnaire_" . $suffixe;
            $mdp_gest   = "gestion123_" . $suffixe;

            // We add the building with the manager info
            $sql_bat = "INSERT INTO Batiment (nom, login_gestionnaire, mdp_gestionnaire) VALUES ('$nom_bat', '$login_gest', '$mdp_gest')";
            if (mysqli_query($conn, $sql_bat)) {
                // We also create the manager account in the Administration table
                $sql_admin = "INSERT INTO Administration (login, mdp) VALUES ('$login_gest', '$mdp_gest')";
                mysqli_query($conn, $sql_admin);
                $message = "<div class='message-ok'>Batiment '$nom_bat' cree. Compte : $login_gest / MDP : $mdp_gest</div>";
            } else {
                $message = "<div class='message-erreur'>Erreur : ce batiment existe peut-etre deja.</div>";
            }
        }

        elseif ($_POST['action'] === 'del_batiment') {
            $id_bat = (int)$_POST['id_batiment'];

            // We get the manager login before deleting the building
            $res = mysqli_query($conn, "SELECT login_gestionnaire FROM Batiment WHERE id = $id_bat");
            $bat = mysqli_fetch_assoc($res);

            if ($bat) {
                $login_gest = mysqli_real_escape_string($conn, $bat['login_gestionnaire']);
                // We delete the building and the manager account
                mysqli_query($conn, "DELETE FROM Batiment WHERE id = $id_bat");
                mysqli_query($conn, "DELETE FROM Administration WHERE login = '$login_gest'");
                $message = "<div class='message-ok'>Batiment et compte $login_gest supprimes.</div>";
            }
        }
    }

    // These actions work for both admin and manager
    if ($_POST['action'] === 'add_capteur') {
        $nom_cap   = mysqli_real_escape_string($conn, $_POST['nom_capteur']);
        $nom_salle = mysqli_real_escape_string($conn, $_POST['nom_salle']);
        $type_cap  = mysqli_real_escape_string($conn, $_POST['type_capteur']);
        $unite_cap = mysqli_real_escape_string($conn, $_POST['unite_capteur']);

        // We check if the room exists, if not we create it first
        $check_salle = mysqli_query($conn, "SELECT nom FROM Salle WHERE nom = '$nom_salle'");
        if (mysqli_num_rows($check_salle) === 0) {
            $id_bat_insert = ($role === 'gestionnaire') ? $id_batiment_gestionnaire : 0;
            mysqli_query($conn, "INSERT INTO Salle (nom, type, capacite, id_batiment) VALUES ('$nom_salle', 'cours', 0, $id_bat_insert)");
        }

        // We insert the new sensor into the database
        $sql = "INSERT INTO Capteur (nom, type, unite, nom_salle) VALUES ('$nom_cap', '$type_cap', '$unite_cap', '$nom_salle')";
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='message-ok'>Capteur '$nom_cap' ajoute avec succes.</div>";
        } else {
            $message = "<div class='message-erreur'>Erreur : " . mysqli_error($conn) . "</div>";
        }
    }

    elseif ($_POST['action'] === 'del_capteur') {
        $nom_cap = mysqli_real_escape_string($conn, $_POST['nom_capteur']);
        // We delete the sensor from the database
        mysqli_query($conn, "DELETE FROM Capteur WHERE nom = '$nom_cap'");
        $message = "<div class='message-ok'>Capteur '$nom_cap' supprime.</div>";
    }
}

// We get the data to display on the page depending on the role
if ($role === 'admin') {
    // Admin sees everything
    $salles    = mysqli_query($conn, "SELECT s.nom, b.nom as nom_batiment FROM Salle s JOIN Batiment b ON s.id_batiment = b.id");
    $capteurs  = mysqli_query($conn, "SELECT c.nom, c.type, c.unite, c.nom_salle FROM Capteur c");
    $batiments = mysqli_query($conn, "SELECT id, nom FROM Batiment");
} else {
    // Manager only sees their own building
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

        <div id="tableau">

            <?php if ($role === 'admin'): ?>
            <div>
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
            </div>
            <?php endif; ?>

            <div>
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
            </div>

        </div>

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
