-- phpMyAdmin SQL Dump

-- version 5.2.1

-- https://www.phpmyadmin.net/

--

-- Hôte : 127.0.0.1

-- Généré le : dim. 02 juil. 2023 à 15:24

-- Version du serveur : 10.4.28-MariaDB

-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */

;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */

;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */

;

/*!40101 SET NAMES utf8mb4 */

;

--

-- Base de données : `ecf_back`

--

-- --------------------------------------------------------

--

-- Structure de la table `articles`

--

CREATE TABLE
    `articles` (
        `id` int(11) NOT NULL,
        `title` varchar(50) DEFAULT NULL,
        `content` text DEFAULT NULL,
        `image_path` varchar(255) DEFAULT NULL,
        `author_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--

-- Déchargement des données de la table `articles`

--

INSERT INTO
    `articles` (
        `id`,
        `title`,
        `content`,
        `image_path`,
        `author_id`,
        `created_at`
    )
VALUES (
        1,
        'Est voluptate blanditiis animi aut blanditiis non ',
        'Laudantium occaecati aut maxime ut rem. Officia repudiandae quia deserunt voluptates repellat. Officia ut veniam cum dignissimos. Quia odio vel enim.',
        'https://via.placeholder.com/640x480.png/001122?text=dolor',
        7,
        '2023-03-19 21:20:42'
    ), (
        2,
        'Facere nam nemo sed nulla magni.',
        'Quidem quia repellat iure maiores architecto illo et. Qui unde laboriosam vero aliquid. Sit recusandae in et quae.',
        'https://via.placeholder.com/640x480.png/000044?text=blanditiis',
        4,
        '2023-04-22 04:33:52'
    ), (
        3,
        'Doloribus velit debitis amet cumque molestiae.',
        'Rerum laborum laboriosam aut sed asperiores est recusandae velit. Dolores iure eaque amet iusto. Soluta alias pariatur est quas repudiandae dignissimos magnam. Placeat voluptate sed autem ut.',
        'https://via.placeholder.com/640x480.png/002244?text=laboriosam',
        3,
        '2023-01-23 20:49:19'
    ), (
        4,
        'Reprehenderit modi est odio sit.',
        'Eligendi sit distinctio autem autem ut. Quia omnis autem veritatis molestiae corrupti doloribus. Earum et ab doloremque temporibus.',
        'https://via.placeholder.com/640x480.png/0033ff?text=vel',
        1,
        '2023-04-22 08:13:21'
    ), (
        5,
        'Molestias fuga voluptas tempore.',
        'Omnis qui rerum qui sed et est aut aut. Eum facilis hic ut omnis. Dolorem delectus consequuntur maiores veniam tenetur corrupti.',
        'https://via.placeholder.com/640x480.png/00dddd?text=incidunt',
        2,
        '2023-05-21 12:01:18'
    ), (
        6,
        'Et voluptatem modi sed laudantium.',
        'Sed maxime hic et porro. Repudiandae debitis sit placeat itaque suscipit earum laboriosam deserunt. Architecto non eveniet possimus quia. Qui tenetur dolorem maiores sit impedit deserunt.',
        'https://via.placeholder.com/640x480.png/00ffff?text=itaque',
        6,
        '2023-04-03 07:46:58'
    ), (
        7,
        'Et aut quas expedita ut consequatur ut.',
        'Accusantium fuga maxime et deserunt asperiores. Rerum recusandae est amet totam ut harum quis. Id maiores aut tenetur. Voluptates tempore eum est repellendus eius.',
        'https://via.placeholder.com/640x480.png/000044?text=aut',
        2,
        '2023-04-23 16:31:12'
    ), (
        8,
        'Impedit hic et et nihil.',
        'Et nobis commodi qui minima. Pariatur voluptas suscipit officiis voluptate quasi voluptas vero. Dolorem nulla quaerat nihil est voluptatibus atque.',
        'https://via.placeholder.com/640x480.png/0066cc?text=earum',
        9,
        '2023-06-07 12:47:33'
    ), (
        9,
        'Vero dolorem in et soluta quo.',
        'Deleniti ex quasi quasi ullam non suscipit minima. Itaque sunt a sed nemo. Excepturi aliquam qui quam enim voluptas qui. Saepe illum doloremque ipsum. Sit molestiae omnis vel et qui provident.',
        'https://via.placeholder.com/640x480.png/004477?text=ad',
        7,
        '2023-06-25 04:35:01'
    ), (
        10,
        'Blanditiis quisquam similique voluptatem odit nequ',
        'Sed odit aut libero sit aut. Voluptatem neque maxime sint minus. At perferendis molestiae omnis sit.',
        'https://via.placeholder.com/640x480.png/00cc88?text=eos',
        8,
        '2023-04-22 06:49:49'
    );

-- --------------------------------------------------------

--

-- Structure de la table `article_likes`

--

CREATE TABLE
    `article_likes` (
        `id` int(11) NOT NULL,
        `article_id` int(11) DEFAULT NULL,
        `user_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--

-- Déchargement des données de la table `article_likes`

--

INSERT INTO
    `article_likes` (
        `id`,
        `article_id`,
        `user_id`,
        `created_at`
    )
VALUES (1, 1, 3, '2023-04-30 01:37:37'), (2, 4, 8, '2022-09-18 14:15:32'), (3, 3, 2, '2022-12-09 09:28:25'), (4, 10, 6, '2023-01-09 17:38:36'), (5, 9, 9, '2023-04-14 02:35:57'), (6, 8, 5, '2023-01-23 01:14:57'), (7, 7, 6, '2023-05-11 16:25:45'), (8, 2, 3, '2022-07-26 14:22:20'), (9, 5, 2, '2023-01-24 05:28:43'), (10, 6, 2, '2022-10-07 21:11:57'), (11, 11, 10, NULL), (12, 12, 10, NULL), (13, 14, 10, NULL);

-- --------------------------------------------------------

--

-- Structure de la table `comments`

--

CREATE TABLE
    `comments` (
        `id` int(11) NOT NULL,
        `article_id` int(11) DEFAULT NULL,
        `user_id` int(11) DEFAULT NULL,
        `content` text DEFAULT NULL,
        `created_at` datetime DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--

-- Déchargement des données de la table `comments`

--

INSERT INTO
    `comments` (
        `id`,
        `article_id`,
        `user_id`,
        `content`,
        `created_at`
    )
VALUES (
        1,
        9,
        1,
        'Est fugiat ut enim qui molestiae aut fugiat. Iusto reiciendis ullam omnis enim. Doloribus consequatur voluptate nostrum excepturi. Perspiciatis recusandae quo sequi ut consequatur ullam. Nulla repellendus ducimus incidunt.',
        '2023-03-24 21:27:39'
    ), (
        2,
        1,
        3,
        'Itaque exercitationem consequuntur dicta quia nihil in voluptatem. Magni est nemo quaerat fugiat ut minima.',
        '2023-01-09 01:57:14'
    ), (
        3,
        5,
        1,
        'Quia ullam quis autem quisquam harum temporibus facere. Vel tenetur voluptas deleniti eos et ipsa suscipit cupiditate. Quos dignissimos et fugiat et.',
        '2023-02-27 04:37:38'
    ), (
        4,
        9,
        4,
        'Ea quae cumque fuga eligendi consectetur itaque. Et in voluptatibus quia fuga nesciunt vel. Porro modi ut accusamus nulla nostrum ut quia. Maiores ducimus perferendis corporis sit ut esse aliquam.',
        '2023-01-04 20:24:38'
    ), (
        5,
        8,
        8,
        'Velit eum adipisci corporis esse. Reprehenderit et id doloremque distinctio excepturi id qui. Animi quo error alias ut quia dicta molestiae est.',
        '2023-04-06 18:34:56'
    ), (
        6,
        4,
        1,
        'Esse autem quia ipsum ducimus vitae est. Quia commodi qui deleniti. Sed voluptatem minus iste ducimus ut.',
        '2023-05-26 01:25:05'
    ), (
        7,
        9,
        9,
        'Quo ut aut nihil ab est ea asperiores. Incidunt corrupti mollitia sed. Et et atque in consequatur voluptas.',
        '2023-04-26 18:14:47'
    ), (
        8,
        2,
        4,
        'Molestiae sed facilis consequatur dolorem culpa qui ut est. Nemo commodi eum aut voluptatem sed reiciendis. Alias facere odit facere nobis impedit eligendi laborum eum.',
        '2023-05-19 09:17:05'
    ), (
        9,
        9,
        2,
        'Sint asperiores reprehenderit laudantium et. Aliquid voluptates tempora voluptatem fuga asperiores at. Tempore quae molestias repudiandae laborum pariatur animi. Blanditiis ipsam veritatis est cum qui. Laudantium temporibus sit saepe sequi.',
        '2023-06-27 23:41:08'
    ), (
        10,
        4,
        9,
        'Earum et iure tempore sint mollitia reiciendis. Officia est cumque voluptates. Dolor aperiam nihil cumque totam quam veritatis. Et quod temporibus expedita ut dolore debitis aut. Reprehenderit qui consequatur aut rerum excepturi dolor officiis.',
        '2023-03-16 10:05:53'
    ), (
        11,
        11,
        10,
        'dsq',
        '2023-07-02 14:40:29'
    ), (
        12,
        12,
        10,
        'fdsqfdsqf',
        '2023-07-02 14:43:11'
    ), (
        13,
        3,
        10,
        'za',
        '2023-07-02 14:49:52'
    );

-- --------------------------------------------------------

--

-- Structure de la table `comment_likes`

--

CREATE TABLE
    `comment_likes` (
        `id` int(11) NOT NULL,
        `comment_id` int(11) DEFAULT NULL,
        `user_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--

-- Déchargement des données de la table `comment_likes`

--

INSERT INTO
    `comment_likes` (
        `id`,
        `comment_id`,
        `user_id`,
        `created_at`
    )
VALUES (1, 5, 9, '2022-07-16 22:10:34'), (2, 3, 2, '2022-08-07 11:50:26'), (3, 5, 6, '2023-04-06 05:55:53'), (4, 1, 9, '2022-10-25 00:23:52'), (5, 4, 6, '2023-02-26 15:45:33'), (6, 7, 6, '2022-08-05 14:19:02'), (7, 2, 3, '2022-11-12 22:19:06'), (8, 4, 8, '2022-11-27 11:11:42'), (9, 6, 2, '2022-11-23 19:58:54'), (
        10,
        5,
        10,
        '2022-08-02 09:45:30'
    ), (11, 11, 10, NULL), (12, 12, 10, NULL);

-- --------------------------------------------------------

--

-- Structure de la table `users`

--

CREATE TABLE
    `users` (
        `id` int(11) NOT NULL,
        `username` varchar(20) DEFAULT NULL,
        `password` varchar(20) DEFAULT NULL,
        `email` varchar(50) DEFAULT NULL,
        `confirmation_token` varchar(60) DEFAULT NULL,
        `role` varchar(20) DEFAULT NULL,
        `confirmed` tinyint(1) DEFAULT 0
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--

-- Déchargement des données de la table `users`

--

INSERT INTO
    `users` (
        `id`,
        `username`,
        `password`,
        `email`,
        `confirmation_token`,
        `role`,
        `confirmed`
    )
VALUES (
        1,
        'taryn27',
        'ZvahNGD`9',
        'johns.angeline@sporer.info',
        'baeb02dc-fe63-31c1-ad09-1ec8ce30122e',
        'ROLE_ADMIN',
        1
    ), (
        2,
        'jfeeney',
        '{-tsER@myBF87,L(I[6',
        'wunsch.milo@graham.net',
        '0d85f32a-9b43-3f62-8b0c-320f30198e5f',
        'ROLE_USER',
        1
    ), (
        3,
        'bkohler',
        'X_/z+sy\'!|&',
        'cbergnaum@schimmel.org',
        '5cba12e0-7123-3884-858a-bcf90993299a',
        'ROLE_ADMIN',
        0
    ), (
        4,
        'fay.monserrate',
        '[A#jEoz18c|+F+P_D\\D',
        'rudy98@gmail.com',
        '1599f781-8bcd-3806-8bb1-f865f72e0509',
        'ROLE_USER',
        0
    ), (
        5,
        'telly.kuphal',
        '~7t~RHmN0',
        'jarred74@hills.org',
        'b72cd351-e2b3-338b-b397-a75b3d241c49',
        'ROLE_ADMIN',
        1
    ), (
        6,
        'chahn',
        'z6}[r$xLg+',
        'corkery.gustave@kuphal.com',
        '0ea8958e-2b5b-3a2f-af24-0f3cb506c86f',
        'ROLE_USER',
        0
    ), (
        7,
        'aryanna.hackett',
        '4sUv/p-.CZ!VPVL^wB7',
        'elody.lockman@hoeger.com',
        '8196c54e-5df1-38dd-8d95-ed09c8bafd49',
        'ROLE_USER',
        1
    ), (
        8,
        'adella.cartwright',
        '9CV7%Bs+z8\"*@',
        'osanford@gmail.com',
        '72ae85e3-5e67-356a-93a7-5df3a38d158e',
        'ROLE_MOD',
        0
    ), (
        9,
        'aubree94',
        '2,QN<jUCM[PC^8[MWG',
        'larson.gerry@gmail.com',
        '75204cf4-f9e4-377b-92dc-2f3c58f3942f',
        'ROLE_ADMIN',
        0
    ), (
        10,
        'janice.rutherford',
        'cLb>M3ZFz',
        'ferne.quigley@becker.com',
        '948d05db-56c6-3d1b-89d4-c6a985659fab',
        'ROLE_ADMIN',
        0
    );

--

-- Index pour les tables déchargées

--

--

-- Index pour la table `articles`

--

ALTER TABLE `articles`
ADD PRIMARY KEY (`id`),
ADD
    KEY `articles_ibfk_1` (`author_id`);

--

-- Index pour la table `article_likes`

--

ALTER TABLE `article_likes`
ADD PRIMARY KEY (`id`),
ADD
    KEY `fk_article_id` (`article_id`),
ADD
    KEY `article_likes_ibfk_2` (`user_id`);

--

-- Index pour la table `comments`

--

ALTER TABLE `comments`
ADD PRIMARY KEY (`id`),
ADD
    KEY `fk_comments_article` (`article_id`),
ADD
    KEY `comments_ibfk_2` (`user_id`);

--

-- Index pour la table `comment_likes`

--

ALTER TABLE `comment_likes`
ADD PRIMARY KEY (`id`),
ADD
    KEY `comment_likes_ibfk_1` (`comment_id`),
ADD
    KEY `comment_likes_ibfk_2` (`user_id`);

--

-- Index pour la table `users`

--

ALTER TABLE `users` ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT pour les tables déchargées

--

--

-- AUTO_INCREMENT pour la table `articles`

--

ALTER TABLE
    `articles` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 20;

--

-- AUTO_INCREMENT pour la table `article_likes`

--

ALTER TABLE
    `article_likes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 15;

--

-- AUTO_INCREMENT pour la table `comments`

--

ALTER TABLE
    `comments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 14;

--

-- AUTO_INCREMENT pour la table `comment_likes`

--

ALTER TABLE
    `comment_likes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 15;

--

-- AUTO_INCREMENT pour la table `users`

--

ALTER TABLE
    `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 11;

--

-- Contraintes pour les tables déchargées

--

--

-- Contraintes pour la table `articles`

--

ALTER TABLE `articles`
ADD
    CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--

-- Contraintes pour la table `article_likes`

--

ALTER TABLE `article_likes`
ADD
    CONSTRAINT `article_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
ADD
    CONSTRAINT `fk_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--

-- Contraintes pour la table `comments`

--

ALTER TABLE `comments`
ADD
    CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD
    CONSTRAINT `fk_comments_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
ADD
    CONSTRAINT `fk_comments_article_new` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--

-- Contraintes pour la table `comment_likes`

--

ALTER TABLE `comment_likes`
ADD
    CONSTRAINT `comment_likes_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
ADD
    CONSTRAINT `comment_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD
    CONSTRAINT `fk_comment_likes_comment` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
ADD
    CONSTRAINT `fk_comment_likes_comment_new` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
ADD
    CONSTRAINT `fk_comment_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */

;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */

;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */

;