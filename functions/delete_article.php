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


        // Supprimer l'article de la table "articles"
        $sqlDeleteArticle = "DELETE FROM articles WHERE id = :th_articleId";
        $stmtDeleteArticle = $pdo->prepare($sqlDeleteArticle);
        $stmtDeleteArticle->bindParam(':th_articleId', $th_articleId);
        $stmtDeleteArticle->execute();

        // Supprimer les commentaires de l'article
        $sqlDeleteComments = "DELETE FROM comments WHERE article_id = :th_articleId";
        $stmtDeleteComments = $pdo->prepare($sqlDeleteComments);
        $stmtDeleteComments->bindParam(':th_articleId', $th_articleId);
        $stmtDeleteComments->execute();

        // Supprimer les enregistrements dans la table "article_likes" liés à l'article
        $sqlDeleteArticleLikes = "DELETE FROM article_likes WHERE article_id = :th_articleId";
        $stmtDeleteArticleLikes = $pdo->prepare($sqlDeleteArticleLikes);
        $stmtDeleteArticleLikes->bindParam(':th_articleId', $th_articleId);
        $stmtDeleteArticleLikes->execute();

        // Supprimer les enregistrements dans la table "comment_likes" liés aux commentaires de l'article
        $sqlDeleteCommentLikes = "DELETE FROM comment_likes WHERE comment_id IN (SELECT id FROM comments WHERE article_id = :th_articleId)";
        $stmtDeleteCommentLikes = $pdo->prepare($sqlDeleteCommentLikes);
        $stmtDeleteCommentLikes->bindParam(':th_articleId', $th_articleId);
        $stmtDeleteCommentLikes->execute();




        // Envoyer une réponse JSON indiquant que la suppression a réussi
        echo 'liked';
    } catch (PDOException $e) {


        // Envoyer une réponse JSON avec l'erreur en cas d'échec de la suppression
        echo 'unliked';
    }
}
