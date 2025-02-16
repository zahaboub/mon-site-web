<?php
session_start();

// Ensure the user is logged in and has the correct role (assuming admin role).
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: logout.php");
    exit();
}

$username = $_SESSION['username'] ?? 'Utilisateur inconnu';

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "mon-site-db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all tickets ordered by date (grouped by day)
$sql = "SELECT * FROM tickets ORDER BY date DESC"; // Sorting by date
$result = $conn->query($sql);

// Check if any tickets were returned
if ($result->num_rows > 0) {
    $tickets = [];
    
    // Group tickets by date
    while ($row = $result->fetch_assoc()) {
        $date = $row['date'];
        if (!isset($tickets[$date])) {
            $tickets[$date] = [];
        }
        $tickets[$date][] = $row;
    }

    // Start building the table
    echo "<h1>Dashboard des Tickets</h1>";
    echo "<table>";
    echo "<thead><tr><th>ID du Ticket</th><th>Pays Sélectionné</th><th>Cource</th><th>CH Gangants</th><th>CH Place</th><th>CH Gagnant_Place</th><th>Mise Gagnant</th><th>Mise Place</th><th>Mise Gagnant_Place</th><th>Somme Totale</th><th>Date de Création</th><th>Utilisateur</th></tr></thead>";
    echo "<tbody>";

    // Output each group of tickets by date
    foreach ($tickets as $date => $dateTickets) {
        echo "<tr class='date-row'><td colspan='12'><strong>Date: $date</strong></td></tr>"; // Displaying the date as a row header

        foreach ($dateTickets as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>"; // Assuming you have an 'id' field
            echo "<td>" . htmlspecialchars($row['combobox1']) . "</td>";
            echo "<td>" . htmlspecialchars($row['combobox2']) . "</td>";
            echo "<td>" . htmlspecialchars($row['result1']) . "</td>";
            echo "<td>" . htmlspecialchars($row['result2']) . "</td>";
            echo "<td>" . htmlspecialchars($row['result3']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sumG']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sumP']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sumGP']) . "</td>";
            echo "<td>" . htmlspecialchars($row['total_sum']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "</tr>";
        }
    }

    echo "</tbody></table>";
} else {
    echo "<p>Aucun ticket trouvé.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <style>
    /* Global Styling */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f5f8fa;
        color: #333;
        margin: 0;
        padding: 0;
    }

    /* Header Styling */
    h1 {
        font-size: 32px;
        color: #2c3e50;
        margin: 20px 0;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        background-color: #ffffff;
    }

    th, td {
        padding: 16px;
        text-align: left;
        font-size: 16px;
        border-bottom: 2px solid #ecf0f1;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    th {
        background-color: #2980b9;
        color: #ffffff;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        background-color: #ecf0f1;
        color: #34495e;
        font-weight: 300;
    }

    /* Alternating Row Colors */
    tr:nth-child(even) td {
        background-color: #f9f9f9;
    }

    /* Hover Effects */
    tr:hover td {
        background-color: #d5dbdb;
        color: #2980b9;
        cursor: pointer;
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out, background-color 0.2s ease;
    }

    /* Date Grouping Row */
    .date-row td {
        background-color: #f39c12;
        font-weight: bold;
        text-align: center;
        color: white;
        font-size: 18px;
        border-top: 3px solid #ffffff;
    }

    /* Responsive Table */
    @media screen and (max-width: 768px) {
        table {
            font-size: 14px;
        }

        th, td {
            padding: 12px;
        }
    }

    /* Logout Button Styling */
    a {
        text-decoration: none;
        padding: 12px 20px;
        background-color: #e74c3c;
        color: white;
        border-radius: 30px;
        margin-top: 30px;
        display: inline-block;
        font-size: 16px;
        text-align: center;
        transition: background-color 0.3s ease, transform 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    a:hover {
        background-color: #c0392b;
        transform: scale(1.05);
    }

    a:active {
        transform: scale(0.98);
    }

    /* Table Responsive View */
    @media screen and (max-width: 600px) {
        th, td {
            font-size: 12px;
        }

        /* Hide specific columns in mobile view if needed */
        td:nth-child(n+7), th:nth-child(n+7) {
            display: none;
        }
    }

    /* Table Row Expansion Effect (optional) */
    tr.expandable:hover {
        background-color: #f4f6f7;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Adding icons for better visuals in the table */
    th i, td i {
        margin-right: 8px;
    }

    /* Add icon for specific columns if needed */
    td:first-child {
        text-align: center;
    }

</style>


</head>
<body>
    <a href="logout.php">Se Déconnecter</a>
</body>
</html>
