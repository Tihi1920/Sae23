<?php 
/* index.php - Platform introduction landing page */
include 'En-tete.php'; 
?>

<section>
    <h2>Suivi Énergétique & Environnemental</h2>
    <p>Centralisation en temps réel des données de nos capteurs connectés pour un campus plus éco-responsable.</p>
</section>

<section>
    <h3>À quoi sert cette plateforme ?</h3>
    <p>Ce site internet interactif a été pensé et conçu dans le cadre de notre projet d'études (<strong>SAÉ 23</strong>). Il sert de tour de contrôle pour intercepter, organiser et stocker les données récoltées en continu par nos capteurs connectés (modules <strong>AM107</strong>). Grâce au protocole sécurisé <strong>MQTT (TLS)</strong>, ces mesures de température voyagent de manière fiable vers notre base de données <strong>MySQL</strong>, avant d'être transformées en graphiques dynamiques directement lisibles ici et sur notre tableau de bord <strong>Grafana</strong>.</p>
</section>

<section id="tableau">
    <section>
        <h2>Bâtiments sous Surveillance</h2>
        <p>Nous suivons actuellement l'activité des bâtiments de notre campus afin d'analyser les variations de température et d'optimiser la gestion thermique des espaces.</p>
    </section>
    <section>
        <h2>Salles Instrumentées</h2>
        <p>Des capteurs de température mesurent en continu l'environnement au cœur des espaces clés : salles de cours, laboratoires de réseaux et espaces administratifs.</p>
    </section>
</section>

<section>
    <h3>Mentions Légales & Confidentialité</h3>
    <p>Ce système de Gestion Technique Centralisée (GTC) est un projet pédagogique développé au sein du département Réseaux et Télécommunications (R&T). Soucieux de la vie privée et en totale conformité avec le RGPD, le système ne collecte aucune donnée à caractère personnel : seules les constantes physiques des salles sont enregistrées. Hébergement : Serveur local LAMPP.</p>
</section>

<?php include 'footer.php'; ?>
