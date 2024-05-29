<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Marmitte</title>
    <!-- Link a Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #000; /* Sfondo nero */
        }
        .fade-in {
            opacity: 0;
            animation: fadeIn 2s ease-in-out forwards;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
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
        .navbar .nav-links, .navbar .actions {
            display: flex;
            align-items: center;
        }
        .navbar .logo img {
            height: 40px;
        }
        .navbar i {
            margin-right: 8px;
        }
        .background {
            background-image: url('image/sfondo.png'); /* Inserisci il tuo percorso di immagine di sfondo */
            background-size: cover;
            background-position: center;
            height: calc(100% - 50px); /* Altezza meno la barra di navigazione */
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden;
            opacity: 0; /* Inizia con opacità 0 */
            animation: fadeInContent 2s ease-in-out 0.5s forwards; /* Ritardo per sincronizzare con l'animazione generale */
        }
        .background::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }
        .background h1 {
            font-size: 48px;
            margin: 0;
            padding: 20px;
            z-index: 2;
        }
        .background .content {
            position:relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .background .cta-button, .background .catalog-button {
            margin-top: 20px;
            padding: 10px 20px;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        .background .cta-button {
            background-color: #ff0000; /* Rosso */
        }
        .background .cta-button:hover {
            background-color: #cc0000;
            transform: scale(1.1);
        }
        .background .catalog-button {
            background-color: #000; /* Nero */
        }
        .background .catalog-button:hover {
            background-color: #333;
            transform: scale(1.1);
        }
        .cart-icon, .login-icon, .admin-icon {
            font-size: 24px;
            margin-left: 10px;
        }
        @keyframes fadeInContent {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="fade-in">
    <div class="navbar">
        <div class="logo">
            <a href="index.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="nav-links">
            <a href="index.php"><i class="fas fa-home"></i>Home</a>
            <a href="catalogo.php"><i class="fas fa-th-list"></i>Catalogo</a>
            <a href="acquista.php"><i class="fas fa-shopping-bag"></i>Acquista</a>
        </div>
        <div class="actions">
            <a href="carrello.php"><i class="fas fa-shopping-cart cart-icon"></i></a>
            <a href="login.php"><i class="fas fa-sign-in-alt login-icon"></i></a>
            <a href="../index.php"><i class="fas fa-user-cog admin-icon"></i></a> 
        </div>
    </div>
    <div class="background">
        <div class="content">
            <h1>Benvenuti nel nostro negozio di marmitte!</h1>
            <button class="cta-button" onclick="window.location.href='acquista.php'">Inizia a Comprare</button>
            <button class="catalog-button" onclick="window.location.href='catalogo.php'">Vai al Catalogo</button>
        </div>
    </div>
</body>
</html>
