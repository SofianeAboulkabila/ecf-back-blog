<?php


// Inclure le fichier de connexion à la base de données
require './db_connect.php';
session_start();

// Vérifier si l'utilisateur est connecté
$user_id = $_SESSION['user_id'] ?? null;

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id'];

    $pdo = db_connect();

    try {
        // Vérifier si l'utilisateur a déjà aimé le commentaire
        $liked_sql = "SELECT COUNT(*) as liked_count FROM comment_likes WHERE comment_id = :comment_id AND user_id = :user_id";
        $liked_stmt = $pdo->prepare($liked_sql);
        $liked_stmt->bindParam(':comment_id', $comment_id);
        $liked_stmt->bindParam(':user_id', $user_id);
        $liked_stmt->execute();
        $liked_count = $liked_stmt->fetch(PDO::FETCH_ASSOC)['liked_count'];
        $liked = $liked_count > 0;

        if (!$liked) {
            // Ajouter un like au commentaire
            $insert_like_sql = "INSERT INTO comment_likes (comment_id, user_id, created_at) VALUES (:comment_id, :user_id, :created_at)";
            $insert_like_stmt = $pdo->prepare($insert_like_sql);
            $insert_like_stmt->bindParam(':comment_id', $comment_id);
            $insert_like_stmt->bindParam(':user_id', $user_id);
            $insert_like_stmt->bindValue(':created_at', date('Y-m-d H:i:s'));
            $insert_like_stmt->execute();

            echo 'liked';
        } else {
            // Supprimer le like du commentaire
            $delete_like_sql = "DELETE FROM comment_likes WHERE comment_id = :comment_id AND user_id = :user_id";
            $delete_like_stmt = $pdo->prepare($delete_like_sql);
            $delete_like_stmt->bindParam(':comment_id', $comment_id);
            $delete_like_stmt->bindParam(':user_id', $user_id);
            $delete_like_stmt->execute();

            echo 'unliked';
        }
    } catch (PDOException $e) {
        echo 'Une erreur s\'est produite lors de la gestion du like : ' . $e->getMessage();
        exit;
    }
} else {
    echo 'invalid_request';
}
