<?php include 'En-tete.php'; ?>

<section>
    <h2>Suivi Énergétique & Environnemental</h2>
    <p>Centralisation en temps réel des données de nos capteurs connectés pour un campus plus éco-responsable.</p>
</section>

<section>
    <h3>À quoi sert cette plateforme ?</h3>
    <p>Ce site internet interactif a été pensé et conçu dans le cadre de notre projet d'études (<strong>SAÉ 23</strong>). Il sert de tour de contrôle pour intercepter, organiser et stocker les données récoltées en continu par nos capteurs connectés (modules <strong>AM107</strong>). Grâce au protocole sécurisé <strong>MQTT (TLS)</strong>, ces mesures (comme le confort thermique ou l'humidité) voyagent de manière fiable vers notre base de données <strong>InfluxDB</strong>, avant d'être transformées en graphiques dynamiques directement lisibles ici et sur notre tableau de bord <strong>Grafana</strong>.</p>
</section>

<div id="tableau">
    <div>
        <section>
            <h2>Bâtiments sous Surveillance</h2>
            <p>Nous suivons actuellement l'activité de complexes académiques majeurs de notre campus afin d'analyser les variations de chaleur et d'optimiser la gestion de la ventilation.</p>
        </section>
    </div>
    <div>
        <section>
            <h2>Salles Instrumentées</h2>
            <p>Des capteurs multi-paramètres mesurent en continu l'environnement au cœur de espaces clés : salles de cours, laboratoires de réseaux et espaces administratifs.</p>
        </section>
    </div>
</div>

<section>
    <h3>Mentions Légales & Confidentialité</h3>
    <p>Ce système de Gestion Technique Centralisée (GTC) est un projet pédagogique développé au sein du département Réseaux et Télécommunications (R&T). Soucieux de la vie privée et en totale conformité avec le RGPD, le systeme ne collecte aucune donnée à caractère personnel : seules les constantes physiques des salles sont enregistrées. Hébergement : Serveur local LAMPP .</p>
</section>

<?php include 'footer.php'; ?>