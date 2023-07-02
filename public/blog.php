<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../public/style.css">
    <title>Blog</title>
</head>

<body>
    <!-- HEADER -->
    <?php
    include '../templates/header.php';

    // ARTICLES

    // Inclure le fichier de connexion à la base de données
    require '../functions/db_connect.php';

    // Connexion à la base de données
    $pdo = db_connect();

    // Requête SQL pour récupérer les articles
    $sql = "SELECT * FROM articles";
    $stmt = $pdo->query($sql);

    // Vérifier si des articles ont été trouvés
    if ($stmt->rowCount() > 0) {
        echo "<div class='article-container'>"; // Ajout de la div article-container


        // Afficher les articles sous forme de cartes
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $article_id = $row['id']; // Récupérer l'ID de l'article
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

                // Vérifier le rôle de l'utilisateur
                $user_id = $_SESSION['user_id'] ?? null;

                // Requête SQL pour récupérer le rôle de l'utilisateur
                $role_sql = "SELECT role FROM users WHERE id = :user_id";
                $role_stmt = $pdo->prepare($role_sql);
                $role_stmt->bindParam(':user_id', $user_id);
                $role_stmt->execute();
                // Vérifier si l'utilisateur a déjà aimé l'article
                $liked = false;
                if ($user_id) {
                    $liked_sql = "SELECT COUNT(*) as liked_count FROM article_likes WHERE article_id = :article_id AND user_id = :user_id";
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
                            // Affichez le bouton de suppression de l'article ici
                            echo '
                            <form class="form-style-none" method="POST" action="../functions/delete_article.php">
                                <input type="hidden" name="article_id" value="' . $article_id . '">
                                <button type="submit" class="like-history-button delete-article" style="border-radius: 50px; border: 3px solid black;" name="delete_article" id="delete_article" value="' . $article_id . '">
                                    Supprimer l\'article
                                </button>
                            </form>';
                        } else {
                            // L'utilisateur n'a pas le rôle "ROLE_ADMIN"
                            // Affichez un message indiquant qu'il n'est pas autorisé à supprimer l'article
                            echo 'Vous n\'êtes pas autorisés à supprimer cet article.';
                        }
                    } else {
                        // Aucun résultat trouvé pour l'utilisateur
                        // Affichez un message d'erreur ou effectuez une autre action appropriée
                        echo 'Une erreur s\'est produite lors de la vérification des autorisations.';
                    }
                } else {
                    // L'utilisateur n'est pas connecté
                    // Affichez un message indiquant qu'il doit se connecter pour supprimer l'article
                    echo 'Vous devez vous connecter pour supprimer cet article.';
                }


                echo "<h2>$title</h2>";
                echo "<p>$content</p>";
                echo "<img src='$imagePath' alt='Image de l'article'>";
                echo "<p>Auteur : $author_name</p>";
                echo "<p>Date de création : $createdAt</p>";
                echo "<div class='article-actions'>";


                $likeButtonClass = $liked ? 'liked-button' : 'like-button';

                echo "<form class='form-style-none' method='POST'>";
                echo "<button type='submit' class='$likeButtonClass' name='likeButton' value='$article_id'>Like</button>";
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
                            $comment_likes_sql = "SELECT COUNT(*) as like_count FROM comment_likes WHERE comment_id = :comment_id";
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
                                echo "<a href='../security/connexion.php'>Connectez-vous pour liker le commentaire</a>";
                            }

                            echo "<span class='like-count'>$like_count_comment</span>";
                            echo "</form>";



                            echo "</div>";

                            echo "</div>";
                        } else {
                            echo "<a href='../security/connexion.php'>Connectez-vous pour commenter</a>";
                        }
                    }
                }
            }
            if (isset($_SESSION['user_id'])) {
                // Formulaire d'ajout de commentaire

                echo "<form class='comment-form' method='POST'>";
                echo "<textarea class='textarea' name='comment_content' placeholder='Votre commentaire'></textarea>";
                echo '<button data-article-id=' . $article_id . ' class="comment-form-button" type="submit" value="' . $article_id . '">Ajouter un commentaire!</button>';
                echo "</form>";
            } else {
                echo "<a href='../security/connexion.php'>Connectez-vous pour commenter</a>";
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
            echo "</div>"; // Fermeture de la div article-card

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
            <form method="POST" action="../functions/add_article.php" class="custom-form">
                <div>
                    <label for="title" class="custom-label">Titre:</label>
                    <input type="text" id="title" name="title" required class="custom-input">
                </div>
                <div>
                    <label for="content" class="custom-label">Contenu:</label>
                    <textarea id="content" name="content" required class="custom-textarea"></textarea>
                </div>
                <div>
                    <label for="image_path" class="custom-label">Chemin de l\'image:</label>
                    <input type="text" id="image_path" name="image_path" class="custom-input">
                </div>
                <button type="submit" class="custom-button">Ajouter l\'article</button>
            </form>';
        }
    }


    ?>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var commentButtons = document.getElementsByClassName('comment-button');
            var likeHistoryButtons = document.getElementsByClassName('like-history-button');
            var likeHistorContainer = document.getElementsByClassName('like-history-ciontainer');

            // Boucle pour les boutons "comment-button"
            for (var i = 0; i < commentButtons.length; i++) {
                commentButtons[i].addEventListener('click', function(e) {
                    e.preventDefault();
                    // Votre code pour le bouton "comment-button"

                    // Récupérer la div 'comment-container' associée au bouton cliqué
                    var commentContainer = this.parentNode.querySelector('.comment-container');

                    // Vérifier que commentContainer est une référence à un élément DOM
                    if (commentContainer instanceof Element) {
                        // Obtenir le style réellement appliqué à la div 'comment-container'
                        var computedStyle = window.getComputedStyle(commentContainer);
                        var isVisible = computedStyle.display !== 'none';

                        // Afficher ou cacher la div 'comment-container' en modifiant la valeur de style 'display'
                        if (isVisible) {
                            commentContainer.style.display = 'none';
                        } else {
                            commentContainer.style.display = 'block';
                        }
                    }
                });
            }

            // Code pour les boutons "like-history-button"
            for (var i = 0; i < likeHistoryButtons.length; i++) {
                likeHistoryButtons[i].addEventListener('click', function(e) {
                    e.preventDefault();

                    // Récupérer l'élément 'like-history-container' associé au bouton cliqué
                    var likeHistoryContainer = this.parentNode.querySelector('.like-history-container');

                    // Vérifier que likeHistoryContainer est une référence à un élément DOM
                    if (likeHistoryContainer instanceof Element) {
                        // Obtenir le style réellement appliqué à l'élément 'like-history-container'
                        var computedStyle = window.getComputedStyle(likeHistoryContainer);
                        var isVisible = computedStyle.display !== 'none';

                        // Afficher ou cacher l'élément 'like-history-container' en modifiant la valeur de style 'display'
                        if (isVisible) {
                            likeHistoryContainer.style.display = 'none';
                        } else {
                            likeHistoryContainer.style.display = 'block';
                        }
                    }
                });
            }


            var likeButtons = document.getElementsByClassName('like-button');
            var likedButtons = document.getElementsByClassName('liked-button');

            // Fusionner les deux collections en une seule
            var allButtons = Array.from(likeButtons).concat(Array.from(likedButtons));

            for (var i = 0; i < allButtons.length; i++) {
                allButtons[i].addEventListener('click', function(e) {
                    e.preventDefault();
                    var articleId = this.value;
                    var likeCountElement = this.nextElementSibling;

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../functions/like_article.php');
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            if (response === 'liked') {
                                // Mettre à jour l'interface utilisateur pour afficher que l'article a été aimé
                                likeCountElement.textContent = parseInt(likeCountElement.textContent) +
                                    1;
                                // Ajouter la classe 'liked-button' et supprimer la classe 'like-button'
                                likeCountElement.previousElementSibling.classList.add('liked-button');
                                likeCountElement.previousElementSibling.classList.remove('like-button');
                            } else if (response === 'unliked') {
                                // Mettre à jour l'interface utilisateur pour afficher que l'article n'est plus aimé
                                likeCountElement.textContent = parseInt(likeCountElement.textContent) -
                                    1;
                                // Ajouter la classe 'like-button' et supprimer la classe 'liked-button'
                                likeCountElement.previousElementSibling.classList.add('like-button');
                                likeCountElement.previousElementSibling.classList.remove(
                                    'liked-button');
                            } else if (response === 'not_logged_in') {
                                // Gérer le cas où l'utilisateur n'est pas connecté
                                console.log('Utilisateur non connecté');
                            } else {
                                // Gérer les autres réponses inattendues
                                console.log('Réponse inattendue du serveur');
                            }
                        } else {
                            console.error(xhr.statusText);
                        }
                    };
                    xhr.onerror = function() {
                        console.error(xhr.responseText);
                    };
                    xhr.send('likeButton=' + encodeURIComponent(articleId));
                });
            }

            var likeButtons = document.getElementsByClassName('like-comment-button');

            for (var i = 0; i < likeButtons.length; i++) {
                likeButtons[i].addEventListener('click', function(e) {
                    e.preventDefault();

                    var commentId = this.parentNode.querySelector('input[name=comment_id]').value;
                    var likeCountElement = this.nextElementSibling;

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../functions/like_comment.php');
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            if (response === 'liked') {
                                // Mettre à jour l'interface utilisateur pour afficher que le commentaire a été aimé
                                likeCountElement.textContent = parseInt(likeCountElement.textContent) +
                                    1;
                                // Ajouter la classe 'liked-comment-button' et supprimer la classe 'like-comment-button'
                                likeCountElement.previousElementSibling.classList.add(
                                    'liked-comment-button');
                                likeCountElement.previousElementSibling.classList.remove(
                                    'like-comment-button');
                            } else if (response === 'unliked') {
                                // Mettre à jour l'interface utilisateur pour afficher que le commentaire n'est plus aimé
                                likeCountElement.textContent = parseInt(likeCountElement.textContent) -
                                    1;
                                // Ajouter la classe 'like-comment-button' et supprimer la classe 'liked-comment-button'
                                likeCountElement.previousElementSibling.classList.add(
                                    'like-comment-button');
                                likeCountElement.previousElementSibling.classList.remove(
                                    'liked-comment-button');
                            } else if (response === 'not_logged_in') {
                                // Gérer le cas où l'utilisateur n'est pas connecté
                                console.log('Utilisateur non connecté');
                            } else {
                                // Gérer les autres réponses inattendues
                                console.log('Réponse inattendue du serveur');
                            }
                        } else {
                            console.error(xhr.statusText);
                        }
                    };
                    xhr.onerror = function() {
                        console.error(xhr.responseText);
                    };
                    xhr.send('comment_id=' + encodeURIComponent(commentId));
                });
            }

            var commentForms = document.getElementsByClassName('comment-form-button');

            for (var i = 0; i < commentForms.length; i++) {
                (function(index) {
                    commentForms[index].addEventListener('click', function(e) {
                        const articleId = this.getAttribute('data-article-id');
                        const commentContent = this.previousElementSibling.value;

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '../functions/add_comment.php');
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                var response = xhr.responseText;
                            } else {
                                console.error(xhr.statusText);
                            }
                        };
                        xhr.onerror = function() {
                            console.error(xhr.responseText);
                        };
                        xhr.send(
                            'commentContent=' +
                            encodeURIComponent(commentContent) +
                            '&article_id=' +
                            encodeURIComponent(articleId)
                        );
                    });
                })(i);
                var buttons = document.getElementsByClassName('delete-article');

                // Gérer le clic sur le bouton de suppression de l'article
                for (let i = 0; i < buttons.length; i++) {
                    buttons[i].addEventListener('click', function(e) {

                        // Récupérer l'ID de l'article à supprimer
                        var th_articleId = this.value;
                        console.log(th_articleId); // Ajout de console.log pour afficher la valeur

                        // Effectuer la requête AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '../functions/delete_article.php');
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                var response = xhr.responseText;
                                if (response === 'liked') {
                                    // La suppression a réussi
                                    console.log('Article supprimé avec succès');
                                    // Rafraîchir la page
                                    location.reload();
                                } else if (response === 'unliked') {
                                    // La suppression a échoué
                                    console.log('Erreur lors de la suppression de l\'article : ' +
                                        response.message);
                                }
                            } else {
                                console.error(xhr.statusText);
                            }
                        };
                        xhr.onerror = function() {
                            console.error(xhr.responseText);
                        };
                        xhr.send('th_articleId=' + encodeURIComponent(th_articleId));
                        var buttons = document.getElementsByClassName('delete-article');
                    });
                }

            }

        })
    </script>





</body>

</html>