<?php
// Inclure le fichier de connexion à la base de données
require './db_connect.php';
session_start();

// Traitement du formulaire de like
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['likeButton'])) {
    $liked_article_id = $_POST['likeButton'];

    $pdo = db_connect();

    // Vérifier si l'utilisateur est connecté
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        // Vérifier si l'utilisateur a déjà aimé l'article
        $liked_sql = "SELECT COUNT(*) as liked_count FROM article_likes WHERE article_id = :article_id AND user_id = :user_id";
        $liked_stmt = $pdo->prepare($liked_sql);
        $liked_stmt->bindParam(':article_id', $liked_article_id);
        $liked_stmt->bindParam(':user_id', $user_id);
        $liked_stmt->execute();
        $liked_count = $liked_stmt->fetch(PDO::FETCH_ASSOC)['liked_count'];
        $liked = $liked_count > 0;

        if (!$liked) {
            // Ajouter un like à l'article
            $insert_like_sql = "INSERT INTO article_likes (article_id, user_id) VALUES (:article_id, :user_id)";
            $insert_like_stmt = $pdo->prepare($insert_like_sql);
            $insert_like_stmt->bindParam(':article_id', $liked_article_id);
            $insert_like_stmt->bindParam(':user_id', $user_id);
            $insert_like_stmt->execute();

            echo 'liked';
        } else {
            // Supprimer le like de l'article
            $delete_like_sql = "DELETE FROM article_likes WHERE article_id = :article_id AND user_id = :user_id";
            $delete_like_stmt = $pdo->prepare($delete_like_sql);
            $delete_like_stmt->bindParam(':article_id', $liked_article_id);
            $delete_like_stmt->bindParam(':user_id', $user_id);
            $delete_like_stmt->execute();

            echo 'unliked';
        }
    } else {
        echo 'not_logged_in';
    }
} else {
    echo 'invalid_request';
}