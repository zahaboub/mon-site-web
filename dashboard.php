<?php
session_start();
include 'db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté et a les bons droits (administrateur ou modérateur)
if (!isset($_SESSION['utilisateur_id']) || ($_SESSION['role'] != 'administrateur' && $_SESSION['role'] != 'moderateur')) {
    header("Location: login.php");
    exit();
}

// Récupérer les utilisateurs depuis la base de données
$stmt = $pdo->query("SELECT * FROM utilisateurs");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<h1>Bienvenue dans votre tableau de bord, <?php echo htmlspecialchars($_SESSION['nom']); ?> !</h1>


    <!-- Lien de déconnexion -->
    <a href="logout.php">Se déconnecter</a>

    <!-- Section pour afficher la liste des utilisateurs -->
    <h2>Liste des utilisateurs</h2>
    <?php
    // Affichage des utilisateurs
    foreach ($utilisateurs as $user) {
        echo "<p>";
        echo "Nom: " . htmlspecialchars($user['nom']) . "<br>";
        echo "Email: " . htmlspecialchars($user['email']) . "<br>";
        echo "Rôle: " . htmlspecialchars($user['role']) . "<br>";
        // Liens pour modifier et supprimer
        echo "<a href='edit_user.php?id=" . $user['id'] . "'>Modifier</a> | ";
        echo "<a href='delete_user.php?id=" . $user['id'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet utilisateur ?\");'>Supprimer</a>";
        echo "</p>";
    }
    ?>

</body>
</html>
