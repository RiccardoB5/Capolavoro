<?php
session_start();

// Configurazione database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fabbricamarmitte";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Controllo se l'utente è loggato
if (!isset($_SESSION['ID_Utente'])) {
    header('Location: login.php');
    exit();
}

// Variabile per memorizzare il messaggio di feedback
$feedback = "";
$feedback_type = "";

// Funzione per rimuovere un prodotto dal carrello
if (isset($_POST['rimuovi'])) {
    $ID_Carrello = $_POST['ID_Carrello'];
    
    $sql = "DELETE FROM carrello WHERE ID_Carrello = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ID_Carrello);
    $stmt->execute();

    if ($stmt->error) {
        $feedback = "Errore: " . $stmt->error;
        $feedback_type = "error";
    } else {
        $feedback = "Prodotto rimosso dal carrello con successo!";
        $feedback_type = "success";
    }

    $stmt->close();
}

// Funzione per completare l'acquisto e svuotare il carrello
if (isset($_POST['procedi_al_pagamento'])) {
    $ID_Utente = $_SESSION['ID_Utente'];
    
    // Qui potrebbe andare la logica di pagamento
    
    // Svuotare il carrello
    $sql = "DELETE FROM carrello WHERE ID_Utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ID_Utente);
    $stmt->execute();

    if ($stmt->error) {
        $feedback = "Errore durante l'eliminazione del carrello: " . $stmt->error;
        $feedback_type = "error";
    } else {
        $feedback = "Acquisto completato con successo! Il tuo carrello è stato svuotato.";
        $feedback_type = "success";
    }

    $stmt->close();
}

// Recupera i prodotti nel carrello dell'utente
$carrello = [];
if (isset($_SESSION['ID_Utente'])) {
    $ID_Utente = $_SESSION['ID_Utente'];
    
    $sql = "SELECT c.ID_Carrello, p.Nome, c.Quantita, l.Prezzo, (c.Quantita * l.Prezzo) AS Totale
            FROM carrello c
            JOIN anagrafica_prodotti p ON c.ID_Prodotto = p.ID_Prodotto
            JOIN listino_prezzi l ON p.ID_Prodotto = l.ID_Prodotto
            WHERE c.ID_Utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ID_Utente);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $carrello[] = $row;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrello</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('image/sfondo.png');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            color: #fff;
            height: 100vh;
            overflow: auto;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s, transform 0.3s;
            display: flex;
            align-items: center;
        }
        .navbar a:hover {
            background-color: #ff6600;
            transform: scale(1.1);
        }
        .navbar .nav-links, .navbar .login, .navbar .cart {
            display: flex;
            align-items: center;
        }
        .navbar .logo img {
            height: 40px;
        }
        .navbar i {
            margin-right: 8px;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.8); /* Colore nero trasparente */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            opacity: 0;
            animation: fadeIn 2s ease-in-out forwards; /* Animazione di dissolvenza */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #444;
        }
        td {
            background-color: rgba(0, 0, 0, 0.8);
        }
        .feedback {
            display: none;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
        }
        .feedback.success {
            background-color: #4CAF50;
            color: white;
        }
        .feedback.error {
            background-color: #f44336;
            color: white;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #ff0000; /* Colore rosso */
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }
        button:hover {
            background-color: #cc0000;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <a href="index.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="nav-links">
            <a href="index.php"><i class="fas fa-home"></i>Home</a>
            <a href="catalogo.php"><i class="fas fa-th-list"></i>Catalogo</a>
            <a href="acquista.php"><i class="fas fa-shopping-bag"></i>Acquista</a>
        </div>
        <div class="cart">
            <a href="carrello.php"><i class="fas fa-shopping-cart"></i></a>
        </div>
    </div>
    <div class="container">
        <h2>Il tuo Carrello</h2>
        <div class="feedback <?php echo $feedback_type; ?>" id="feedback"><?php echo $feedback; ?></div>
        <?php if (empty($carrello)): ?>
            <p>Il tuo carrello è vuoto.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Quantità</th>
                        <th>Prezzo</th>
                        <th>Totale</th>
                        <th>Azione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrello as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['Nome']); ?></td>
                            <td><?php echo htmlspecialchars($item['Quantita']); ?></td>
                            <td>€<?php echo htmlspecialchars(number_format($item['Prezzo'], 2)); ?></td>
                            <td>€<?php echo htmlspecialchars(number_format($item['Totale'], 2)); ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="ID_Carrello" value="<?php echo $item['ID_Carrello']; ?>">
                                    <button type="submit" name="rimuovi">Rimuovi</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <form method="post" action="">
                <button type="submit" name="procedi_al_pagamento">Procedi al pagamento</button>
            </form>
        <?php endif; ?>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var feedback = document.getElementById("feedback");
            if (feedback.textContent.trim() !== "") {
                feedback.style.display = "block";
                setTimeout(function() {
                    feedback.style.display = "none";
                }, 3000);
            }
        });
    </script>
</body>
</html>
