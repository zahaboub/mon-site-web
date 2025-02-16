<?php
include 'db.php'; // Connexion à la base de données

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est déjà connecté, dans ce cas, rediriger vers la page protégée
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'moderateur') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: logout.php");
    }
    exit();
}

// Initialiser une variable pour le message d'erreur
$error_message = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Validation côté serveur de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Veuillez entrer un email valide.";
    }

    // Si l'email est valide, vérifiez les informations de connexion
    if (empty($error_message)) {
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch();

        // Si l'utilisateur existe
        if ($utilisateur) {
            // Comparer le mot de passe saisi avec le mot de passe haché
            if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $utilisateur['id'];
                $_SESSION['user_nom'] = $utilisateur['nom'];
                $_SESSION['user_email'] = $utilisateur['email'];
                $_SESSION['user_role'] = $utilisateur['role'];

                // Rediriger selon le rôle de l'utilisateur
                if ($_SESSION['user_role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($_SESSION['user_role'] == 'moderateur') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: profile.php");
                }
                exit();
            } else {
                $error_message = "Mot de passe incorrect.";
            }
        } else {
            $error_message = "Aucun utilisateur trouvé avec cet email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body 
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
.form-container {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
    text-align: center;
}

/* Titre du formulaire */
h2 {
    font-size: 24px;
    margin-bottom: 20px;
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
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px 20px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    color: #333;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
}

/* Effet au focus sur les champs */
input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #4e73df;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(78, 115, 223, 0.3);
}

/* Bouton de soumission */
button {
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
button:hover {
    background-color: #3c5bdb;
    transform: translateY(-2px);
}

/* Lien d'inscription */
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
</head>
<body>
    <div class="form-container">
        <h2>Se connecter</h2>

        <!-- Affichage des erreurs -->
        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="mot_de_passe">Mot de passe:</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
