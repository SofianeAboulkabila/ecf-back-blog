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
                if (session_status() === PHP_SESSION_NONE && session_id() === '') {
                    // La session n'a pas été démarrée, appelez session_start()
                    session_start();
                }
                // Vérifier si l'utilisateur est connecté
                if (isset($_SESSION['user_id'])) {
                    // Utilisateur connecté, afficher le lien de déconnexion
                    echo '<a href="../functions/deconnexion.php">Déconnexion</a>';
                } else {
                    // Utilisateur non connecté, afficher le lien de connexion
                    echo '<a href="../security/connexion.php">Connexion</a>';
                }
                ?>

         </div>
     </nav>
 </header>