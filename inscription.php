<?php
include 'db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données soumises via le formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role']; // Récupérer le rôle sélectionné

    // Validation des données (par exemple, vérifier si l'email est valide)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "L'email n'est pas valide.";
    } else {
        // Vérifier si l'email existe déjà dans la base de données
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur) {
            echo "Cet email est déjà utilisé.";
        } else {
            // Insérer l'utilisateur dans la base de données avec son rôle
            $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            // Hashage du mot de passe pour la sécurité
            $stmt->execute([$nom, $email, password_hash($mot_de_passe, PASSWORD_DEFAULT), $role]);

            echo "Utilisateur ajouté avec succès !";
        }
    }
}
?>

<!-- Formulaire d'inscription -->
<form action="inscription.php" method="post">
    <label for="nom">Nom:</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="mot_de_passe">Mot de passe:</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

    <label for="role">Rôle:</label>
    <select name="role" id="role" required>
        <option value="utilisateur">Utilisateur</option>
        <option value="moderateur">Modérateur</option>
        <option value="admin">Administrateur</option>
    </select><br>
    <link rel="stylesheet" href="styles.css">


    <button type="submit">S'inscrire</button>
    <p>Connexion <a href="login.php">connectez ici ici</a></p>
<style>
    /* Global reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Corps de la page */
body {
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(135deg, #4e73df, #1c3d9e);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #fff;
}

/* Conteneur du formulaire */
form {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    max-width: 450px;
    width: 100%;
    text-align: center;
}

/* Titre du formulaire */
h2 {
    font-size: 26px;
    margin-bottom: 30px;
    color: #333;
}

/* Labels */
label {
    display: block;
    text-align: left;
    margin-bottom: 8px;
    font-size: 14px;
    color: #555;
}

/* Champs de formulaire */
input[type="text"],
input[type="email"],
input[type="password"],
select {
    width: 100%;
    padding: 12px 20px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    color: #333;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
}

/* Effet au focus sur les champs */
input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus,
select:focus {
    border-color: #4e73df;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(78, 115, 223, 0.3);
}

/* Style du bouton */
button[type="submit"] {
    background-color: #4e73df;
    color: white;
    width: 100%;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

/* Effet au survol du bouton */
button[type="submit"]:hover {
    background-color: #3c5bdb;
    transform: translateY(-2px);
}

/* Liens d'inscription */
p {
    margin-top: 20px;
    font-size: 14px;
    color: #555;
}

a {
    color: #4e73df;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Affichage des erreurs */
.error {
    color: #ff4e4e;
    margin-top: 10px;
    font-size: 14px;
}

/* Animation des transitions */
form {
    animation: fadeIn 1s ease-in-out;
}

/* Effet de fade-in pour le formulaire */
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}


 </style>
 



</form>
