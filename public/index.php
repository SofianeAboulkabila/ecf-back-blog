<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
    <title>index</title>
</head>

<body>

    <!-- HEADER -->

    <?php
    include '../templates/header.php';
    ?>


    <!-- NOTICE DU FONCTIONNEMENT -->
    <section class="notice">
        <div class="notice-content">
            <h2>Notice du fonctionnement du site</h2>
            <ul>
                <li>Utilisation de PHP pour la logique côté serveur.</li>
                <li>Utilisation de MySQL pour la gestion de la base de données.</li>
                <li>Mise en place d'un système d'authentification sécurisé ( hash +s mtp_mail )</li>
                <li>Gestion des articles avec des opérations CRUD (Create, Read, /!\ Update, /!\ Delete).</li>
                <li>Affichage dynamique des articles avec des jointures de tables.</li>
                <li>Utilisation d'AJAX pour les interactions asynchrones avec le serveur + dynamisme likes en temps
                    réel.</li>
                <li>Gestion des likes et des commentaires sur les articles.</li>
                <li>/!\ AJOUTER SEARCH BAR /!\Recherche d'articles avec des requêtes SQL avancées.</li>
                <li>Mise en place de l'interface utilisateur avec HTML / CSS / JAVASCRIPT.</li>
            </ul>
            <p>- Sofiane Aboulkabila</p>
        </div>
    </section>


</body>

</html>