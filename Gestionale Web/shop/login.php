<?php
session_start();

// Controllo se l'utente è già loggato
if (isset($_SESSION['ID_Utente'])) {
    header('Location: index.php');
    exit();
}

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

$feedback = "";

// Funzione per il login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT ID_Utente, Password FROM utenti WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['ID_Utente'] = $row['ID_Utente'];
            header('Location: index.php');
            exit();
        } else {
            $feedback = "Password errata!";
        }
    } else {
        $feedback = "Utente non trovato!";
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
    <title>Login</title>
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
            display: flex;
            flex-direction: column;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.8); /* Colore nero trasparente */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
            opacity: 0;
            animation: fadeIn 2s ease-in-out forwards; /* Animazione di dissolvenza */
        }
        .feedback {
            color: #ff0000;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 10px;
        }
        button {
            width: calc(100% - 20px);
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
        .register-link {
            margin-top: 20px;
            display: block;
            color: #fff;
            text-decoration: none;
            transition: color 0.3s, transform 0.3s;
        }
        .register-link:hover {
            color: #ffcc00;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h2>Login</h2>
        <?php if ($feedback): ?>
            <p class="feedback"><?php echo $feedback; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        <a class="register-link" href="register.php">Non hai un account? Registrati</a>
    </div>
</body>
</html>
