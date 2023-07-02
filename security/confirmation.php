<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../public/style.css">
    <title>Email_send</title>
</head>

<body>

    <script>

    </script>

    <!-- HEADER -->
    <?php
    include '../templates/header.php';
    ?>

    <!-- EMAIL CONFIRMER -->




    <?php



    require_once '../functions/db_connect.php';

    // Récupération du token depuis l'URL
    $token = $_GET['token'];

    // Vérification de la présence du nom d'utilisateur dans l'URL
    if (isset($_GET['username'])) {
        $username = $_GET['username'];

        // Connexion à la base de données
        $pdo = db_connect();

        $stmt = $pdo->prepare("UPDATE users SET confirmed = 1 WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            // Succès : la colonne "confirmed" a été mise à jour pour l'utilisateur spécifié
            echo "<p class='email-send container-email'>La colonne 'confirmed' a été mise à jour avec succès pour l'utilisateur $username.</p>";
        } else {
            // Échec : aucun utilisateur n'a été trouvé avec le nom d'utilisateur spécifié
            echo "<p class='email-send container-email'>Aucun utilisateur trouvé avec le nom d'utilisateur $username.</p>";
        }
    } else {
        // Échec : aucun nom d'utilisateur n'est présent dans l'URL
        echo "Aucun nom d'utilisateur n'est présent dans l'URL.";
    }
    ?>



</body>

</html>