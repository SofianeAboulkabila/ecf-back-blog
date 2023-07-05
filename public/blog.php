<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../public/style.css">
    <!-- JAVASCRIPT -->
    <script src="../functions/blog_ajax.js" defer></script>
    <title>Blog</title>
</head>

<body>
    <!-- HEADER -->

    <?php

    include '../templates/header.php';


    // BARRE DE RECHERCHE ARTICLES


    echo '<!-- Barre de recherche -->';
    echo '<div class="search-container">';
    echo '<h1 class="search-title">Barre de recherche<h1>';
    echo '<input type="text" id="searchInput" placeholder="Rechercher par mot-clé...">';
    echo '<button id="searchButton">Rechercher</button>';
    echo '</div>';



    // ARTICLES

    // Requête SQL pour récupérer les articles
    $sql = "SELECT * FROM articles";
    $stmt = $pdo->query($sql);

    // Vérifier si des articles ont été trouvés
    if ($stmt->rowCount() > 0) {
        echo "<div id='searchResults' class='article-container'>";


        // Afficher les articles sous forme de cartes
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $article_id = $row['id'];
            $title = $row['title'];
            $content = $row['content'];
            $imagePath = $row['image_path'];
            $authorId = $row['author_id'];
            $createdAt = $row['created_at'];

            // Requête SQL pour récupérer les informations de l'auteur
            $author_sql = "SELECT * FROM users WHERE id = :author_id";
            $author_stmt = $pdo->prepare($author_sql);
            $author_stmt->bindParam(':author_id', $authorId);
            $author_stmt->execute();

            // Vérifier si l'auteur existe
            if ($author_stmt->rowCount() > 0) {
                $author_row = $author_stmt->fetch(PDO::FETCH_ASSOC);
                $author_name = $author_row['username'];

                // Compter les likes pour l'article
                $article_likes_sql = "SELECT COUNT(*) as article_like_count FROM article_likes WHERE article_id = :article_id";
                $article_likes_stmt = $pdo->prepare($article_likes_sql);
                $article_likes_stmt->bindParam(':article_id', $article_id);
                $article_likes_stmt->execute();

                // Récupérer le nombre de likes de l'article
                $article_like_count = $article_likes_stmt->fetch(PDO::FETCH_ASSOC)['article_like_count'];


                // Requête SQL pour récupérer le rôle de l'utilisateur

                $user_id = $_SESSION['user_id'] ?? null;

                $role_sql = "SELECT role FROM users WHERE id = :user_id";
                $role_stmt = $pdo->prepare($role_sql);
                $role_stmt->bindParam(':user_id', $user_id);
                $role_stmt->execute();
                // Vérifier si l'utilisateur a déjà aimé l'article
                $liked = false;
                if ($user_id) {
                    $liked_sql = "SELECT COUNT(*) as liked_count FROM article_likes WHERE article_id = :article_id AND user_id =
        :user_id";
                    $liked_stmt = $pdo->prepare($liked_sql);
                    $liked_stmt->bindParam(':article_id', $article_id);
                    $liked_stmt->bindParam(':user_id', $user_id);
                    $liked_stmt->execute();
                    $liked_count = $liked_stmt->fetch(PDO::FETCH_ASSOC)['liked_count'];
                    $liked = $liked_count > 0;
                }

                // Afficher les informations de l'article dans une carte
                echo "<div class='article-card'>";

                // SUPPRIMER ARTICLE
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    $role_sql = "SELECT role FROM users WHERE id = :user_id";
                    $role_stmt = $pdo->prepare($role_sql);
                    $role_stmt->bindParam(':user_id', $user_id);
                    $role_stmt->execute();

                    if ($role_stmt->rowCount() > 0) {
                        $row = $role_stmt->fetch(PDO::FETCH_ASSOC);
                        if ($row['role'] === 'ROLE_ADMIN') {
                            // L'utilisateur a le rôle "ROLE_ADMIN"
                            echo '
            <form class="form-style-none" method="POST" action="../functions/delete_article.php">
                <input type="hidden" name="article_id" value="' . $article_id . '">
                <button type="submit" class="like-history-button delete-article"
                    style="border-radius: 50px; border: 3px solid black;" name="delete_article" id="delete_article"
                    value="' . $article_id . '">
                    Supprimer l\'article
                </button>
            </form>';
                        } else {
                            // L'utilisateur n'a pas le rôle "ROLE_ADMIN"
                            echo ' <a class="connect-info">Vous n\'êtes pas autorisés à supprimer cet article.</a> ';
                        }
                    } else {
                        // Aucun résultat trouvé pour l'utilisateur
                        echo 'Une erreur s\'est produite lors de la vérification des autorisations.';
                    }
                } else {
                    // L'utilisateur n'est pas connecté
                    echo
                    "<a class='connect-info' href='../security/connexion.php'>Vous devez vous connecter pour supprimer cet
                article.</a>";
                }


                echo "<h2>$title</h2>";
                echo "<p>$content</p>";
                echo "<img src='$imagePath' alt='Image de l' article'>";
                echo "<p>Auteur : $author_name</p>";
                echo "<p>Date de création : $createdAt</p>";
                echo "<div class='article-actions'>";


                $likeButtonClass = $liked ? 'liked-button' : 'like-button';

                echo "<form class='form-style-none' method='POST'>";
                echo "<button type='submit' class='$likeButtonClass' name='likeButton'
                        value='$article_id'>Like</button>";
                echo "<span class='like-count'>$article_like_count</span>";
                echo "</form>";


                echo "<button class='comment-button'>Commentaire</button>";




                // Récupérer les commentaires associés à l'article
                $comments_sql = "SELECT * FROM comments WHERE article_id = :article_id";
                $comments_stmt = $pdo->prepare($comments_sql);
                $comments_stmt->bindParam(':article_id', $article_id);
                $comments_stmt->execute();

                echo "<div class='comment-container'>";
                while ($comment_row = $comments_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $comment_id = $comment_row['id']; // Ajout de la récupération de l'ID du commentaire

                    $comment_content = $comment_row['content'];
                    $comment_created_at = $comment_row['created_at'];
                    $user_id = $comment_row['user_id'];

                    // Requête SQL pour récupérer les informations de l'utilisateur
                    $user_sql = "SELECT * FROM users WHERE id = :user_id";
                    $user_stmt = $pdo->prepare($user_sql);
                    $user_stmt->bindParam(':user_id', $user_id);
                    $user_stmt->execute();

                    // Vérifier si l'utilisateur existe
                    if ($user_stmt->rowCount() > 0) {
                        $user_row = $user_stmt->fetch(PDO::FETCH_ASSOC);
                        $username = $user_row['username'];

                        echo "<div class='comment'>";
                        echo "<p>$comment_content</p>";
                        echo "<p>Date de création : $comment_created_at</p>";
                        echo "<p>Utilisateur : $username</p>";
                        echo "<div class='comment-actions'>";

                        if ($user_id) {
                            // Requête SQL pour compter les likes du commentaire
                            $comment_likes_sql = "SELECT COUNT(*) as like_count FROM comment_likes WHERE comment_id =
                            :comment_id";
                            $comment_likes_stmt = $pdo->prepare($comment_likes_sql);
                            $comment_likes_stmt->bindParam(':comment_id', $comment_id);
                            $comment_likes_stmt->execute();

                            // Récupérer le nombre de likes du commentaire
                            $like_count_comment = $comment_likes_stmt->fetch(PDO::FETCH_ASSOC)['like_count'];

                            echo "<form class='form-style-none' style='padding: 2px;' method='POST'>";
                            echo "<input type='hidden' name='comment_id' value='$comment_id'>";
                            if (isset($_SESSION['user_id'])) {
                                echo "<button class='like-comment-button' type='submit'>Like</button>";
                            } else {
                                // L'utilisateur n'est pas connecté
                                echo "<a class='connect-info' href='../security/connexion.php'>Connectez-vous pour liker
                                    le commentaire</a>";
                            }

                            echo "<span class='like-count'>$like_count_comment</span>";
                            echo "</form>";



                            echo "</div>";

                            echo "</div>";
                        } else {
                            echo "<a class='connect-info' href='../security/connexion.php'>Connectez-vous pour commenter</a>";
                        }
                    }
                }
            }
            if (isset($_SESSION['user_id'])) {
                // Formulaire d'ajout de commentaire

                echo "<form class='comment-form' method='POST'>";
                echo "<textarea class='textarea' name='comment_content'
                            placeholder='Votre commentaire'></textarea>";
                echo '<button data-article-id=' . $article_id . ' class="comment-form-button" type="submit"
                            value="' . $article_id . '">Ajouter un commentaire!</button>';
                echo "</form>";
            } else {
                echo "<a class='connect-info' href='../security/connexion.php'>Connectez-vous pour commenter</a>";
            }
            echo "</div>";

            // Afficher le bouton "Historique des likes"
            echo "<button class='like-history-button'>Historique des likes</button>";

            // Récupérer les likes et les noms des utilisateurs
            $like_history_sql = "SELECT article_likes.*, users.username FROM article_likes
                JOIN users ON article_likes.user_id = users.id
                WHERE article_likes.article_id = :article_id";
            $like_history_stmt = $pdo->prepare($like_history_sql);
            $like_history_stmt->bindParam(':article_id', $article_id);
            $like_history_stmt->execute();

            // Afficher l'historique des likes lorsque le bouton est cliqué
            echo "<div class='like-history-container'>";
            echo "<h3>Historique des likes</h3>";
            if ($like_history_stmt->rowCount() > 0) {
                echo "<ul class='like-history-list'>";
                while ($like_row = $like_history_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $username = $like_row['username'];
                    echo "<li>$username</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Aucun like pour cet article.</p>";
            }

            echo "</div>"; // Fermeture de la div like-history-container
            echo "</div>"; // Fermeture de la div article-actions
            echo "
        </div>"; // Fermeture de la div article-card

        }
        echo "</div>"; // Fermeture de la div article-container
    } else {
        // Aucun article trouvé
        echo "Aucun article trouvé.";
    }
    // Vérifier si la session est active
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $role_sql = "SELECT role FROM users WHERE id = :user_id";
        $role_stmt = $pdo->prepare($role_sql);
        $role_stmt->bindParam(':user_id', $user_id);
        $role_stmt->execute();
    }

    if ($role_stmt->rowCount() > 0) {
        $row = $role_stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['role'] === 'ROLE_ADMIN') {


            // L'utilisateur a le rôle "ROLE_ADMIN"
            // Affichez le formulaire d'ajout d'article
            echo '
    <form method="POST" action="../functions/add_article.php" class="add-article-form">

        <h1> AJOUTER ARTICLES </h1>

        <div>
            <label for="title" class="form-label">Titre :</label>
            <input type="text" id="title" name="title" required class="form-input">
        </div>
        <div>
            <label for="content" class="form-label">Contenu :</label>
            <textarea id="content" name="content" required class="form-textarea"></textarea>
        </div>
        <div>
            <label for="image_path" class="form-label">Chemin de l\'image :</label>
            <input type="text" id="image_path" name="image_path" class="form-input">
        </div>
        <button type="submit" class="form-button">Ajouter l\'article</button>
    </form>';
        }
    }


    ?>



</body>

</html>