<?php
require_once '../functions/db_connect.php';

require_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once '../vendor/phpmailer/phpmailer/src/SMTP.php';
require_once '../vendor/phpmailer/phpmailer/src/Exception.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validation des champs du formulaire
$errors = [];
$username = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validation des champs ...


    // Vérification de l'existence d'un compte avec le même nom d'utilisateur
    $pdo = db_connect();
    // Vérification de l'existence d'un compte avec le même nom d'utilisateur
    $stmtUsername = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmtUsername->bindParam(':username', $username);
    $stmtUsername->execute();
    $existingUsername = $stmtUsername->fetch();

    if ($existingUsername && strcmp($existingUsername['username'], $username) === 0) {
        $errors[] = "<p class='error_message'>Le nom d'utilisateur est déjà utilisé.</p>";
    }
    // Vérification de l'existence d'un compte avec la même adresse e-mail
    $stmtEmail = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmtEmail->bindParam(':email', $email);
    $stmtEmail->execute();
    $existingEmail = $stmtEmail->fetch();

    if ($existingEmail && strcmp($existingEmail['email'], $email) === 0) {
        $errors[] = "<p class='error_message'>L'adresse e-mail est déjà utilisée.</p>";
    }



    // Si aucune erreur n'est présente, enregistrement de l'utilisateur dans la base de données
    if (empty($errors)) {
        // Génération du token de confirmation aléatoire
        $confirmationToken = bin2hex(random_bytes(60));

        // Requête d'insertion de l'utilisateur dans la table users
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, confirmation_token, role) VALUES (?, ?, ?, ?, ?)");

        // Utilisation des paramètres préparés pour éviter les injections SQL
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $email);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(3, $hashedPassword);
        $stmt->bindParam(4, $confirmationToken);
        $stmt->bindValue(5, 'ROLE_USER');

        if ($stmt->execute()) {
            // Envoi de l'e-mail de validation
            $mail = new PHPMailer(true);

            try {

                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.office365.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'sofiane.aboulkabila@outlook.com';
                $mail->Password   = '159357Abk';
                $mail->SMTPSecure = 'STARTTLS';
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('sofiane.aboulkabila@outlook.com', 'Mailer');
                $mail->addAddress($email, $username);

                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Confirmation de votre compte';
                $mail->Body    = 'Cliquez sur le lien suivant pour confirmer votre compte : <a href="http://localhost/ecf_back_sofiane/security/confirmation.php?token=' . $confirmationToken . '&username=' . $username . '">Confirmer</a>';

                // Envoi de l'e-mail
                if ($mail->send()) {
                    // L'e-mail a été envoyé avec succès
                    header("Location: ../security/email_envoyer.php");
                    exit();
                } else {
                    // Une erreur s'est produite lors de l'envoi de l'e-mail
                    $errors[] = 'Une erreur s\'est produite lors de l\'envoi de l\'e-mail de confirmation.';
                }
            } catch (Exception $e) {
                $errors[] = "Une erreur s'est produite lors de l'envoi de l'e-mail de confirmation. Veuillez réessayer plus tard.";
            }

            // Fermeture de la connexion à la base de données
            $pdo = null;

            // Redirection vers une page de succès ou d'accueil
            header("Location: ../security/email_envoyer.php");
            exit();
        } else {
            $errors[] = "Une erreur s'est produite lors de l'enregistrement de l'utilisateur.";
        }
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
    <title>Inscription</title>
</head>

<body>

    <!-- HEADER -->
    <?php
    include '../templates/header.php';
    ?>

    <!-- FORM INSCRIPTION -->

    <div class="container">
        <form action="" method="POST">
            <h1>Inscription</h1>
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
                <input type="text" id="username" name="username" required class="input-field" value="<?php echo $username; ?>">
            </div>
            <div class="input-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required value="<?php echo $email; ?>">
            </div>
            <div class="input-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirmer mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="input-group">
                <button type="submit" class="btn">S'inscrire</button>
            </div>
        </form>
    </div>

</body>

</html>