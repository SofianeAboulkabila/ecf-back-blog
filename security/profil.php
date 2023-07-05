<?php
// HEADER

include '../templates/header.php';


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../public/blog.php");
    exit;
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur connecté depuis la base de données
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fonction pour générer le lien vers la page contenant toutes les informations
function generateProfileLink($user_id)
{
    return 'http://localhost/ecf_back_sofiane/security/user_historique.php?user_id=' . $user_id;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="../public/style.css">
    <title>Profil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
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

        .profile-info p {
            margin: 10px 0;
        }

        .profile-info strong {
            font-weight: bold;
        }

        .profile-link-container {
            margin-top: 20px;
            text-align: center;
        }

        #profile-link {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        #profile-link:hover {
            background-color: #0056b3;
        }

        #copy-message {
            display: none;
            color: #007bff;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Profil</h1>

        <div class="profile-info">
            <p><strong>Utilisateur connecté :</strong> <?php echo $user['username']; ?></p>
            <p><strong>Email :</strong> <?php echo $user['email']; ?></p>
        </div>

        <div class="profile-link-container">
            <strong>Partager le lien vers vos listes :</strong>
            <br>
            <br>
            <a href="<?php echo generateProfileLink($user_id); ?>" id="profile-link">Cliquez pour copier le lien</a>
            <br>
            <br>
            <span id="copy-message">Lien copié !</span>
            <br>
        </div>
    </div>

    <script>
        document.getElementById('profile-link').addEventListener('click', function(event) {
            event.preventDefault();

            var profileLink = "<?php echo generateProfileLink($user_id); ?>";

            navigator.clipboard.writeText(profileLink).then(function() {
                var copyMessage = document.getElementById('copy-message');
                copyMessage.style.display = 'inline';
                setTimeout(function() {
                    copyMessage.style.display = 'none';
                }, 2000);
            });
        });
    </script>
</body>

</html>