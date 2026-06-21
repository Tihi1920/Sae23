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
            <p>La <strong>SAÉ 23</strong> est un grand projet d'études qui nous plonge au cœur d'une problématique d'entreprise. L'enjeu est de concevoir et déployer une solution informatique de bout en bout en combinant plusieurs compétences clés de notre formation : la programmation web, l'administration de bases de données, la gestion de systèmes Linux et le travail d'équipe.</p>
        </section>
    </div>
    <div>
        <section>
            <h2>Les grands objectifs du projet</h2>
            <p>Pour mener à bien cette mission, notre travail s'articule autour de deux axes principaux :</p>
            <p><strong>Une chaîne de traitement robuste (via Docker) :</strong> Un pipeline complet où les données de température transitent par Mosquitto (le broker MQTT), sont triées et routées par Node-RED, sauvegardées dans InfluxDB, puis mises en valeur visuellement sur Grafana.</p>
        </section>
    </div>
</div>

<section>
    <h2>Calendrier d'Avancement (GANTT)</h2>
    <p>Voici l'état d'avancement des livrables de notre infrastructure de supervision :</p>
    
    <table class="gantt">
        <thead>
            <tr>
                <th>Tâche / Objectif</th>
                <th>Sem 21</th>
                <th>Sem 22</th>
                <th>Sem 23</th>
                <th>Sem 24</th>
                <th>Sem 25</th>
                <th>Sem 26</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Prise en main Git / Github</td>
                <td class="fait">✔</td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
            </tr>
            <tr>
                <td>Configuration VM / Docker</td>
                <td class="vide"></td>
                <td class="fait">✔</td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
            </tr>
            <tr>
                <td>Mise en place MQTT / Node-RED</td>
                <td class="vide"></td>
                <td class="fait">✔</td>
                <td class="fait">✔</td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
            </tr>
            <tr>
                <td>InfluxDB et dashboard Grafana</td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="fait">✔</td>
                <td class="fait">✔</td>
                <td class="vide"></td>
                <td class="vide"></td>
            </tr>
            <tr>
                <td>Conception base de données MySQL</td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="fait">✔</td>
                <td class="fait">✔</td>
                <td class="vide"></td>
                <td class="vide"></td>
            </tr>
            <tr>
                <td>Développement site web dynamique</td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="fait">✔</td>
                <td class="fait">✔</td>
                <td class="vide"></td>
            </tr>
            <tr>
                <td>Script PHP ingestion MQTT</td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="fait">✔</td>
                <td class="fait">✔</td>
                <td class="vide"></td>
            </tr>
            <tr>
                <td>Tests et finalisation</td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="fait">✔</td>
                <td class="vide"></td>
            </tr>
            <tr>
                <td>Présentation orale</td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="vide"></td>
                <td class="fait">✔</td>
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
</section>

<section>
    <h2>Synthèse personnelle — Youssouf Nael DIALLO</h2>

    <h3>Travail réalisé</h3>
    <p>Je me suis occupé de toute la partie collecte et visualisation des données. J'ai mis en place les flux Node-RED pour récupérer les données MQTT des 4 salles, configuré InfluxDB pour stocker les mesures et créé les dashboards Grafana pour les deux bâtiments. J'ai aussi développé l'interface web avec l'affichage dynamique des températures et le script PHP de récupération automatique des données capteurs vers la base de données MySQL.</p>

    <h3>Problèmes rencontrés</h3>
    <p>Le principal problème a été au départ avec la commande mosquitto_sub qui était incorrecte, ce qui nous a fait perdre du temps. Une fois corrigée, la récupération des données s'est bien déroulée. Il a aussi fallu bien configurer les tags InfluxDB pour séparer les données par salle.</p>

    <h3>Solutions apportées</h3>
    <p>On a corrigé la commande MQTT et bien structuré les nœuds InfluxDB dans Node-RED avec les bons tags. Pour Grafana on a créé un dashboard par bâtiment ce qui rend la lecture plus claire.</p>

    <h3>Degré de satisfaction</h3>
    <p>La chaîne MQTT → Node-RED → InfluxDB → Grafana fonctionne correctement et les données s'affichent bien en temps réel. Je suis satisfait du résultat obtenu sur cette partie du projet.</p>
</section>

<section>
    <h2>Synthèse personnelle — Touré Mahamoudou</h2>

    <h3>Travail réalisé</h3>
    <p>Je me suis occupé de la coordination globale du projet et du suivi de la documentation. J'ai également géré la mise en ligne des fichiers sur le serveur LAMPP et assuré la communication entre les membres du groupe pour que les différentes parties du projet s'assemblent correctement.</p>

    <h3>Problèmes rencontrés</h3>
    <p>Il a fallu bien configurer les droits d'accès sur les fichiers pour que le serveur Apache puisse les lire correctement. La coordination entre les différentes parties du projet a aussi demandé une organisation rigoureuse.</p>

    <h3>Solutions apportées</h3>
    <p>On a vérifié les permissions des fichiers et ajusté la configuration pour que le site soit accessible depuis le navigateur. On a utilisé Git pour que chaque membre puisse travailler sur sa partie sans bloquer les autres.</p>

    <h3>Degré de satisfaction</h3>
    <p>Le projet a bien avancé dans les délais et les différentes parties s'intègrent bien ensemble. Je suis satisfait de la coordination globale qui a permis de livrer un projet complet.</p>
</section>

<section>
    <h2>Synthèse personnelle — Samuel LUKANU</h2>

    <h3>Travail réalisé</h3>
    <p>Je me suis occupé de la conception et l'administration de la base de données MySQL. J'ai créé le schéma de la base de données avec les tables Batiment, Salle, Capteur et Mesure, rédigé les scripts SQL d'initialisation et mis en place les relations entre les tables. J'ai aussi participé au développement du site web côté PHP.</p>

    <h3>Problèmes rencontrés</h3>
    <p>La connexion à la base de données MySQL depuis PHP a demandé quelques ajustements au niveau des paramètres de connexion sur LAMPP. La gestion des clés étrangères entre les tables a aussi nécessité une réflexion sur l'ordre d'insertion des données.</p>

    <h3>Solutions apportées</h3>
    <p>On a vérifié la configuration de LAMPP et testé les requêtes SQL directement dans PhpMyAdmin avant de les intégrer dans le code PHP. On a aussi bien défini l'ordre de création et d'alimentation des tables pour respecter les contraintes de clés étrangères.</p>

    <h3>Degré de satisfaction</h3>
    <p>La base de données fonctionne correctement et les requêtes PHP récupèrent bien les données attendues. Je suis satisfait de la structure mise en place qui permet une gestion claire et évolutive des capteurs et des bâtiments.</p>
</section>

<div style="margin-top: 20px; text-align: center;">
    <a href="https://github.com/Tihi1920/Sae23/tree/main" target="_blank" style="display: inline-block; background-color: #A0522D; color: white; padding: 12px 24px; text-decoration: none; border-radius: 10px; font-weight: bold;">Consulter notre dépôt GitHub</a>
</div>

<?php include 'footer.php'; ?>
