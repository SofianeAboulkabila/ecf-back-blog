<?php
// Inclure le fichier de connexion à la base de données
require '../functions/db_connect.php';

// Connexion à la base de données
$pdo = db_connect();

// Vérifier si la recherche a été soumise
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];

    // Requête SQL pour rechercher les articles par mot-clé dans le titre ou le contenu
    $sql = "SELECT * FROM articles WHERE title LIKE :keyword OR content LIKE :keyword";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':keyword', '%' . $keyword . '%');
    $stmt->execute();

    // Afficher les résultats de la recherche
    if ($stmt->rowCount() > 0) {
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Afficher les informations de l'article
            echo '<div class="article-card">';
            echo '<h2>' . $result['title'] . '</h2>';
            echo '<p>' . $result['content'] . '</p>';
            echo '<img src="' . $result['image_path'] . '">';
            echo '</div>';
        }
    } else {
        echo 'Aucun résultat trouvé.';
    }
}
