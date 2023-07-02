<?php

require_once './db_connect.php';
require_once '../vendor/autoload.php';

use Faker\Factory;

// Connexion à la base de données
$pdo = db_connect();

// Génération des fixtures avec Faker
$faker = Factory::create();

// Fonction pour générer des fixtures pour la table users
function generateUsersFixtures($faker)
{
    $usersFixtures = [];
    for ($i = 0; $i < 10; $i++) {
        $username = $faker->userName;
        $password = $faker->password;
        $email = $faker->unique()->email;
        $confirmationToken = $faker->uuid;
        $role = $faker->randomElement(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_MOD']);
        $confirmed = $faker->randomElement([true, false]); // Valeur aléatoire pour la colonne confirmed

        $usersFixtures[] = [
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'confirmation_token' => $confirmationToken,
            'role' => $role,
            'confirmed' => $confirmed
        ];
    }
    return $usersFixtures;
}


// Fonction pour générer des fixtures pour la table articles
function generateArticlesFixtures($faker)
{
    $articlesFixtures = [];
    for ($i = 0; $i < 10; $i++) {
        $title = $faker->sentence;
        $content = $faker->paragraph;
        $imagePath = $faker->imageUrl;
        $authorId = $faker->numberBetween(1, 10);
        $createdAt = $faker->dateTimeThisYear;

        $articlesFixtures[] = [
            'title' => $title,
            'content' => $content,
            'image_path' => $imagePath,
            'author_id' => $authorId,
            'created_at' => $createdAt->format('Y-m-d H:i:s')
        ];
    }
    return $articlesFixtures;
}


function generateArticlesLikesFixtures($faker)
{
    global $pdo;
    $articlesLikesFixtures = [];
    $existingArticleIds = [];

    // Récupérer les IDs existants dans la table articles
    $stmt = $pdo->prepare("SELECT id FROM articles");
    $stmt->execute();
    $existingArticles = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($existingArticles as $id) {
        $existingArticleIds[] = $id;
    }

    for ($i = 0; $i < 10; $i++) {
        $articleId = $faker->randomElement($existingArticleIds);
        $userId = $faker->numberBetween(1, 10);
        $createdAt = $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s');

        $articlesLikesFixtures[] = [
            'article_id' => $articleId,
            'user_id' => $userId,
            'created_at' => $createdAt
        ];
    }
    return $articlesLikesFixtures;
}


// Fonction pour générer des fixtures pour la table comments
function generateCommentFixtures($faker)
{
    $commentFixtures = [];
    for ($i = 0; $i < 10; $i++) {
        $articleId = $faker->numberBetween(1, 10);
        $userId = $faker->numberBetween(1, 10);
        $content = $faker->paragraph;
        $createdAt = $faker->dateTimeThisYear;

        $commentFixtures[] = [
            'article_id' => $articleId,
            'user_id' => $userId,
            'content' => $content,
            'created_at' => $createdAt->format('Y-m-d H:i:s')
        ];
    }
    return $commentFixtures;
}

function generateCommentLikesFixtures($faker)
{
    $commentLikesFixtures = [];
    for ($i = 0; $i < 10; $i++) {
        $commentId = $faker->numberBetween(1, 10);
        $userId = $faker->numberBetween(1, 10);
        $createdAt = $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s');

        $commentLikesFixtures[] = [
            'comment_id' => $commentId,
            'user_id' => $userId,
            'created_at' => $createdAt
        ];
    }
    return $commentLikesFixtures;
}


// Fonction pour insérer les fixtures dans la base de données
function insertFixtures($table, $fixtures)
{
    global $pdo;
    $columns = implode(', ', array_keys($fixtures[0]));
    $placeholders = rtrim(str_repeat('?,', count($fixtures[0])), ',');
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    foreach ($fixtures as $fixture) {
        $stmt->execute(array_values($fixture));
    }
}

// Appel des fonctions de génération de fixtures
$usersFixtures = generateUsersFixtures($faker);
$articlesFixtures = generateArticlesFixtures($faker);
$articlesLikesFixtures = generateArticlesLikesFixtures($faker);
$commentFixtures = generateCommentFixtures($faker);
$commentLikesFixtures = generateCommentLikesFixtures($faker);

// Insertion des fixtures dans la base de données
insertFixtures("users", $usersFixtures);
insertFixtures("articles", $articlesFixtures);
insertFixtures("article_likes", $articlesLikesFixtures);
insertFixtures("comments", $commentFixtures);
insertFixtures("comment_likes", $commentLikesFixtures);

// Fermeture de la connexion à la base de données
$pdo = null;

echo "Génération de fixtures terminée.";
