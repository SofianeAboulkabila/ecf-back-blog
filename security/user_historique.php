<?php
// HEADER

include '../templates/header.php';


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../public/blog.php");
    exit;
}

// Récupérer l'ID de l'utilisateur depuis l'URL
if (isset($_GET['user_id'])) {
    // Supprimer les caractères non numériques de l'ID de l'utilisateur
    $user_id = filter_var($_GET['user_id'], FILTER_SANITIZE_NUMBER_INT);
} else {
    // Rediriger vers une page d'erreur si l'ID de l'utilisateur n'est pas fourni
    header("Location: ../public/index.php");
    exit;
}


// Récupérer les articles likés par l'utilisateur
$sql = "SELECT article_id, title
        FROM article_likes, articles
        WHERE article_likes.article_id = articles.id
        AND article_likes.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

$likedArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les commentaires likés par l'utilisateur avec le contenu des articles correspondants
$sql = "SELECT comment_likes.comment_id, comments.content
 FROM comment_likes, comments, articles
 WHERE comment_likes.comment_id = comments.id
 AND comments.article_id = articles.id
 AND comment_likes.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

$likedComments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les commentaires postés par l'utilisateur avec leur article_id correspondant
$sql = "SELECT comments.id, comments.content, comments.article_id, articles.title
        FROM comments
        JOIN articles ON comments.article_id = articles.id
        WHERE comments.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$postedComments = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../public/style.css">
    <title>User Historique</title>
</head>

<body>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .container {
        width: auto;
        margin: 50px auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    h2 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        text-align: center;
        border: 1px solid #a39999;
    }

    th {
        background-color: #f5f5f5;
        font-weight: bold;
    }
    </style>

    <div class="container">
        <h1>Historique de l'utilisateur</h1>

        <h2>Articles likés</h2>
        <table>
            <tr>
                <th>ID de l'article</th>
                <th>Titre</th>
            </tr>
            <?php foreach ($likedArticles as $article) : ?>
            <tr>
                <td><?php echo $article['article_id']; ?></td>
                <td><?php echo $article['title']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h2>Commentaires likés</h2>
        <table>
            <tr>
                <th>ID du commentaire</th>
                <th>Contenu</th>
            </tr>
            <?php if ($likedComments !== null) : ?>
            <?php foreach ($likedComments as $comment) : ?>
            <tr>
                <td><?php echo $comment['comment_id']; ?></td>
                <td><?php echo $comment['content']; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <h2>Commentaires postés</h2>
        <table>
            <tr>
                <th>ID du commentaire</th>
                <th>Contenu</th>
                <th>ID de l'article</th>
            </tr>
            <?php foreach ($postedComments as $comment) : ?>
            <tr>
                <td><?php echo $comment['id']; ?></td>
                <td><?php echo $comment['content']; ?></td>
                <td><?php echo $comment['article_id']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>


    </div>
</body>

</html>