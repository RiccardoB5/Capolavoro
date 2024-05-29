<?php
//1. Customers
$query ="SELECT COUNT(*) FROM `utenti`";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($customers);
$stmt->fetch();
$stmt->close();

//2. Prodotti
$query = "SELECT SUM(Quantita) FROM `magazzino_prodotti_finiti`";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($products);
$stmt->fetch();
$stmt->close();


//3. Orders
$query = "SELECT SUM(Quantità) FROM `magazzino_componenti`";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($orders);
$stmt->fetch();
$stmt->close();

// Soldi spesi comprando dai fornitori
$query = "SELECT SUM(Costo * Quantità) AS TotaleSpeso FROM `acquisti_fornitori`";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($totalSpent);
$stmt->fetch();
$stmt->close();

?>
