<?php

include 'db.php'; // Inclure la connexion à la base de données

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mon-site-db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérification des données reçues via AJAX
var_dump($_POST); // Afficher les données reçues (pour débogage)

// Si les données ne sont pas vides, vous pouvez commencer à les récupérer
$combobox1Value = isset($_POST['combobox1Value']) ? $_POST['combobox1Value'] : null;
$combobox2Value = isset($_POST['combobox2Value']) ? $_POST['combobox2Value'] : null;
$currentTime = isset($_POST['currentTime']) ? $_POST['currentTime'] : null;
$result1Value = isset($_POST['result1Value']) ? $_POST['result1Value'] : null;
$result2Value = isset($_POST['result2Value']) ? $_POST['result2Value'] : null;
$result3Value = isset($_POST['result3Value']) ? $_POST['result3Value'] : null;
$supplementary1 = isset($_POST['supplementary1']) ? $_POST['supplementary1'] : null;
$supplementary2 = isset($_POST['supplementary2']) ? $_POST['supplementary2'] : null;
$supplementary3 = isset($_POST['supplementary3']) ? $_POST['supplementary3'] : null;
$totalSum = isset($_POST['totalSum']) ? $_POST['totalSum'] : 0;

// Vérifier si toutes les données nécessaires sont présentes
if (empty($combobox1Value) || empty($combobox2Value) || empty($currentTime) || empty($result1Value) || empty($result2Value) || empty($result3Value)) {
    die("Erreur: Certaines données sont manquantes.");
}

// Préparer la requête SQL avec des placeholders
$sql = "INSERT INTO tickets (combobox1, combobox2, current_time, result1, result2, result3, supplementary1, supplementary2, supplementary3, total_sum) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Préparer la déclaration
$stmt = $conn->prepare($sql);

// Vérifier si la préparation de la déclaration a réussi
if ($stmt === false) {
    die("Erreur de préparation de la déclaration: " . $conn->error);
}

// Lier les paramètres à la déclaration préparée
$stmt->bind_param("sssssssssd", $combobox1Value, $combobox2Value, $currentTime, $result1Value, $result2Value, $result3Value, $supplementary1, $supplementary2, $supplementary3, $totalSum);

// Exécuter la déclaration préparée
if ($stmt->execute()) {
    echo "Ticket ajouté avec succès";
} else {
    die("Erreur lors de l'insertion: " . $stmt->error); // Affiche plus de détails sur l'erreur
}

// Fermer la déclaration et la connexion
$stmt->close();
$conn->close();
?>