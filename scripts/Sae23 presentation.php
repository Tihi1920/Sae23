<?php 
session_start();
include 'En-tete.php'; 
?>

<section>
    <h2>Découvrir notre projet : la SAÉ 23</h2>
    <p>Conception d'une infrastructure complète de monitoring pour l'Internet des Objets (IoT)</p>
</section>

<div id="tableau">
    <div>
        <section>
            <h2>Qu'est-ce que la SAÉ 23 ?</h2>
            <p>La <strong>SAÉ 23</strong> (Situation d'Apprentissage et d'Évaluation) est un grand projet d'études qui nous plonge au cœur d'une problématique d'entreprise. L'enjeu est de taille : concevoir et déployer une solution informatique de bout en bout en combinant plusieurs compétences clés de notre formation : la programmation web, l'administration de bases de données, la gestion de systèmes Linux et le travail d'équipe.</p>
        </section>
    </div>
    <div>
        <section>
            <h2>Les grands objectifs du projet</h2>
            <p>Pour mener à bien cette mission, notre travail s'articule autour de deux axes principaux :</p>
            <p><strong>Une chaîne de traitement robuste (via Docker) :</strong> Un pipeline complet où les données transitent par Mosquitto (le broker MQTT), sont triées et routées par Node-RED, sauvegardées dans la base temporelle InfluxDB, puis mises en valeur visuellement sur Grafana.</p>
        </section>
    </div>
</div>

<section>
    <h2>📅 Calendrier d'Avancement (GANTT Matrix)</h2>
    <p>Voici l'état d'avancement des livrables de notre infrastructure de supervision :</p>
    
    <table class="gantt">
        <thead>
            <tr>
                <th>Tâche / Objectif</th>
                <th>Phase 1</th>
                <th>Phase 2</th>
                <th>Phase 3</th>
                <th>Statut final</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Routage IoT (Node-RED)</td>
                <td class="fait">Terminé</td>
                <td class="fait">Terminé</td>
                <td class="vide"></td>
                <td>100%</td>
            </tr>
            <tr>
                <td>Base de données (MySQL)</td>
                <td class="fait">Terminé</td>
                <td class="fait">Terminé</td>
                <td class="fait">Terminé</td>
                <td>100%</td>
            </tr>
            <tr>
                <td>Développement Web (PHP)</td>
                <td class="fait">Terminé</td>
                <td class="encours">Optimisation</td>
                <td class="encours">Optimisation</td>
                <td>En cours</td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2>Qui se cache derrière ce projet ?</h2>
    <p>Notre équipe projet est composée d'étudiants investis aux compétences complémentaires :</p>
    <ul class="stats" style="margin-top: 15px;">
        <li><b>Mahamoudou</b> — Chef de projet, coordination globale et suivi de la documentation</li>
        <li><b>Nael</b> — Développement de l'interface utilisateur web et architecture Node-RED</li>
        <li><b>Samuel</b> — Administration de l'infrastructure de bases de données et scripts Bash</li>
    </ul>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="" target="_blank" style="display: inline-block; background-color: #A0522D; color: white; padding: 12px 24px; text-decoration: none; border-radius: 10px; font-weight: bold;">Consulter notre dépôt GitHub</a>
    </div>
</section>

<?php include 'footer.php'; ?>