<?php

// Inclure le fichier de connexion à la base de données
require './db_connect.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger l'utilisateur vers la page de connexion
    header('Location: ../security/connexion.php');
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imagePath = $_POST['image_path'];
    $authorId = $_SESSION['user_id'];
    $createdAt = date('Y-m-d H:i:s');


    // Inserer l'article dans la base de données
    try {
        $pdo = db_connect();

        // Préparer la requête d'insertion de l'article
        $sql = "INSERT INTO articles (title, content, image_path, author_id, created_at) 
                VALUES (:title, :content, :image_path, :author_id, :created_at)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image_path', $imagePath);
        $stmt->bindParam(':author_id', $authorId);
        $stmt->bindParam(':created_at', $createdAt);
        $stmt->execute();

        // Rediriger l'utilisateur vers la page d'accueil ou une autre page de votre choix
        header('Location: ../public/blog.php');
        exit;
    } catch (PDOException $e) {
        echo 'Une erreur s\'est produite lors de l\'ajout de l\'article : ' . $e->getMessage();
        exit;
    }
}
