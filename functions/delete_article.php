<?php

session_start();

require_once './db_connect.php';

// Vérifier si l'article ID est présent dans la requête POST
if (isset($_POST['th_articleId']) && isset($_SESSION['user_id'])) {
    // Récupérer l'ID de l'article à supprimer
    $th_articleId = $_POST['th_articleId'];

    try {
        // Connexion à la base de données
        $pdo = db_connect();

        // Désactiver les contraintes de clés étrangères
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

        // Supprimer l'article de la table "articles"
        $sqlDeleteArticle = "DELETE FROM articles WHERE id = :th_articleId";
        $stmtDeleteArticle = $pdo->prepare($sqlDeleteArticle);
        $stmtDeleteArticle->bindParam(':th_articleId', $th_articleId);
        $stmtDeleteArticle->execute();

        // Supprimer les commentaires de l'article
        $sqlDeleteComments = "DELETE FROM comments WHERE th_articleId = :th_articleId";
        $stmtDeleteComments = $pdo->prepare($sqlDeleteComments);
        $stmtDeleteComments->bindParam(':th_articleId', $th_articleId);
        $stmtDeleteComments->execute();

        // Supprimer les enregistrements dans la table "comment_likes" liés aux commentaires de l'article
        $sqlDeleteCommentLikes = "DELETE FROM comment_likes WHERE comment_id IN (SELECT id FROM comments WHERE th_articleId = :th_articleId)";
        $stmtDeleteCommentLikes = $pdo->prepare($sqlDeleteCommentLikes);
        $stmtDeleteCommentLikes->bindParam(':th_articleId', $th_articleId);
        $stmtDeleteCommentLikes->execute();

        // Réactiver les contraintes de clés étrangères
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        // Envoyer une réponse JSON indiquant que la suppression a réussi
        echo 'liked';

        // Rediriger l'utilisateur vers la page d'accueil ou une autre page de votre choix
        header('Location: ../public/blog.php');
    } catch (PDOException $e) {
        // Réactiver les contraintes de clés étrangères
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        // Envoyer une réponse JSON avec l'erreur en cas d'échec de la suppression
        echo 'unliked';
    }
}
