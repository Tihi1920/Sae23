# SAE23 - Mettre en place une solution informatique pour l'entreprise

Projet réalisé dans le cadre de la SAE23, BUT1 Réseaux et Télécoms, IUT de Blagnac.

## Objectif

Le but du projet est de récupérer les données de capteurs température, humidité, CO2 et pression installés dans les salles de l'IUT de Blagnac et de les afficher de façon claire. On a fait ça en deux parties : une chaîne avec des conteneurs Docker (Mosquitto, Node-RED, InfluxDB, Grafana) et un site web dynamique en PHP/MySQL hébergé sur un serveur LAMPP.

## Equipe

- Youssouf Nael DIALLO : Node-RED, InfluxDB, Grafana, interface web avec les jauges
- Touré Mahamoudou : envoi des fichiers via FTP, script PHP pour récupérer les données MQTT, automatisation avec crontab
- Samuel LUKANU : développement du site web dynamique, code PHP et CSS

## Technologies utilisées

- Docker
- Mosquitto pour le broker MQTT
- Node-RED
- InfluxDB
- Grafana
- MySQL
- HTML, CSS, PHP, JavaScript
- Bash et crontab
- Serveur LAMPP
- Git et GitHub

## Principe de la chaîne

Les capteurs envoient leurs données sur le broker MQTT. Node-RED récupère ces données et les envoie dans InfluxDB. Grafana vient ensuite lire InfluxDB pour afficher les graphiques. En parallèle, un script PHP récupère aussi les données MQTT et les insère dans la base MySQL pour qu'elles soient affichées sur le site web.

## Contenu du dépôt

- site : tout le code du site web, les pages PHP et le CSS
- nodered : les flows Node-RED exportés
- scripts : le script PHP qui récupère les données MQTT et le script de sauvegarde de la base
- bdd : le schéma de la base de données

## Comment lancer le projet

Pour lancer la chaîne Docker, démarrer les conteneurs avec la commande docker start suivi du nom de chaque conteneur. Node-RED est accessible sur le port 1880, InfluxDB sur le port 8086 et Grafana sur le port 3000.

Pour le site web, il faut mettre le dossier site dans le htdocs de LAMPP puis démarrer Apache et MySQL avec la commande sudo /opt/lampp/lampp start. Le site est ensuite accessible depuis le navigateur.

Pour la base de données, il faut importer le fichier sql dans MySQL via PhpMyAdmin.

Pour automatiser la récupération des données, on a mis le script PHP dans le crontab pour qu'il se lance tout seul toutes les minutes.

On a aussi utilisé crontab avec l'option @reboot pour que les conteneurs Docker et le serveur LAMPP démarrent automatiquement dès que la VM s'allume, sans avoir besoin de se connecter ou de lancer quoi que ce soit à la main.

## Gestion de version

On a utilisé Git avec une branche main pour la version stable et des branches séparées pour chaque partie du travail qu'on a ensuite fusionnées avec main une fois testées.
