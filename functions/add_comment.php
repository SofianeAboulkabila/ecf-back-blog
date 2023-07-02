<?php

// Inclure le fichier de connexion à la base de données
require './db_connect.php';
session_start();

// Traitement du formulaire de commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentContent']) && isset($_POST['article_id'])) {

    $pdo = db_connect();

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        echo 'not_logged_in';
        exit;
    }

    $comment_content = $_POST['commentContent'];
    $article_id = $_POST['article_id'];

    $user_id = $_SESSION['user_id'];

    try {
        // Préparer la requête d'insertion du commentaire
        $sql = "INSERT INTO comments (content, article_id, user_id, created_at) VALUES (:content, :article_id, :user_id, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':content', $comment_content);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        echo 'success';
    } catch (PDOException $e) {
        echo 'database_error: ' . $e->getMessage();
        exit;
    } catch (Exception $e) {
        echo 'unexpected_error: ' . $e->getMessage();
        exit;
    }
}
