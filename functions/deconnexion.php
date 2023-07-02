<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    // Supprimer la variable de session de l'ID de l'utilisateur
    unset($_SESSION['user_id']);
}

// Redirection vers la page de connexion ou d'accueil
header('Location: ../security/connexion.php');
exit();
