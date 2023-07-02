<?php

require  '../functions/db_connect.php';


if (session_status() === PHP_SESSION_NONE && session_id() === '') {
    // La session n'a pas été démarrée, appelez session_start()
    session_start();
}
// Vérifiez si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    // Redirigez vers la page index.php
    header('Location: ../public/index.php');
    exit();
}



function verifyPassword($passwordInput, $passwordHash)
{
    if (password_verify($passwordInput, $passwordHash)) {
        return true;
    } else {
        return false;
    }
}


if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $pdo = db_connect();

    $username = $_POST['username'];
    $passwordInput = $_POST['password'];

    $req = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $req->bindParam(':username', $username);
    $req->execute();

    $user = $req->fetch(PDO::FETCH_OBJ);

    $passwordHash = $user->password;

    if ($user) {
        if ($user && $passwordHash === $user->password && $user->confirmed == 1) {
            $_SESSION['user_id'] = $user->id;
            header('Location: ../public/index.php');
            exit();
        } else {
            $errors[] = 'Votre compte n\'est pas confirmé.';
        }
    } else {
        $errors[] = 'Nom d\'utilisateur ou mot de passe incorrect.';
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../public/style.css">
    <!-- JAVASCRIPT VALIDATE FORM -->
    <script src="../functions/validate_form.js" defer></script>
    <title>Connexion</title>
</head>

<body>


    <!-- HEADER -->
    <?php
    include '../templates/header.php';
    ?>

    <!-- FORM CONNEXION -->


    <div class="container">

        <form action="" method="POST">
            <h1>Connexion</h1>

            <?php
            // Affichage des erreurs
            if (!empty($errors)) {
                echo "<div class='error_message'>";
                foreach ($errors as $error) {
                    echo "<p>$error</p>";
                }
                echo "</div>";
            }
            ?>
            <br>

            <div class="input-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required class="input-field">
            </div>
            <div class="input-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required class="input-field">
            </div>
            <div class="input-group">
                <p>Vous n'avez pas encore de compte ? <a href="./inscription.php">Inscrivez-vous ici</a></p>
            </div>
            <div class="input-group">
                <button type="submit" class="btn">Se connecter</button>
            </div>
        </form>
    </div>

</body>

</html>