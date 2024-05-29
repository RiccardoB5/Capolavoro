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

// Se l'utente non è loggato, reindirizza alla pagina di login
if (!isset($_SESSION['ID_Utente'])) {
    header('Location: login.php');
    exit();
}

$successMessage = "";

// Funzione per aggiungere al carrello
if (isset($_POST['aggiungi_carrello'])) {
    $ID_Prodotto = $_POST['ID_Prodotto'];
    $quantita = $_POST['quantita'];

    $sql = "INSERT INTO carrello (ID_Utente, ID_Prodotto, Quantita) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $_SESSION['ID_Utente'], $ID_Prodotto, $quantita);
    $stmt->execute();

    if ($stmt->error) {
        echo "<p class='feedback'>Errore: " . $stmt->error . "</p>";
    } else {
        $successMessage = "Prodotto aggiunto al carrello con successo!";
    }

    $stmt->close();
}

// Recupera i prodotti per il menu a tendina, filtrando solo le marmitte
$sql = "SELECT ID_Prodotto, Nome FROM anagrafica_prodotti WHERE Tipo = 'Marmitta'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acquista Prodotto</title>
    <!-- Aggiungi il link a Font Awesome -->
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
        .feedback {
            color: #ff0000;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"], select, input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 10px;
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
        .popup {
            visibility: hidden;
            opacity: 0;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: #fff;
            font-size: 18px;
            transition: visibility 0s, opacity 0.5s;
        }
        .popup.show {
            visibility: visible;
            opacity: 1;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    <script>
        function showPopup(message) {
            var popup = document.getElementById('popup');
            popup.innerText = message;
            popup.classList.add('show');
            setTimeout(function() {
                popup.classList.remove('show');
            }, 3000);
        }
    </script>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h2>Acquista Prodotto</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="prodotti">Scegli un prodotto:</label>
                <select name="ID_Prodotto" id="prodotti">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <option value="<?php echo $row['ID_Prodotto']; ?>"><?php echo $row['Nome']; ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="0">Nessun prodotto disponibile</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantita">Quantità:</label>
                <input type="number" id="quantita" name="quantita" min="1" value="1">
            </div>
            <button type="submit" name="aggiungi_carrello">Aggiungi al Carrello</button>
        </form>
    </div>
    <?php if ($successMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showPopup("<?php echo $successMessage; ?>");
            });
        </script>
    <?php endif; ?>
    <div id="popup" class="popup"></div>
</body>
</html>
