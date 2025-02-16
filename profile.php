<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

$user_role = $_SESSION['user_role'] ?? 'Rôle non défini';
$username = $_SESSION['username'] ?? 'Utilisateur inconnu';
$user_nom = $_SESSION['user_nom'] ?? 'Utilisateur';

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "mon-site-db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $combobox1Value = $_POST['combobox1Value'] ?? '';
    $combobox2Value = $_POST['combobox2Value'] ?? '';
    $result1Value = $_POST['result1Value'] ?? '';
    $result2Value = $_POST['result2Value'] ?? '';
    $result3Value = $_POST['result3Value'] ?? '';
    $sumG = $_POST['supplementary1'] ?? 0;
    $sumP = $_POST['supplementary2'] ?? 0;
    $sumGP = $_POST['supplementary3'] ?? 0;
    $totalSum = $_POST['totalSum'] ?? 0;
    $currentDate = date("Y-m-d");

    $sql = "INSERT INTO tickets (combobox1, combobox2, result1, result2, result3, sumG, sumP, sumGP, total_sum, date, username) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssdddss", $combobox1Value, $combobox2Value, $result1Value, $result2Value, $result3Value, $sumG, $sumP, $sumGP, $totalSum, $currentDate, $username);

        if ($stmt->execute()) {
            echo "<script>alert('Ticket ajouté avec succès');</script>";
        } else {
            die("Erreur lors de l'insertion du ticket: " . $stmt->error);
        }

        $stmt->close();
    } else {
        die("Erreur de préparation de la déclaration: " . $conn->error);
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil utilisateur</title>
    <style>
        /* Styles CSS */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(189, 219, 249);
            color: #333;
        }

        h2 {
            text-align: center;
            color: #6c5ce7;
            margin-top: 10px;
            font-size: 2em;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        h2:hover {
            color: rgb(190, 185, 255);
            transform: translateY(-5px);
        }

        form {
            width: 100%;
            margin: 5px auto;
            padding: 5px;
            background: rgb(231, 153, 153);
            border-radius: 5px;
            box-shadow: 0 12px 24px rgba(207, 30, 30, 0.1);
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        label {
            font-size: 2em;
            color: #636e72;
            margin-bottom: 6px;
            display: block;
        }

        select {
            padding: 6px;
            font-size: 1em;
            width: 70%;
            max-width: 200px;
            border: 2px solid #ddd;
            border-radius: 6px;
            background-color: rgb(165, 209, 244);
            margin-bottom: 10px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        select:hover {
            border-color: #6c5ce7;
            transform: scale(1.02);
        }

        select:focus {
            outline: none;
            border-color: #6c5ce7;
            background-color: #f9f9f9;
        }

        button {
            padding: 9px 15px;
            background-color: rgb(138, 112, 240);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: rgb(234, 14, 58);
            transform: translateY(-3px);
        }

        .container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 15px;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .group {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 30%;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .group:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .group h4 {
            font-size: 1em;
            color: #ffffff;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            background-color: #6c5ce7;
            color: white;
            padding: 10px;
            border-radius: 8px;
        }

        .result-container {
            font-size: 1.4em;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .result-container:hover {
            color: #6c5ce7;
        }

        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .input-group input[type="text"] {
            padding: 20px;
            font-size: 2em;
            border-radius: 12px;
            border: 5px solid #ddd;
            background-color: rgb(255, 158, 158);
            transition: all 0.3s ease;
            text-align: center;
        }

        .input-group input[type="text"]:focus {
            outline: none;
            border-color: #6c5ce7;
            transform: scale(1.02);
        }

        .logout-btn {
            display: block;
            width: 220px;
            text-align: center;
            margin: 30px auto;
            background-color: #e74c3c;
            color: white;
            padding: 12px;
            font-size: 1.3em;
            border-radius: 12px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-btn:hover {
            background-color: rgb(125, 227, 102);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .group {
                width: 70%;
                margin-bottom: 20px;
            }

            select, input[type="text"], button {
                width: 100%;
            }
        }
    </style>
<script>
    let addedNumbers = {result1: [], result2: [], result3: []};
    let supplementaryNumbers = {result1: "", result2: "", result3: ""};

    // Ajouter un numéro dans le label et désactiver le bouton correspondant
    function appendToResult(value, resultId) {
        const resultLabel = document.getElementById(resultId);
        let currentValue = resultLabel.innerHTML;

        // Ajouter le numéro avec un slash entre les numéros
        if (currentValue !== "") {
            resultLabel.innerHTML += '/' + value;
        } else {
            resultLabel.innerHTML += value;
        }

        // Désactiver le bouton après ajout
        disableButton(value, resultId);
        addedNumbers[resultId].push(value);
        updateResult(resultId); // Mettre à jour le résultat
    }

    // Désactiver le bouton correspondant après l'ajout du numéro
    function disableButton(value, resultId) {
        const button = document.getElementById('button' + resultId + value);
        button.disabled = true;
    }

    // Supprimer uniquement le dernier numéro ajouté dans le label et réactiver le bouton
    function removeLastNumber(resultId) {
        const resultLabel = document.getElementById(resultId);
        let currentValue = resultLabel.innerHTML;

        // Supprimer le dernier numéro et le slash
        if (currentValue !== "") {
            let numbers = currentValue.split('/');
            let lastNumber = numbers[numbers.length - 1]; // Le dernier numéro
            numbers.pop(); // Enlever le dernier numéro
            resultLabel.innerHTML = numbers.join('/');

            // Réactiver le bouton du dernier numéro supprimé
            reEnableButton(lastNumber, resultId);
        }

        // Enlever le dernier numéro de la pile
        let lastNumber = addedNumbers[resultId].pop();
        if (lastNumber) {
            reEnableButton(lastNumber, resultId);
        }
        updateResult(resultId); // Mettre à jour le résultat
    }

    // Réactiver le bouton du dernier numéro supprimé
    function reEnableButton(lastNumber, resultId) {
        const button = document.getElementById('button' + resultId + lastNumber);
        button.disabled = false;
    }

    // Mettre à jour le champ de texte des boutons supplémentaires avec un seul numéro
    function updateSupplementaryValue(value, resultId) {
        supplementaryNumbers[resultId] = value;
        document.getElementById(resultId + "Supplementary").value = value;
        updateResult(resultId); // Mettre à jour le résultat après chaque saisie
    }

    // Gérer la saisie via le clavier pour les champs de texte des boutons supplémentaires
    function handleSupplementaryInput(event, resultId) {
        const value = event.target.value;
        const validNumber = value.match(/^\d+$/); // Valider si c'est un nombre entier

        if (validNumber) {
            updateSupplementaryValue(validNumber[0], resultId);
        } else {
            event.target.value = supplementaryNumbers[resultId];
        }
    }

    // Calculer et mettre à jour le résultat
    function updateResult(resultId) {
        let total = addedNumbers[resultId].length; // Nombre de numéros dans le groupe
        let supplementaryValue = parseInt(supplementaryNumbers[resultId]) || 0;

        // Calculer le produit en fonction du groupe
        let result = 0;
        if (resultId === "result1" || resultId === "result2") {
            result = supplementaryValue * total; // G ou P
        } else if (resultId === "result3") {
            result = supplementaryValue * total * 2; // GP
        }

        // Afficher le résultat
        document.getElementById(resultId + "Result").innerHTML = "Résultat : " + result;
        updateTotalSum(); // Mettre à jour la somme totale
    }

    // Mettre à jour la somme totale des résultats
    function updateTotalSum() {
        let sum1 = parseInt(document.getElementById("result1Result").innerText.replace("Résultat : ", "")) || 0;
        let sum2 = parseInt(document.getElementById("result2Result").innerText.replace("Résultat : ", "")) || 0;
        let sum3 = parseInt(document.getElementById("result3Result").innerText.replace("Résultat : ", "")) || 0;
        let totalSum = sum1 + sum2 + sum3;
        document.getElementById("totalSum").innerText = totalSum + " TND"; // Ajouter " TND" pour l'affichage
    }

    function generateTicket() {
    // Récupérer les valeurs des combobox et autres éléments
    let combobox1Value = document.getElementById("alphabet").value;
    let combobox2Value = document.getElementById("cources").value;

    // Récupérer les mises supplémentaires
    let supplementary1 = document.getElementById('result1Supplementary').value || 0;
    let supplementary2 = document.getElementById('result2Supplementary').value || 0;
    let supplementary3 = document.getElementById('result3Supplementary').value || 0;

    // Récupérer les résultats (par exemple, les numéros des groupes)
    let result1Value = document.getElementById('result1').innerHTML || "";
    let result2Value = document.getElementById('result2').innerHTML || "";
    let result3Value = document.getElementById('result3').innerHTML || "";

    // Calculer la somme des mises
    let sumG = (supplementary1 ? parseInt(supplementary1) : 0) * (result1Value ? result1Value.split('/').length : 0);
    let sumP = (supplementary2 ? parseInt(supplementary2) : 0) * (result2Value ? result2Value.split('/').length : 0);
    let sumGP = (supplementary3 ? parseInt(supplementary3) : 0) * (result3Value ? result3Value.split('/').length : 0) * 2; // Multiplier par 2 pour GP
    let totalSum = sumG + sumP + sumGP;

    // Récupérer l'heure actuelle
    let currentTime = new Date().toLocaleString();

    // Vérification des champs obligatoires
    if (!combobox1Value || !combobox2Value || !currentTime) {
        alert("Erreur: Veuillez remplir tous les champs obligatoires.");
        return;
    }

    // Mettre à jour les champs du formulaire
    document.getElementById('totalSum').value = totalSum;
    document.getElementById('combobox1Value').value = combobox1Value;
    document.getElementById('combobox2Value').value = combobox2Value;
    document.getElementById('result1Value').value = result1Value;
    document.getElementById('result2Value').value = result2Value;
    document.getElementById('result3Value').value = result3Value;
    document.getElementById('supplementary1').value = sumG;
    document.getElementById('supplementary2').value = sumP;
    document.getElementById('supplementary3').value = sumGP;

    // Créer le contenu du ticket
    let ticketContent = `
        <div style="font-family: Arial, sans-serif; text-align: center; padding: 5px; border: 1px solid #ccc; max-width: 350px; margin: 0 auto; background-color: #f9f9f9;">
            <div style="border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 5px;">
                <h2 style="font-size: 18px; color: #333; margin: 0;">TICKET</h2>
            </div>

            <div style="margin: 5px 0; font-size: 12px; color: #555;">
                <strong>PAYS et COURSES :</strong><br>
                <span style="font-weight: bold; font-size: 14px;">${combobox1Value}</span> -- <span style="font-weight: bold; font-size: 14px;">${combobox2Value}</span>
            </div>

            <div style="margin: 5px 0; font-size: 10px; color: #777;">
                <strong>Date et Heure :</strong> ${currentTime}
            </div>

            <div style="display: flex; justify-content: ${result1Value || result2Value || result3Value ? 'space-around' : 'center'}; flex-wrap: wrap; margin: 5px 0;">
                ${result1Value ? `
                <div style="flex: 0 0 ${result2Value || result3Value ? '30%' : '10%'}; text-align: left; margin-right: 5px; margin-bottom: 5px;">
                    <h3 style="color:rgb(0, 0, 0); font-size: 14px; margin: 2px 0;">Gagnant</h3>
                    <p style="font-size: 12px; margin: 2px 0;"><strong>${result1Value}</strong></p>
                    <p style="font-size: 12px; margin: 2px 0;"><strong>Mise : </strong>${sumG}</p>
                </div>` : ''}

                ${result2Value ? `
                <div style="flex: 0 0 ${result1Value || result3Value ? '30%' : '10%'}; text-align: left; margin-right: 5px; margin-bottom: 5px;">
                    <h3 style="color:rgb(0, 0, 0); font-size: 14px; margin: 2px 0;">Place</h3>
                    <p style="font-size: 12px; margin: 2px 0;"><strong>${result2Value}</strong></p>
                    <p style="font-size: 12px; margin: 2px 0;"><strong>Mise : </strong>${sumP}</p>
                </div>` : ''}

                ${result3Value ? `
                <div style="flex: 0 0 ${result1Value || result2Value ? '30%' : '10%'}; text-align: left; margin-right: 5px; margin-bottom: 5px;">
                    <h3 style="color:rgb(0, 0, 0); font-size: 14px; margin: 2px 0;">Gagnant/Place</h3>
                    <p style="font-size: 12px; margin: 2px 0;"><strong>${result3Value}</strong></p>
                    <p style="font-size: 12px; margin: 2px 0;"><strong>Mise : </strong>${sumGP}</p>
                </div>` : ''}
            </div>

            <div style="margin-top: 5px;">
                <p style="font-size: 12px; margin: 2px 0;"><strong>Total : </strong>${totalSum}</p>
            </div>
        </div>
    `;

    // Créer une nouvelle fenêtre pour afficher le ticket
    let ticketWindow = window.open('', '', 'width=400,height=600');
    ticketWindow.document.write('<html><head><title>Ticket</title><style>body{font-family:Arial,sans-serif;padding:5px;margin:0;}</style></head><body>');
    ticketWindow.document.write(ticketContent);
    ticketWindow.document.close(); // Nécessaire pour afficher correctement le contenu

    // Imprimer directement la fenêtre
    ticketWindow.print();

    // Soumettre le formulaire après impression
    // Commenter cette ligne si vous ne voulez pas soumettre immédiatement après l'impression
    document.getElementById('ticketForm').submit();
}


  


</script>

</head>
<body>
    <h2>Bienvenue, <?php echo htmlspecialchars($user_nom); ?> !</h2>

    <form id="ticketForm" method="POST" action="">
        <input type="hidden" id="combobox1Value" name="combobox1Value">
        <input type="hidden" id="combobox2Value" name="combobox2Value">
        <input type="hidden" id="result1Value" name="result1Value">
        <input type="hidden" id="result2Value" name="result2Value">
        <input type="hidden" id="result3Value" name="result3Value">
        <input type="hidden" id="supplementary1" name="supplementary1">
        <input type="hidden" id="supplementary2" name="supplementary2">
        <input type="hidden" id="supplementary3" name="supplementary3">
        <input type="hidden" id="totalSum" name="totalSum">
    </form>

    <label for="alphabet">PAYS:</label>
    <select id="alphabet" name="alphabet" onchange="updateLabel()">
        <option value="a">a</option>
        <option value="b">b</option>
        <option value="c">c</option>
        <option value="d">d</option>
        <option value="e">e</option>
        <option value="f">f</option>
        <option value="g">g</option>
        <option value="h">h</option>
    </select>

    <label for="cources">Courses:</label>
    <select id="cources" name="cources" onchange="updateLabel()">
        <option value="C1">C1</option>
        <option value="C2">C2</option>
        <option value="C3">C3</option>
        <option value="C4">C4</option>
        <option value="C5">C5</option>
        <option value="C6">C6</option>
        <option value="C7">C7</option>
        <option value="C8">C8</option>
        <option value="C9">C9</option>
    </select>

    <div>
        <span id="resultLabel"></span>
    </div>

    <!-- Nouveau label pour afficher la somme -->
    <div>
    <input type="hidden" id="totalSum" name="totalSum">
    <button type="button" class="ticket-btn" onclick="generateTicket()">Générer le ticket</button>
    <div id="ticketDisplay"></div>
    <style>
    /* Style pour le bouton spécifique "Générer le ticket" */
.ticket-btn {
    padding: 12px 24px; /* Espace à l'intérieur du bouton */
    background-color: #00b894; /* Couleur verte moderne */
    color: white;
    border: none;
    border-radius: 10px; /* Coins légèrement arrondis */
    cursor: pointer;
    font-size: 2em; /* Taille du texte */
    box-shadow: 0 4px 8px rgba(244, 52, 52, 0.1); /* Ombre discrète */
    transition: all 0.3s ease; /* Transition fluide */
    display: flex;
    justify-content: center; /* Centre horizontalement */
    align-items: center; /* Centre verticalement */
    height: 10vh; /* Hauteur de l'écran */
}

.ticket-btn:hover {
    background-color:rgb(254, 16, 71); /* Couleur au survol */
    transform: translateY(-2px); /* Légère élévation */
    box-shadow: 0 8px 16px rgba(47, 192, 232, 0.2); /* Ombre plus forte au survol */
}

.ticket-btn:active {
    background-color: #00a859; /* Couleur lors du clic */
    transform: translateY(1px); /* Légère descente au clic */
}

</style>
    </div>

    <?php if ($user_role == 'utilisateur'): ?>
        <div class="container">
    <!-- Premier groupe (G) -->
    <div class="group">
        <h4>Gagnant</h4>
        <div class="result-container" id="result1Result">Résultat : 0</div>
        <label id="result1" class="result-label"></label><br>
        <div class="button-group">
            <?php for ($i = 1; $i <= 20; $i++): ?>
                <button type="button" id="buttonresult1<?php echo $i; ?>" onclick="appendToResult('<?php echo $i; ?>', 'result1')"><?php echo $i; ?></button>
            <?php endfor; ?>
        </div>
        <button type="button" onclick="removeLastNumber('result1')">Effacer</button>
    </div>

    <!-- Deuxième groupe (P) -->
    <div class="group">
        <h4>Place</h4>
        <div class="result-container" id="result2Result">Résultat : 0</div>
        <label id="result2" class="result-label"></label><br>
        <div class="button-group">
            <?php for ($i = 1; $i <= 20; $i++): ?>
                <button type="button" id="buttonresult2<?php echo $i; ?>" onclick="appendToResult('<?php echo $i; ?>', 'result2')"><?php echo $i; ?></button>
            <?php endfor; ?>
        </div>
        <button type="button" onclick="removeLastNumber('result2')">Effacer</button>
    </div>

    <!-- Troisième groupe (GP) -->
    <div class="group">
        <h4>Gagnany-Place</h4>
        <div class="result-container" id="result3Result">Résultat : 0</div>
        <label id="result3" class="result-label"></label><br>
        <div class="button-group">
            <?php for ($i = 1; $i <= 20; $i++): ?>
                <button type="button" id="buttonresult3<?php echo $i; ?>" onclick="appendToResult('<?php echo $i; ?>', 'result3')"><?php echo $i; ?></button>
            <?php endfor; ?>
        </div>
        <button type="button" onclick="removeLastNumber('result3')">Effacer</button>
    </div>
</div>


        <div class="container">
            <div class="group">
                <input type="text" id="result1Supplementary" class="result-label" oninput="handleSupplementaryInput(event, 'result1')" />
                <div class="button-group">
                    <?php foreach ([1, 2, 3, 4, 5, 10, 20, 50, 100, 200] as $value): ?>
                        <button type="button" onclick="updateSupplementaryValue('<?php echo $value; ?>', 'result1')"><?php echo $value; ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="group">
                <input type="text" id="result2Supplementary" class="result-label" oninput="handleSupplementaryInput(event, 'result2')" />
                <div class="button-group">
                    <?php foreach ([1, 2, 3, 4, 5, 10, 20, 50, 100, 200] as $value): ?>
                        <button type="button" onclick="updateSupplementaryValue('<?php echo $value; ?>', 'result2')"><?php echo $value; ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="group">
                <input type="text" id="result3Supplementary" class="result-label" oninput="handleSupplementaryInput(event, 'result3')" />
                <div class="button-group">
                    <?php foreach ([1, 2, 3, 4, 5, 10, 20, 50, 100, 200] as $value): ?>
                        <button type="button" onclick="updateSupplementaryValue('<?php echo $value; ?>', 'result3')"><?php echo $value; ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <a href="logout.php" class="logout-btn"><strong>Se déconnecter</strong></a>
</body>
</html>
