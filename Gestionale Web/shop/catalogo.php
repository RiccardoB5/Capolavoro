<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo Marmitte</title>
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
        .catalogo-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.8); /* Colore nero trasparente */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            opacity: 0;
            animation: fadeIn 2s ease-in-out forwards; /* Animazione di dissolvenza */
        }
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            gap: 10px;
        }
        .search-bar input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .search-bar button {
            width: 15%; /* Metà della larghezza del campo di testo */
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #ff0000; /* Colore rosso */
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .search-bar button:hover {
            background-color: #cc0000;
            transform: scale(1.1);
        }
        .product {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: rgba(0, 0, 0, 0.8); /* Colore nero trasparente */
            display: flex;
            justify-content: space-between;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .product-info {
            flex: 1;
            margin-left: 20px;
        }
        .product-info h3 {
            margin-top: 0;
            color: #fff;
        }
        .product-info p {
            color: #ccc;
        }
        .feedback {
            color: #ff0000;
            font-weight: bold;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="catalogo-container">
        <div class="search-bar">
            <form method="GET" action="" style="display: flex; width: 100%; gap: 10px;">
                <input type="text" name="query" placeholder="Cerca per marca">
                <button type="submit">Cerca</button>
                <button type="submit" name="all" value="true">Mostra Tutto</button> <!-- Pulsante per mostrare tutte le marmitte -->
            </form>
        </div>
        <div id="product-list">
            <?php
            if (isset($_GET['query']) || isset($_GET['all'])) {
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "fabbricamarmitte";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT p.ID_Prodotto, p.Nome, p.Descrizione, p.Tipo, m.Quantita, l.Prezzo
                        FROM anagrafica_prodotti p
                        JOIN magazzino_prodotti_finiti m ON p.ID_Prodotto = m.ID_Prodotto_Finito
                        JOIN listino_prezzi l ON p.ID_Prodotto = l.ID_Prodotto
                        WHERE m.Quantita > 0";

                if (isset($_GET['query']) && !isset($_GET['all'])) {
                    $query = '%' . $_GET['query'] . '%';
                    $sql .= " AND p.Nome LIKE ?";
                }

                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    if (isset($_GET['query']) && !isset($_GET['all'])) {
                        $stmt->bind_param("s", $query);
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='product'>
                                    <div class='product-info'>
                                        <h3>" . htmlspecialchars($row['Nome']) . "</h3>
                                        <p>ID: " . htmlspecialchars($row['ID_Prodotto']) . "</p>
                                        <p>" . htmlspecialchars($row['Descrizione']) . "</p>
                                        <p>Tipo: " . htmlspecialchars($row['Tipo']) . "</p>
                                        <p>Prezzo: €" . htmlspecialchars(number_format($row['Prezzo'], 2)) . "</p>
                                        <p>Quantità disponibile: " . htmlspecialchars($row['Quantita']) . "</p>
                                    </div>
                                  </div>";
                        }
                    } else {
                        echo "<p class='feedback'>Nessun prodotto trovato o non disponibile in magazzino.</p>";
                    }
                    $stmt->close();
                } else {
                    echo "<p class='feedback'>Errore nella preparazione della query: " . $conn->error . "</p>";
                }
                $conn->close();
            }
            ?>
        </div>
    </div>
</body>
</html>
