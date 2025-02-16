<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

// Afficher le contenu de la page protégée
echo "Page protégée - Accès autorisé uniquement aux utilisateurs connectés.";
?>
