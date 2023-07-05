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
        $usersFixtures[] = [
            'username' => $faker->userName,
            'password' => $faker->password,
            'email' => $faker->unique()->email,
            'confirmation_token' => $faker->uuid,
            'role' => $faker->randomElement(['ROLE_USER', 'ROLE_ADMIN']),
            'confirmed' => $faker->boolean()
        ];
    }
    return $usersFixtures;
}

// Fonction pour générer des fixtures pour la table articles
function generateArticlesFixtures($faker)
{
    $articlesFixtures = [];
    for ($i = 0; $i < 10; $i++) {
        $articlesFixtures[] = [
            'title' => $faker->sentence,
            'content' => $faker->paragraph,
            'image_path' => $faker->imageUrl,
            'author_id' => $faker->numberBetween(1, 10),
            'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s')
        ];
    }
    return $articlesFixtures;
}

// Fonction pour générer des fixtures pour la table article_likes
function generateArticlesLikesFixtures($faker)
{
    $articlesLikesFixtures = [];
    for ($i = 0; $i < 10; $i++) {
        $articlesLikesFixtures[] = [
            'article_id' => $faker->numberBetween(1, 10),
            'user_id' => $faker->numberBetween(1, 10),
            'created_at' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')
        ];
    }
    return $articlesLikesFixtures;
}

// Fonction pour générer des fixtures pour la table comments
function generateCommentFixtures($faker)
{
    $commentFixtures = [];
    for ($i = 0; $i < 10; $i++) {
        $commentFixtures[] = [
            'article_id' => $faker->numberBetween(1, 10),
            'user_id' => $faker->numberBetween(1, 10),
            'content' => $faker->paragraph,
            'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s')
        ];
    }
    return $commentFixtures;
}

// Fonction pour générer des fixtures pour la table comment_likes
function generateCommentLikesFixtures($faker)
{
    $commentLikesFixtures = [];
    for ($i = 0; $i < 10; $i++) {
        $commentLikesFixtures[] = [
            'comment_id' => $faker->numberBetween(1, 10),
            'user_id' => $faker->numberBetween(1, 10),
            'created_at' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')
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
