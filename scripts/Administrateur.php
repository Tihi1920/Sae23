<?php
// Administrateur.php - Panel d'administration
session_start();
require 'DB.php';

if (!isset($_SESSION['role'])) {
    header("Location: connexion.php");
    exit;
}

$role = $_SESSION['role'];
$id_batiment_gestionnaire = isset($_SESSION['id_batiment_gere']) ? (int)$_SESSION['id_batiment_gere'] : null;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($role === 'admin') {
        if ($_POST['action'] === 'add_batiment') {
            $nom_batiment = htmlspecialchars($_POST['nom_batiment']);
            $suffixe = strtolower(str_replace(' ', '', $nom_batiment));
            $login_gest = "gestionnaire_" . $suffixe;
            $mdp = "chap1234"; 

            $sql_user = "INSERT INTO utilisateur (login, mot_de_passe, role) VALUES (?, ?, 'gestionnaire')";
            $stmt = mysqli_prepare($conn, $sql_user);
            mysqli_stmt_bind_param($stmt, "ss", $login_gest, $mdp);
            
            if (mysqli_stmt_execute($stmt)) {
                $sql_bat = "INSERT INTO batiment (nom_batiment, login_gestionnaire) VALUES (?, ?)";
                $stmt_bat = mysqli_prepare($conn, $sql_bat);
                mysqli_stmt_bind_param($stmt_bat, "ss", $nom_batiment, $login_gest);
                mysqli_stmt_execute($stmt_bat);
                mysqli_stmt_close($stmt_bat);
                
                $message = "<div class='message-ok'>✅ Bâtiment et compte <b>$login_gest</b> créés.</div>";
            } else {
                $message = "<div class='message-erreur'>❌ Erreur : Ce gestionnaire existe déjà.</div>";
            }
            mysqli_stmt_close($stmt);
        }
        elseif ($_POST['action'] === 'del_batiment') {
            $id_bat = (int)$_POST['id_batiment'];
            
            $stmt_sel = mysqli_prepare($conn, "SELECT login_gestionnaire FROM batiment WHERE id_batiment = ?");
            mysqli_stmt_bind_param($stmt_sel, "i", $id_bat);
            mysqli_stmt_execute($stmt_sel);
            $res = mysqli_stmt_get_result($stmt_sel);

            if ($bat = mysqli_fetch_assoc($res)) {
                $login_gest = $bat['login_gestionnaire'];

                $stmt_del1 = mysqli_prepare($conn, "DELETE FROM batiment WHERE id_batiment = ?");
                mysqli_stmt_bind_param($stmt_del1, "i", $id_bat);
                mysqli_stmt_execute($stmt_del1);

                $stmt_del2 = mysqli_prepare($conn, "DELETE FROM utilisateur WHERE login = ?");
                mysqli_stmt_bind_param($stmt_del2, "s", $login_gest);
                mysqli_stmt_execute($stmt_del2);

                $message = "<div class='message-ok'>🗑️ Bâtiment supprimé du cluster.</div>";
                mysqli_stmt_close($stmt_del1);
                mysqli_stmt_close($stmt_del2);
            }
            mysqli_stmt_close($stmt_sel);
        }
    }

    if ($_POST['action'] === 'add_capteur') {
        $nom_cap = htmlspecialchars($_POST['nom_capteur']);
        $id_salle = (int)$_POST['id_salle'];
        
        $sql = "INSERT INTO capteur (nom_capteur, id_salle) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $nom_cap, $id_salle);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        $message = "<div class='message-ok'>✅ Capteur configuré et ajouté avec succès.</div>";
    }
    elseif ($_POST['action'] === 'del_capteur') {
        $id_capteur = (int)$_POST['id_capteur'];

        $stmt_del_cap = mysqli_prepare($conn, "DELETE FROM capteur WHERE id_capteur = ?");
        mysqli_stmt_bind_param($stmt_del_cap, "i", $id_capteur);
        mysqli_stmt_execute($stmt_del_cap);
        mysqli_stmt_close($stmt_del_cap);

        $message = "<div class='message-ok'>🗑️ Capteur retiré de l'infrastructure.</div>";
    }
}

if ($role === 'admin') {
    $salles_sql = "SELECT s.id_salle, s.nom_salle, b.nom_batiment FROM salle s JOIN batiment b ON s.id_batiment = b.id_batiment";
    $capteurs_sql = "SELECT c.id_capteur, c.nom_capteur, s.nom_salle, b.nom_batiment FROM capteur c JOIN salle s ON c.id_salle = s.id_salle JOIN batiment b ON s.id_batiment = b.id_batiment";
    $salles = mysqli_query($conn, $salles_sql);
    $capteurs = mysqli_query($conn, $capteurs_sql);
} else {
    $salles_sql = "SELECT s.id_salle, s.nom_salle FROM salle s WHERE s.id_batiment = ?";
    $stmt_s = mysqli_prepare($conn, $salles_sql);
    mysqli_stmt_bind_param($stmt_s, "i", $id_batiment_gestionnaire);
    mysqli_stmt_execute($stmt_s);
    $salles = mysqli_stmt_get_result($stmt_s);

    $capteurs_sql = "SELECT c.id_capteur, c.nom_capteur, s.nom_salle FROM capteur c JOIN salle s ON c.id_salle = s.id_salle WHERE s.id_batiment = ?";
    $stmt_c = mysqli_prepare($conn, $capteurs_sql);
    mysqli_stmt_bind_param($stmt_c, "i", $id_batiment_gestionnaire);
    mysqli_stmt_execute($stmt_c);
    $capteurs = mysqli_stmt_get_result($stmt_c);
}

$batiments = mysqli_query($conn, "SELECT * FROM batiment"); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panneau d'Action</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Panneau d'Action Administrateur</h1>
        <p>Rôle actif : <?= strtoupper($role) ?></p>
    </header>

    <main>
        <?= $message ?>

        <div id="tableau">
            <?php if ($role === 'admin'): ?>
                <div>
                    <section>
                        <h2>🏢 Gestion des Bâtiments</h2>
                        
                        <form method="POST">
                            <input type="hidden" name="action" value="add_batiment">
                            <label>Nom du nouveau complexe :</label>
                            <input type="text" name="nom_batiment" placeholder="Ex: Batiment D" required>
                            <button type="submit">Créer le Bâtiment</button>
                        </form>
                        
                        <hr style="margin: 20px 0; border: none; border-top: 1px solid #8B5E3C;">
                        
                        <form method="POST">
                            <input type="hidden" name="action" value="del_batiment">
                            <label>Sélectionner le bâtiment à détruire :</label>
                            <select name="id_batiment" required>
                                <?php mysqli_data_seek($batiments, 0); while($b = mysqli_fetch_assoc($batiments)): ?>
                                    <option value="<?= $b['id_batiment'] ?>"><?= htmlspecialchars($b['nom_batiment']) ?></option>
                                <?php endwhile; ?>
                            </select>
                            <button type="submit">Supprimer le Bâtiment</button>
                        </form>
                    </section>
                </div>
            <?php endif; ?>

            <div>
                <section>
                    <h2>📡 Déploiement des Capteurs</h2>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_capteur">
                        <label>Nom ou ID du capteur :</label>
                        <input type="text" name="nom_capteur" placeholder="Ex: Temp_Salle102" required>
                        
                        <label>Zone d'affectation :</label>
                        <select name="id_salle" required>
                            <option value="">-- Choisir un emplacement --</option>
                            <?php mysqli_data_seek($salles, 0); while($s = mysqli_fetch_assoc($salles)): ?>
                                <option value="<?= $s['id_salle'] ?>">
                                    <?= htmlspecialchars($s['nom_salle']) ?> <?= isset($s['nom_batiment']) ? "({$s['nom_batiment']})" : "" ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit">Enregistrer le capteur</button>
                    </form>
                </section>
            </div>
        </div>

        <section>
            <h2>❌ Retirer un composant de l'infrastructure</h2>
            <form method="POST">
                <input type="hidden" name="action" value="del_capteur">
                <label>Sélectionner le capteur à détruire définitivement :</label>
                <select name="id_capteur" required>
                    <option value="">-- Sélectionner un capteur actif --</option>
                    <?php mysqli_data_seek($capteurs, 0); while($c = mysqli_fetch_assoc($capteurs)): ?>
                        <option value="<?= $c['id_capteur'] ?>">
                            🚨 <?= htmlspecialchars($c['nom_capteur']) ?> — [Salle: <?= htmlspecialchars($c['nom_salle']) ?>]
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Supprimer définitivement</button>
            </form>
        </section>
    </main>
</body>
</html>