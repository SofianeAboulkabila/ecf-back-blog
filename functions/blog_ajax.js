document.addEventListener('DOMContentLoaded', function () {

    var commentButtons = document.getElementsByClassName('comment-button');
    var likeHistoryButtons = document.getElementsByClassName('like-history-button');

    // Boucle pour les boutons "comment-button"
    for (var i = 0; i < commentButtons.length; i++) {
        commentButtons[i].addEventListener('click', function (e) {
            e.preventDefault();

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
        likeHistoryButtons[i].addEventListener('click', function (e) {
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
        allButtons[i].addEventListener('click', function (e) {
            e.preventDefault();
            var articleId = this.value;
            var likeCountElement = this.nextElementSibling;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../functions/like_article.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
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
            xhr.onerror = function () {
                console.error(xhr.responseText);
            };
            xhr.send('likeButton=' + encodeURIComponent(articleId));
        });
    }

    var likeButtons = document.getElementsByClassName('like-comment-button');

    for (var i = 0; i < likeButtons.length; i++) {
        likeButtons[i].addEventListener('click', function (e) {
            e.preventDefault();

            var commentId = this.parentNode.querySelector('input[name=comment_id]').value;
            var likeCountElement = this.nextElementSibling;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../functions/like_comment.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
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
            xhr.onerror = function () {
                console.error(xhr.responseText);
            };
            xhr.send('comment_id=' + encodeURIComponent(commentId));
        });
    }

    var commentForms = document.getElementsByClassName('comment-form-button');

    for (var i = 0; i < commentForms.length; i++) {
        (function (index) {
            commentForms[index].addEventListener('click', function (e) {
                const articleId = this.getAttribute('data-article-id');
                const commentContent = this.previousElementSibling.value;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../functions/add_comment.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = xhr.responseText;
                        // Rafraîchir la page
                        window.top.location = "./blog.php"
                    } else {
                        console.error(xhr.statusText);
                    }
                };
                xhr.onerror = function () {
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
            buttons[i].addEventListener('click', function (e) {

                // Récupérer l'ID de l'article à supprimer
                var th_articleId = this.value;
                console.log(th_articleId); // Ajout de console.log pour afficher la valeur

                // Effectuer la requête AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../functions/delete_article.php');
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = xhr.responseText;
                        if (response === 'liked') {
                            // La suppression a réussi
                            console.log('Article supprimé avec succès');
                            // Rafraîchir la page
                            window.top.location = "./blog.php"
                        } else if (response === 'unliked') {
                            // La suppression a échoué
                            console.log('Erreur lors de la suppression de l\'article : ' +
                                response.message);
                        }
                    } else {
                        console.error(xhr.statusText);
                    }
                };
                xhr.onerror = function () {
                    console.error(xhr.responseText);
                };
                xhr.send('th_articleId=' + encodeURIComponent(th_articleId));
            });
        }

    }

})


// Récupérer les éléments du DOM
const searchInput = document.getElementById('searchInput');
const searchButton = document.getElementById('searchButton');
const searchResults = document.getElementById('searchResults');

// Fonction pour effectuer la recherche
function performSearch() {
    const keyword = searchInput.value.trim();

    // Effectuer une requête AJAX vers le serveur
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `../functions/search_articles.php?keyword=${encodeURIComponent(keyword)}`, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Afficher les résultats de la recherche
                searchResults.innerHTML = xhr.responseText;
            } else {
                xhr.onerror = function () {
                    console.error(xhr.responseText);
                };
            }
        }
    };

    xhr.send();
}

// Attacher un événement au clic sur le bouton de recherche
searchButton.addEventListener('click', performSearch);

// Attacher un événement lorsque l'utilisateur appuie sur la touche Entrée dans le champ de saisie
searchInput.addEventListener('keyup', function (event) {
    if (event.key === 'Enter') {
        performSearch();
    }
});
