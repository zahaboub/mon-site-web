<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'mon-site-db';  
$username = 'root';       
$password = '';           

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurer le mode d'erreur de PDO pour lancer des exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Gérer l'erreur de connexion
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit();
}
?>
