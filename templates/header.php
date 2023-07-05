<!-- NAVBAR -->
<header>
    <nav class="navbar">
        <div class="nav-logo">
            <a href="../public/index.php">LOGO</a>
        </div>
        <div class="nav-links">
            <a href="../public/index.php">Accueil</a>
            <a href="../public/blog.php">Blog</a>
        </div>
        <div class="nav-auth">
            <?php


            // Inclure le fichier de connexion à la base de données
            require '../functions/db_connect.php';

            // Connexion à la base de données
            $pdo = db_connect();


            session_start();

            // Vérifier si l'utilisateur a le rôle "ROLE_ADMIN"
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $role_sql = "SELECT role FROM users WHERE id = :user_id";
                $role_stmt = $pdo->prepare($role_sql);
                $role_stmt->bindParam(':user_id', $user_id);
                $role_stmt->execute();

                if ($role_stmt->rowCount() > 0) {
                    $row = $role_stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row['role'] === 'ROLE_ADMIN') {
                        // Utilisateur avec le rôle d'administrateur, afficher le bouton d'administration
                        echo '<a href="../security/admin.php" style="margin-right: 30px;">Admin</a>';
                    }
                }
                // Afficher le lien de déconnexion
                echo '<a href="../security/profil.php" style="margin-right: 30px;">Profil</a>';
                echo '<a href="../functions/deconnexion.php">Déconnexion</a>';
            } else {
                // Utilisateur non connecté, afficher le lien de connexion
                echo '<a href="../security/connexion.php">Connexion</a>';
            }

            ?>
        </div>
    </nav>
</header>